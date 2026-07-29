<?php
/**
 * TEMP: seed a Faculty test page. Run: wp eval-file scripts/tmp-faculty-test.php
 */

$img = 90; // existing image attachment for testing

$members = [
    ['category' => 'Faculty', 'name' => 'Samantha Ezell', 'title' => 'Executive Director', 'bio' => '<p>Samantha Ezell joins Elite Sports Academy in its flagship year as Executive Director.</p><p>Ezell has over 25 years of experience in collegiate athletics, including coaching women\'s basketball and serving as a college athletic director. Most recently, she worked for NCSA/IMG Academy as a senior recruiting specialist, helping families find the best academic and athletic fit while providing student-athletes exposure to college coaches for recruiting purposes. In her five years at NCSA, Ezell was awarded Rookie of the Year and the Core Values Award twice.</p>'],
    ['category' => 'Faculty', 'name' => 'Stephanie Lund', 'title' => 'Director of Academics', 'bio' => '<p>Stephanie Lund leads academics at Elite Sports Academy.</p>'],
    ['category' => 'Faculty', 'name' => 'Debbie Kunes', 'title' => 'English Instructor', 'bio' => '<p>Debbie Kunes is an accomplished educator with more than three decades of experience dedicated to empowering students through language, mindfulness, cognitive development, service learning, and personal growth. She is a bilingual educator who has built a career centered on fostering meaningful learning experiences that help students thrive academically and personally.</p><p>In addition to her classroom expertise, Debbie has coached athletics for over 20 years, using sports as a platform to develop leadership, resilience, teamwork, and character. She is a collaborative professional who has presented at national conferences.</p>'],
];

$fk = 'field_faculty_faculty';
$sub = [
    'category' => 'field_faculty_faculty_category',
    'name' => 'field_faculty_faculty_name',
    'name_uppercase' => 'field_faculty_faculty_name_uppercase',
    'title' => 'field_faculty_faculty_title',
    'photo' => 'field_faculty_faculty_photo',
    'object_position' => 'field_faculty_faculty_object_position',
    'bio' => 'field_faculty_faculty_bio',
];

$data = [
    'eyebrow' => 'Elite Sports Academy',
    '_eyebrow' => 'field_faculty_eyebrow',
    'title' => 'Meet Our Faculty',
    '_title' => 'field_faculty_title',
    'title_uppercase' => 1,
    '_title_uppercase' => 'field_faculty_title_uppercase',
    'body' => 'Educators and campus leaders supporting the academic, personal, and athletic growth of Elite Sports Academy students.',
    '_body' => 'field_faculty_body',
    'faculty' => count($members),
    '_faculty' => $fk,
];

foreach ($members as $i => $m) {
    foreach ($sub as $name => $key) {
        $val = $m[$name] ?? '';
        if ($name === 'photo') {
            $val = $img;
        }
        if ($name === 'object_position') {
            $val = '50% 30%';
        }
        if ($name === 'name_uppercase') {
            $val = 1;
        }
        $data["faculty_{$i}_{$name}"] = $val;
        $data["_faculty_{$i}_{$name}"] = $key;
    }
}

$json = wp_json_encode([
    'name' => 'acf/faculty',
    'data' => $data,
    'mode' => 'preview',
]);

$content = '<!-- wp:acf/faculty ' . $json . ' /-->';
$content = wp_slash($content);

$existing = get_page_by_path('faculty-test');
$args = [
    'post_title' => 'Faculty Test',
    'post_name' => 'faculty-test',
    'post_status' => 'publish',
    'post_type' => 'page',
    'post_content' => $content,
];
if ($existing) {
    $args['ID'] = $existing->ID;
    $id = wp_update_post($args);
} else {
    $id = wp_insert_post($args);
}

echo "Page ID {$id} -> " . get_permalink($id) . "\n";
