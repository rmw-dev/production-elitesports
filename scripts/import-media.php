<?php
/**
 * Import all source media (homepage + coaches) into the WP media library.
 * Idempotent: skips files already present (matched by sanitized filename).
 *
 * Run from Bedrock root:
 *   wp eval-file scripts/import-media.php
 */

if (! function_exists('media_handle_sideload')) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
}

$sourceDir = dirname(__DIR__) . '/elite-website-source-handoff-2026-06-11/public/media';

/**
 * Find an existing attachment by (sanitized) filename.
 */
function esa_find_attachment(string $filename): ?int
{
    global $wpdb;

    $name = sanitize_file_name($filename);

    $id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta}
             WHERE meta_key = '_wp_attached_file'
             AND meta_value LIKE %s
             LIMIT 1",
            '%' . $wpdb->esc_like($name)
        )
    );

    return $id ? (int) $id : null;
}

/**
 * Import a single file, returning its attachment ID (existing or new).
 */
function esa_import_file(string $path): ?int
{
    if (! is_file($path)) {
        WP_CLI::warning("Missing: {$path}");
        return null;
    }

    $filename = basename($path);

    if ($existing = esa_find_attachment($filename)) {
        return $existing;
    }

    $tmp = wp_tempnam($filename);
    copy($path, $tmp);

    $file = [
        'name' => $filename,
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file, 0);

    if (is_wp_error($id)) {
        @unlink($tmp);
        WP_CLI::warning("Failed {$filename}: " . $id->get_error_message());
        return null;
    }

    return (int) $id;
}

$files = array_merge(
    glob("{$sourceDir}/*.jpg") ?: [],
    glob("{$sourceDir}/*.png") ?: [],
    glob("{$sourceDir}/*.mp4") ?: [],
    glob("{$sourceDir}/coaches/*.jpg") ?: [],
    glob(dirname(__DIR__) . '/web/app/themes/elitesports/resources/images/icons/*.png') ?: [],
);

$imported = 0;
$reused = 0;

foreach ($files as $path) {
    $existed = esa_find_attachment(basename($path)) !== null;
    $id = esa_import_file($path);

    if ($id === null) {
        continue;
    }

    $existed ? $reused++ : $imported++;
}

WP_CLI::success("Media import complete. New: {$imported}, reused: {$reused}, total processed: " . count($files));
