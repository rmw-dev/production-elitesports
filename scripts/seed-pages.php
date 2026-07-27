<?php
/**
 * Seed the interior pages (Coaches, Academics, Tuition, Privacy Policy,
 * Terms of Service) with populated ACF blocks. Idempotent: reuses pages
 * matched by slug, creating them when missing, and overwrites their content.
 *
 * Run from the Bedrock root:
 *   wp eval-file scripts/seed-pages.php
 */

if (! function_exists('acf_get_fields')) {
    fwrite(STDERR, "ACF is not active.\n");
    exit(1);
}

/* ---- Shared helpers (guarded; mirror scripts/seed-homepage.php) ---------- */

if (! function_exists('esa_flatten_acf')) {
    function esa_flatten_acf(array $fields, array $data, string $prefix, array &$out): void
    {
        foreach ($fields as $field) {
            $name = $field['name'] ?? '';

            if ($name === '' || ! array_key_exists($name, $data)) {
                continue;
            }

            $key = $field['key'];
            $value = $data[$name];

            if ($field['type'] === 'group' && ! empty($field['sub_fields'])) {
                $out["{$prefix}{$name}"] = '';
                $out["_{$prefix}{$name}"] = $key;
                esa_flatten_acf($field['sub_fields'], (array) $value, "{$prefix}{$name}_", $out);
                continue;
            }

            if ($field['type'] === 'repeater' && ! empty($field['sub_fields'])) {
                $rows = (array) $value;
                $out["{$prefix}{$name}"] = count($rows);
                $out["_{$prefix}{$name}"] = $key;

                foreach (array_values($rows) as $i => $row) {
                    esa_flatten_acf($field['sub_fields'], (array) $row, "{$prefix}{$name}_{$i}_", $out);
                }

                continue;
            }

            $out["{$prefix}{$name}"] = $value;
            $out["_{$prefix}{$name}"] = $key;
        }
    }
}

if (! function_exists('esa_block')) {
    function esa_block(string $blockName, string $groupKey, array $data, array $extra = []): string
    {
        $fields = acf_get_fields($groupKey);

        if (! $fields) {
            fwrite(STDERR, "Field group {$groupKey} not found; skipping {$blockName}.\n");

            return '';
        }

        // Default vertical spacing to match the homepage rhythm. Hero-style
        // blocks that carry their own large internal padding pass smaller
        // values explicitly, so only fill these in when absent.
        $data += [
            'padding_top' => 12,
            'padding_bottom' => 12,
        ];

        $flat = [];
        esa_flatten_acf($fields, $data, '', $flat);

        $attributes = array_merge([
            'name' => $blockName,
            'data' => $flat,
            'align' => 'full',
            'mode' => 'preview',
        ], $extra);

        return '<!-- wp:' . $blockName . ' '
            . wp_json_encode($attributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            . ' /-->';
    }
}

if (! function_exists('esa_attachment_id')) {
    function esa_attachment_id(string $like): int
    {
        $q = new WP_Query([
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'posts_per_page' => 1,
            'fields' => 'ids',
            's' => $like,
        ]);

        return $q->posts[0] ?? 0;
    }
}

/** Resolve an existing page by slug, or create it. Returns the post ID. */
function esa_page(string $slug, string $title): int
{
    $existing = get_page_by_path($slug);

    if ($existing instanceof WP_Post) {
        if ($existing->post_status !== 'publish') {
            wp_update_post(['ID' => $existing->ID, 'post_status' => 'publish']);
        }

        return $existing->ID;
    }

    $id = wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => '',
    ]);

    return is_wp_error($id) ? 0 : (int) $id;
}

/** Save a stack of block comments to a page (slashed so quotes survive). */
function esa_save(int $pageId, array $blocks): void
{
    $content = implode("\n\n", array_filter($blocks));

    wp_update_post([
        'ID' => $pageId,
        'post_content' => wp_slash($content),
    ]);
}

/**
 * Convert the reference legal markdown into HTML for the prose-page wysiwyg.
 * Mirrors LegalDocumentContent() from the React source.
 */
