<?php

/**
 * Seed a styled Gravity Form that mirrors the ESA design.
 *
 * Idempotent: updates the existing "Contact" form if one already exists,
 * otherwise creates it. Run from the Bedrock root:
 *
 *   wp eval-file scripts/seed-contact-form.php
 */

if (! class_exists('GFAPI')) {
    fwrite(STDERR, "Gravity Forms is not active.\n");

    return;
}

$title = 'Contact';

$forms = \GFAPI::get_forms(true, false, 'title', 'ASC');

$existingId = 0;

foreach ($forms as $form) {
    if (strcasecmp((string) ($form['title'] ?? ''), $title) === 0) {
        $existingId = (int) ($form['id'] ?? 0);
        break;
    }
}

$formMeta = [
    'title' => $title,
    'fields' => [
        [
            'id' => 1,
            'label' => 'Name',
            'type' => 'name',
            'isRequired' => true,
            'inputs' => [
                ['id' => '1.3', 'label' => 'First'],
                ['id' => '1.6', 'label' => 'Last'],
            ],
        ],
        [
            'id' => 2,
            'label' => 'Email',
            'type' => 'email',
            'isRequired' => true,
        ],
        [
            'id' => 3,
            'label' => 'Phone',
            'type' => 'phone',
            'isRequired' => false,
        ],
        [
            'id' => 4,
            'label' => 'Message',
            'type' => 'textarea',
            'isRequired' => true,
        ],
    ],
    'button' => [
        'type' => 'text',
        'text' => 'Send message',
    ],
    'description' => '',
];

if ($existingId > 0) {
    $existing = \GFAPI::get_form($existingId);

    if (! is_array($existing)) {
        fwrite(STDERR, "Unable to load existing Contact form (ID {$existingId}).\n");
        return;
    }

    // Keep confirmation and notification configuration if already customized.
    if (! empty($existing['confirmations'])) {
        $formMeta['confirmations'] = $existing['confirmations'];
    }

    if (! empty($existing['notifications'])) {
        $formMeta['notifications'] = $existing['notifications'];
    }

    $result = \GFAPI::update_form($formMeta, $existingId);

    if (is_wp_error($result)) {
        fwrite(STDERR, "Failed updating Contact form: " . $result->get_error_message() . "\n");
        return;
    }

    $id = $existingId;
    echo "Updated existing Contact form (ID {$id}).\n";
} else {
    $result = \GFAPI::add_form($formMeta);

    if (is_wp_error($result)) {
        fwrite(STDERR, "Failed creating Contact form: " . $result->get_error_message() . "\n");
        return;
    }

    $id = (int) $result;
    echo "Created Contact form (ID {$id}).\n";
}

echo "Shortcode: [gravityform id=\"{$id}\" title=\"false\" description=\"false\" ajax=\"true\"]\n";
