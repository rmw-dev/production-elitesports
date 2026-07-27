<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    // Per-block stylesheets (resources/css/blocks/{slug}.css) are only wired to
    // enqueue_block_assets on the front end (acf-composer guards them behind
    // ! is_admin()), so they never reach the API v3 editor iframe. Import each
    // one here so block previews match the front end (fonts, layout, spacing).
    foreach (glob(get_theme_file_path('resources/css/blocks/*.css')) as $blockCss) {
        $entry = 'resources/css/blocks/'.basename($blockCss);

        $settings['styles'][] = [
            'css' => "@import url('".Vite::asset($entry)."')",
        ];
    }

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_action('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        $dependencies = json_decode(Vite::content('editor.deps.json'));

        foreach ($dependencies as $dependency) {
            if (! wp_script_is($dependency)) {
                wp_enqueue_script($dependency);
            }
        }
    }
    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Disable on-demand block asset loading.
 *
 * @link https://core.trac.wordpress.org/ticket/61965
 */
add_filter('should_load_separate_core_block_assets', '__return_false');

/**
 * Output the brand favicon set (matches the reference site's Favicon_1 assets)
 * and suppress WordPress's default Site Icon markup so the detailed crest logo
 * isn't used at favicon sizes.
 *
 * @return void
 */
remove_action('wp_head', 'wp_site_icon', 99);

add_action('wp_head', function () {
    $base = get_theme_file_uri('resources/images/favicon');

    echo <<<HTML
    <link rel="icon" type="image/png" sizes="32x32" href="{$base}/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="{$base}/favicon-16x16.png" />
    <link rel="shortcut icon" href="{$base}/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="{$base}/apple-touch-icon.png" />
    <link rel="manifest" href="{$base}/site.webmanifest" />

    HTML;
}, 2);


/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Register the Elite Sports Academy brand editor color palette.
     *
     * These slugs mirror the Tailwind brand tokens defined in
     * resources/css/app.css so a selected color maps directly to a
     * `bg-{slug}` / `text-{slug}` utility. Consumed by the Log1x
     * acf-editor-palette field via the reusable ColorPicker partial.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#block-color-palettes
     */
    add_theme_support('editor-color-palette', [
        ['name' => __('Ink', 'sage'), 'slug' => 'ink', 'color' => '#07070c'],
        ['name' => __('Ink Soft', 'sage'), 'slug' => 'ink-soft', 'color' => '#101019'],
        ['name' => __('Cream', 'sage'), 'slug' => 'cream', 'color' => '#f5eee6'],
        ['name' => __('Orange', 'sage'), 'slug' => 'orange', 'color' => '#f68c29'],
        ['name' => __('Orange Bright', 'sage'), 'slug' => 'orange-bright', 'color' => '#ff9a3d'],
        ['name' => __('Orange Deep', 'sage'), 'slug' => 'orange-deep', 'color' => '#e56f1f'],
        ['name' => __('Purple', 'sage'), 'slug' => 'purple', 'color' => '#7140b1'],
        ['name' => __('Purple Bright', 'sage'), 'slug' => 'purple-bright', 'color' => '#7c3aed'],
        ['name' => __('Purple Deep', 'sage'), 'slug' => 'purple-deep', 'color' => '#4f248b'],
    ]);
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});