function esa_legal_html(string $path, string $titleLabel): string
{
    if (! is_file($path)) {
        fwrite(STDERR, "Legal source missing: {$path}\n");

        return '';
    }

    $raw = (string) file_get_contents($path);
    $raw = preg_replace('/^```text\s*/u', '', trim($raw));
    $raw = preg_replace('/\s*```\s*$/u', '', (string) $raw);

    $blocks = preg_split('/\n{2,}/u', trim((string) $raw)) ?: [];
    $html = '';

    foreach ($blocks as $i => $block) {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), 'strlen'));

        if (! $lines) {
            continue;
        }

        $first = $lines[0];

        if ($i === 0 && $first === $titleLabel) {
            continue;
        }

        if (preg_match('/^Last updated:/u', $first)) {
            $html .= '<p class="privacy-policy-updated">' . esa_legal_inline($first) . '</p>';
            continue;
        }

        if (preg_match('/^\d+\.\s+/u', $first)) {
            $html .= '<h2>' . esc_html($first) . '</h2>';
            foreach (array_slice($lines, 1) as $line) {
                $html .= '<p>' . esa_legal_inline($line) . '</p>';
            }
            continue;
        }

        $allBullets = true;
        foreach ($lines as $line) {
            if (strpos($line, '- ') !== 0) {
                $allBullets = false;
                break;
            }
        }

        if ($allBullets) {
            $html .= '<ul>';
            foreach ($lines as $line) {
                $html .= '<li>' . esa_legal_inline(substr($line, 2)) . '</li>';
            }
            $html .= '</ul>';
            continue;
        }

        foreach ($lines as $line) {
            $html .= '<p>' . esa_legal_inline($line) . '</p>';
        }
    }

    return $html;
}

/** Escape a line of legal text and linkify URLs + emails. */
function esa_legal_inline(string $text): string
{
    $escaped = esc_html($text);

    $escaped = preg_replace_callback(
        '#https?://[^\s<]+#u',
        static fn ($m) => '<a href="' . esc_url($m[0]) . '" target="_blank" rel="noopener noreferrer">' . $m[0] . '</a>',
        $escaped
    );

    $escaped = preg_replace_callback(
        '/[\w.+-]+@[\w.-]+\.[A-Za-z]{2,}/u',
        static fn ($m) => '<a href="mailto:' . esc_attr($m[0]) . '">' . $m[0] . '</a>',
        (string) $escaped
    );

    return (string) $escaped;
}

/* ---- Shared CTAs -------------------------------------------------------- */
$applyUrl = 'https://heritageacademy.schoolmint.net/';
$tourUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSdagnRNZfXdf5yl_XXAaBS2Cn_DgD7qNzdJElZUtp8ngKnxoA/viewform';

$ctaButtons = [
    ['label' => 'Apply Now', 'url' => $applyUrl, 'new_tab' => 1, 'variant' => 'primary'],
    ['label' => 'Schedule a Tour', 'url' => $tourUrl, 'new_tab' => 1, 'variant' => 'secondary'],
];

/* ===================================================================== */
/* COACHES                                                               */
/* ===================================================================== */

$coachData = [
    ['MIKE GIOVANDO', 'QB Coach/OC', 'Mike_Giovando', 'purple', '50% 28%',
        'Coach Gio brings 32 years of football coaching, with 26 dedicated to quarterbacks from youth to the NFL. He has trained 1,500+ QBs, securing 400 scholarships and guiding players to top colleges and the NFL. Notably, Spencer Rattler (Saints, 2024 Draft) and Jack Plummer (Panthers) trained under him. Gio has run multiple Pro Days and his alumni span schools like Oklahoma, Michigan, Ohio State, and programs such as the Falcons, Giants, and Saints.'],
    ['RON SOWERS', 'OL/DL Coach', 'Ron_Sowers', 'orange', '50% 28%',
        'Ron Sowers, former ASU captain and NFL/USFL player, has 30+ years coaching in the Valley. He coached at ASU, Mesa CC, Scottsdale CC, and multiple state-championship high schools, earning 14 titles. Ron developed training equipment to improve strength, technique, and pass-pro skills, helping 100+ athletes earn scholarships, 60% at the D1 level. His career includes ASU O-line coach (’84-’85) and pro stints with the Chiefs, Wranglers, and Gunslingers.'],
    ['MARCUS PITTMAN', 'Coach', 'Marcus_Pitman', 'purple', '50% 28%',
        'Troy University alum and former AFL standout, Pittman played from 2010-2016 with the Arizona Rattlers and Los Angeles KISS, winning 3 championships and recording 28 sacks. A dominant defensive lineman, he earned a reputation for his strength and consistency. After his playing career, Pittman transitioned to coaching and now serves as Defensive Line Coach at Boulder Creek High School, where he develops athletes with strong fundamentals, discipline, and technique.'],
    ['LEWIS REDMOND ‘Coach Red’', 'DC/LB Coach', 'Lewis_Redmond', 'orange', '50% 28%',
        'Lewis "Coach Red" Redmond, DC/LB Coach, is a former D1 safety with 17 years of HS coaching and 3 years as a head coach. With 20+ years as a CPT and CSCS II, he has developed elite talent, including 11 former players now competing at the Division 1 level.'],
    ['RYAN "Sweetfeet" PEETE', 'Coach', 'Ryan_Peete', 'purple', '50% 25%',
        'Ryan "Sweetfeet" Peete, owner of Sweetfeet Training in Arizona, has 12+ years specializing in speed, agility, and position-specific programs. He has trained multiple D1 athletes and 5+ NFL players. With a Kinesiology background from Bethel College and GCU, Ryan partners with high schools and youth programs across Phoenix, creating customized training that builds athletic performance and long-term success.'],
    ['MARQUIS BRAY', 'Football Coach', 'Marcus_Bray', 'orange', '50% 22%',
        'Marquis "Coach Quis" Bray is a military veteran and defensive specialist with 7 years of coaching experience, including leading the 10U Big Cats to a national championship. A former player in arena, semi-pro, and overseas football, he excels at analyzing offenses and teaching disciplined, high-performing defensive play, instilling focus, work ethic, and strategy in every athlete he coaches.'],
    ['CHRIS WOOLWINE', 'Track Coach', 'Chris_Woolwine', 'purple', '50% 22%',
        'Chris Woolwine is a former business owner and current owner of EPA Track Club, a premier program known for developing elite athletes. Under his leadership, EPA has produced numerous national champions and All-Americans. With a passion for mentorship and performance, Woolwine is dedicated to helping athletes maximize their potential, build discipline, and achieve success both on and off the track.'],
    ['CHRIS STUART', 'Sports Agent', 'Chris_Stuart', 'orange', '50% 25%',
        'CEO & President of Encore Sports & Entertainment, Chris co-founded the firm in 2004 and has built a reputation representing elite athletes and corporations, including Drew Brees, CC Sabathia, and Matt Kemp. Formerly with Upper Deck, he secured deals with icons like Tiger Woods, Michael Jordan, and LeBron James. A University of Arizona graduate and USD School of Law alum, Stuart launched his career with the San Diego Chargers and has been a member of the CA State Bar since 1996.'],
    ['STEPHEN VILLINES', 'Baseball Coach', 'Stephen_Villines', 'purple', '50% 24%',
        'Former pro pitcher, Stephen starred at the University of Kansas, where he became the all-time saves leader and a 2x Cape Cod League player. Drafted in the 10th round of the 2017 MLB Draft by the Mets, he played 6 seasons in Minor League Baseball with the Mets and Rangers, reaching Triple-A. With experience across two MLB organizations, Villines now channels his playing career into coaching and player development, helping athletes maximize their potential.'],
    ['BEN VILLINES', 'Pitching Coach', 'Ben_Villines', 'orange', '50% 28%',
        'Pitching coach at Glendale CC, Ben is a former Southern California pitcher from El Toro HS and Saddleback College. After injuries ended his playing career, he spent nearly a decade developing pitchers of all ages. From 2018-2023, he co-founded Baseball Concepts in Michigan, training hundreds of athletes, before launching Baseball Concepts AZ. A Driveline-certified coach, he specializes in customized throwing programs focused on performance and arm health.'],
    ['SEAN GREENE', 'Basketball Coach', 'Sean_Greene', 'purple', '50% 24%',
        'Sean Greene is CEO of Hoop Code Basketball Academy, Arizona’s leading youth basketball development organization. He is dedicated to helping athletes grow through elite training that develops the mind, body, and skill set while building character on and off the court. Under his leadership, Hoop Code provides high-level coaching from experienced players and trainers. Greene also holds an MBA in Digital Communication and Media from Stanford University Graduate School of Business.'],
    ['MARJAHN SCALES', 'Basketball Coach', 'Marjahn_Scales', 'orange', '50% 26%',
        'MarJahn Scales is a dedicated basketball coach with 8+ years at Hoop Code Academy, developing athletes on and off the court. He is an AAU coach for Blue Chip Elite and spent two years with GCU Club Basketball. Currently, he serves as Open Division Coach at AZ Compass Prep, where he prepares athletes to compete at the highest level. Passionate about mentorship and player growth, Coach Scales is committed to building well-rounded athletes.'],
];

$coachRows = [];
foreach ($coachData as $c) {
    $coachRows[] = [
        'name' => $c[0],
        'role' => $c[1],
        'photo' => esa_attachment_id($c[2]),
        'object_position' => $c[4],
        'accent' => $c[3],
        'bio' => $c[5],
    ];
}

$coachesPage = esa_page('coaches', 'Coaches');
esa_save($coachesPage, [
    esa_block('acf/coaches', 'group_coaches', [
        'eyebrow' => 'Elite Sports Academy',
        'title' => 'Meet Our Team',
        'body' => 'Coaches, trainers, and athlete-development leaders building the Elite standard across sport, academics, and character.',
        'coaches' => $coachRows,
        'padding_top' => 1,
        'padding_bottom' => 1,
    ]),
    esa_block('acf/cta-banner', 'group_cta_banner', [
        'eyebrow' => 'Student-Athlete Development',
        'title' => 'Train with Arizona’s top coaches.',
        'body' => '',
        'buttons' => $ctaButtons,
    ]),
]);

/* ===================================================================== */
/* ACADEMICS                                                             */
/* ===================================================================== */

$academicsPageId = esa_page('academics', 'Academics');
esa_save($academicsPageId, [
    esa_block('acf/page-hero', 'group_page_hero', [
        'eyebrow' => 'Elite Sports Academy',
        'title' => 'Academics Built for Success',
        'body' => 'At Elite Sports Academy (Grades 6–12), students experience a dynamic environment where in-person academics are seamlessly integrated with elite athletic training. With small class sizes and a focus on character & leadership development, each student is known, challenged, and supported to grow both intellectually and personally. Our efficient schedule is intentionally designed to maximize performance during the school day while preserving valuable time for family, recovery, and life beyond the classroom —developing well-rounded student-athletes prepared to excel in every arena.',
        'stats' => [
            ['value' => '6-12', 'label' => 'Grades'],
            ['value' => 'Heritage', 'label' => 'Academy curriculum'],
            ['value' => 'Dual Enrollment', 'label' => 'College credit'],
        ],
        'padding_top' => 1,
        'padding_bottom' => 1,
    ]),
    esa_block('acf/highlight-banner', 'group_highlight_banner', [
        'label' => 'The Elite Pathway',
        'body' => 'A groundbreaking 6th–12th grade private school combining (in-person) high academics, elite athletics, and character development to prepare students for success in every arena of life.',
    ]),
    esa_block('acf/info-columns', 'group_info_columns', [
        'layout' => 'stack',
        'columns' => 2,
        'card_style' => 'surface',
        'title_style' => 'kicker',
        'cards' => [
            [
                'title' => 'Academics That Achieve',
                'body' => '<p>Our scholars master their academics with focus and precision. Elite follows the same Heritage Academy curriculum trusted by Arizona families for decades.</p>',
                'bullets' => [
                    ['text' => 'Core academics in math, science, history, and English'],
                    ['text' => 'Dual enrollment opportunities for early college credit'],
                    ['text' => 'A focused approach to excellence without unnecessary distractions'],
                    ['text' => 'Academics and athletic classes align with values-based instruction'],
                ],
            ],
            [
                'title' => 'Citizenship Built In To Our Academics',
                'body' => '<p>While teaching the academic disciplines, Heritage Academy is dedicated to instilling into the minds and hearts of today’s youth a knowledge of and respect for the ideals and values of the great men and women of history, including those who founded the American nation.</p>',
                'bullets' => [],
            ],
        ],
    ]),
    esa_block('acf/info-columns', 'group_info_columns', [
        'eyebrow' => 'Academic Tracks',
        'title' => 'Junior high subjects and high school program.',
        'layout' => 'stack',
        'columns' => 2,
        'card_style' => 'outline',
        'title_style' => 'display',
        'cards' => [
            [
                'title' => 'Junior High Subjects',
                'body' => '',
                'bullets' => [
                    ['text' => 'English (Literature, Writing, Grammar)'],
                    ['text' => 'Math, PreAlgebra'],
                    ['text' => 'Science'],
                    ['text' => 'History'],
                    ['text' => 'Computers/Keyboarding'],
                ],
            ],
            [
                'title' => 'High School Program',
                'body' => '',
                'bullets' => [
                    ['text' => 'Dual Enrollment (College Credit)'],
                    ['text' => 'Advanced Math & Science'],
                    ['text' => 'Algebra → Geometry → Algebra II → Advanced Math'],
                    ['text' => 'Biology → Chemistry → Advanced Sciences'],
                    ['text' => 'Literature-rich English progression'],
                    ['text' => 'U.S. History, Government, Economics'],
                ],
            ],
        ],
    ]),
    esa_block('acf/course-table', 'group_course_table', [
        'eyebrow' => 'Course Sequence',
        'title' => 'Recommended High School Course Sequence',
        'columns' => [
            ['heading' => '9th Grade', 'items' => [
                ['text' => '9th Grade Composition'], ['text' => 'Algebra I'], ['text' => 'Integrated Science'], ['text' => 'Foreign Language'],
            ]],
            ['heading' => '10th Grade', 'items' => [
                ['text' => 'American Literature'], ['text' => 'Geometry'], ['text' => 'World History'], ['text' => 'Foreign Language'],
            ]],
            ['heading' => '11th Grade', 'items' => [
                ['text' => '11/12th English A or DE English'], ['text' => 'Algebra II'], ['text' => 'Biology'], ['text' => 'American History'],
            ]],
            ['heading' => '12th Grade', 'items' => [
                ['text' => '11/12th English B or DE English'], ['text' => 'Statistic, Pre-Calculus, or College Math'], ['text' => 'Chemistry'], ['text' => 'Government & Economics'],
            ]],
        ],
    ]),
    esa_block('acf/reading-list', 'group_reading_list', [
        'eyebrow' => 'Classical Reading',
        'title' => 'High School Required Classical Reading',
        'body' => '<p>Many of these selections can be read during the summer, in preparation for school. We encourage parents to involve their children in summer reading of some of these books to increase their reading skills, to better prepare for school studies, and to lighten the load during the school year.</p>',
        'rows' => [
            ['label' => '9th Grade', 'items' => [
                ['text' => 'English 9, Hon English 9: Independent Reading Novel, short stories, a dystopian novel (to be decided), To Kill a Mockingbird, and Romeo and Juliet.'],
                ['text' => 'HS English Skills Yr 1: Steelheart, Beyond the Bright Sea, A Raisin in the Sun, & 5 People You Meet in Heaven'],
            ]],
            ['label' => '10th Grade', 'items' => [
                ['text' => 'English 10, Hon. Eng. 10: The Crucible by Arthur Miller, Julius Caesar by William Shakespeare; Adventures of Huckleberry Finn by Mark Twain; The Great Gatsby by F. Scott Fitzgerald'],
                ['text' => 'HS English Skills Yr 2: Shane, Freak the Mighty, Number the Stars, & Roll of Thunder, Hear My Cry'],
            ]],
            ['label' => '11th Grade', 'items' => [
                ['text' => 'ENG 101: Hiroshima, Jane Eyre; ENG 102: Non-Fiction Independent Read'],
                ['text' => 'Eng 11/12 yr 1: Dr. Jekyll and Mr. Hyde, Shakespeare’s Hamlet, Pygmalion, Book Clubs'],
            ]],
            ['label' => '12th Grade', 'items' => [
                ['text' => 'ENG 110: Many short pieces of literature, Red Badge of Courage; ENG 111: Technical Communication'],
                ['text' => 'English 11/12 yr 2: The Tempest, Les Miserables, The Importance of Being Earnest, Screwtape Letters, The Time Machine'],
            ]],
        ],
    ]),
    esa_block('acf/cta-banner', 'group_cta_banner', [
        'eyebrow' => 'Next Steps',
        'title' => 'Start your path at Elite Sports Academy.',
        'body' => '',
        'buttons' => $ctaButtons,
    ]),
]);

/* ===================================================================== */
/* TUITION                                                               */
/* ===================================================================== */

$tuitionPageId = esa_page('tuition', 'Tuition');
esa_save($tuitionPageId, [
    esa_block('acf/page-hero', 'group_page_hero', [
        'eyebrow' => 'Elite Sports Academy',
        'title' => 'Tuition & Financial Information',
        'body' => 'Tuition, registration, ESA scholarship, and STO information for families considering Elite Sports Academy.',
        'image' => esa_attachment_id('financial6'),
        'stats' => [
            ['value' => '$15,500', 'label' => '2026-27 tuition'],
            ['value' => '$1,500', 'label' => 'Deposit. (credited to tuition)'],
            ['value' => '$80', 'label' => 'Enrollment fee'],
            ['value' => '2026-2027', 'label' => 'School year'],
        ],
        'padding_top' => 1,
        'padding_bottom' => 1,
    ]),
    esa_block('acf/info-columns', 'group_info_columns', [
        'eyebrow' => 'Welcome',
        'title' => 'Access matters.',
        'body' => '<p>From its founding, Elite Sports Academy was built on the belief that exceptional training and education should be accessible to talented, motivated students, not limited only to those who can afford it. Elite exists to develop student-athletes who excel in sport, character, discipline, academics, and leadership.</p>',
        'intro_extra' => 'A rigorous academic program paired with elite athletic training can transform lives, open opportunities, and prepare young people to lead with integrity both on and off the field.',
        'layout' => 'split',
        'columns' => 1,
        'card_style' => 'surface',
        'title_style' => 'label',
        'cards' => [
            ['title' => 'Tuition', 'body' => '<p>Elite Sports Academy board has set tuition for the 2026-27 school year at $15,500.</p>', 'bullets' => []],
            ['title' => 'Registration', 'body' => '<p>To register for 2026-27, families submit a new-student fee of $80 and a nonrefundable deposit of $1,500 down. The deposit is credited toward the tuition balance.</p>', 'bullets' => []],
            ['title' => 'Scholarship Timing', 'body' => '<p>Families needing to utilize scholarship options must complete registration before continuing ESA and STO applications.</p>', 'bullets' => []],
        ],
    ]),
    esa_block('acf/info-columns', 'group_info_columns', [
        'eyebrow' => 'Affording Elite Sports Academy',
        'title' => 'Affordability and Support',
        'body' => '<p>Cost should not stand between a qualified student-athlete and the opportunity to train, grow, and compete at Elite. We admit students based on merit, including academic commitment, athletic ability, and personal character. After acceptance, we work closely with families through a clear and supportive process designed to help make Elite more accessible.</p>',
        'layout' => 'split',
        'columns' => 1,
        'card_style' => 'surface',
        'title_style' => 'label',
        'cards' => [
            ['title' => 'Admissions', 'body' => '<p>Admission decisions are need blind and are based on academic commitment, athletic ability, character, and fit with the Elite model.</p>', 'bullets' => []],
            ['title' => 'Scholarships', 'body' => '<p>Families may pursue additional support through outside programs, empowerment scholarships, or STOs.</p>', 'bullets' => []],
        ],
    ], ['anchor' => 'financial-assistance']),
    esa_block('acf/esa-funding', 'group_esa_funding', [
        'eyebrow' => 'Arizona Empowerment Scholarship Account',
        'title' => 'Arizona ESA funding may help cover private school tuition.',
        'body' => '<p>Elite Sports Academy is an approved private school option for families using Arizona’s Empowerment Scholarship Account program. The program allows eligible Arizona students to use state education funding toward approved learning expenses outside the public school system.</p>',
        'intro_extra' => 'For many families, that can include private school tuition and related qualified expenses. Funding amounts, allowable purchases, and payment timing are managed by the Arizona Department of Education, so families should confirm current requirements directly through ADE.',
        'buttons' => [
            ['label' => 'Visit Arizona ESA', 'url' => 'https://www.azed.gov/esa', 'new_tab' => 1, 'variant' => 'secondary'],
        ],
        'snapshot_label' => 'Funding snapshot',
        'snapshot_value' => '90%',
        'snapshot_caption' => 'State-calculated funding formula',
        'snapshot_body' => 'Arizona ESA awards are generally based on 90% of the state support amount calculated for the student. Funds are distributed through ClassWallet on a quarterly schedule.',
        'snapshot_rows' => [
            ['text' => 'Elite Sports Academy is approved for ESA funds'],
            ['text' => 'Funds are released quarterly'],
            ['text' => 'ADE manages eligibility and approvals'],
        ],
        'disclaimer' => 'Eligibility, funding amount, allowable expenses, and payment timing are determined by the Arizona Department of Education and may change.',
    ]),
    esa_block('acf/info-columns', 'group_info_columns', [
        'eyebrow' => 'STO Options',
        'title' => 'Additional scholarship support may be available.',
        'body' => '<p>Arizona School Tuition Organization scholarships can help eligible families reduce the cost of private school tuition. Families apply directly to each STO, and each organization sets its own requirements, deadlines, and award decisions.</p>',
        'layout' => 'stack',
        'columns' => 3,
        'card_style' => 'outline',
        'title_style' => 'label',
        'buttons' => [
            ['label' => 'Visit STO4KIDZ', 'url' => 'https://sto4kidz.org/', 'new_tab' => 1, 'variant' => 'secondary'],
        ],
        'cards' => [
            ['title' => 'Apply to Multiple STOs', 'body' => '<p>Families may apply to more than one STO, and scholarships may be combined up to the value of annual tuition.</p>', 'bullets' => []],
            ['title' => 'Review Each STO’s Requirements', 'body' => '<p>Deadlines, applications, eligibility rules, and award amounts vary by organization.</p>', 'bullets' => []],
            ['title' => 'Awards Are Applied to Tuition', 'body' => '<p>Scholarship funds awarded in a student’s name are applied directly to that student’s Elite tuition account.</p>', 'bullets' => []],
        ],
    ]),
    esa_block('acf/cta-banner', 'group_cta_banner', [
        'eyebrow' => 'Next Steps',
        'title' => 'Registration is the first Step',
        'body' => '<p>We are now accepting enrollment applications for the 2026-27 school year. For ESA scholarship help, contact Admissions at 480-461-4487.</p><p>Divorced or separated parents must submit a copy of their court-approved divorce settlement or separation agreement to the Registrar during registration. Both parents are still required to provide the necessary information to complete the tuition forms.</p>',
        'buttons' => $ctaButtons,
    ]),
]);

/* ===================================================================== */
/* PRIVACY POLICY + TERMS OF SERVICE                                     */
/* ===================================================================== */

$docsDir = dirname(__DIR__) . '/elite-website-source-handoff-2026-06-11/docs';

$privacyPageId = esa_page('privacy-policy', 'Privacy Policy');
esa_save($privacyPageId, [
    esa_block('acf/prose-page', 'group_prose_page', [
        'eyebrow' => 'Elite Sports Academy',
        'title' => 'Privacy Policy',
        'body' => esa_legal_html("{$docsDir}/Privacy_Policy.md", 'Privacy Policy'),
        'padding_top' => 1,
        'padding_bottom' => 1,
    ]),
]);

$termsPageId = esa_page('terms-of-service', 'Terms of Service');
esa_save($termsPageId, [
    esa_block('acf/prose-page', 'group_prose_page', [
        'eyebrow' => 'Elite Sports Academy',
        'title' => 'Terms of Service',
        'body' => esa_legal_html("{$docsDir}/Terms_of_Service.md", 'Terms of Service'),
        'padding_top' => 1,
        'padding_bottom' => 1,
    ]),
]);

WP_CLI::success(sprintf(
    'Seeded pages: coaches #%d, academics #%d, tuition #%d, privacy #%d, terms #%d.',
    $coachesPage, $academicsPageId, $tuitionPageId, $privacyPageId, $termsPageId
));
