<?php

/**
 * Seed a styled Contact Form 7 form that mirrors the ESA design.
 *
 * Idempotent: updates the existing "Contact" form if one already exists,
 * otherwise creates it. Run from the Bedrock root:
 *
 *   wp eval-file scripts/seed-contact-form.php
 */

if (! class_exists('WPCF7_ContactForm')) {
    fwrite(STDERR, "Contact Form 7 is not active.\n");

    return;
}

$title = 'Contact';

// The form template. Custom `class:` tokens let the theme style every field
// (see resources/css/blocks/form.css). Wrapper markup uses our design classes.
$form = <<<'FORM'
<div class="cf7-grid">
  <p class="cf7-field">
    <label class="cf7-label">Name<span class="cf7-req">*</span></label>
    [text* your-name class:cf7-input autocomplete:name placeholder "Your name"]
  </p>
  <p class="cf7-field">
    <label class="cf7-label">Email<span class="cf7-req">*</span></label>
    [email* your-email class:cf7-input autocomplete:email placeholder "you@example.com"]
  </p>
  <p class="cf7-field cf7-field--full">
    <label class="cf7-label">Phone</label>
    [tel your-phone class:cf7-input autocomplete:tel placeholder "(480) 555-0100"]
  </p>
  <p class="cf7-field cf7-field--full">
    <label class="cf7-label">Message<span class="cf7-req">*</span></label>
    [textarea* your-message class:cf7-textarea rows:6 placeholder "How can we help?"]
  </p>
</div>
<p class="cf7-actions">[submit class:cf7-submit "Send message"]</p>
FORM;

$mail_body = <<<'BODY'
A new enquiry has been submitted on Elite Sports Academy.

Name:    [your-name]
Email:   [your-email]
Phone:   [your-phone]

Message:
[your-message]

--
Sent from [_site_title] ([_site_url])
BODY;

$admin_email = get_option('admin_email');
$blogname = get_option('blogname');

$mail = [
    'active' => true,
    'subject' => "[$blogname] New contact enquiry from [your-name]",
    'sender' => "[$blogname] <wordpress@" . preg_replace('/^www\./', '', parse_url(home_url(), PHP_URL_HOST)) . '>',
    'recipient' => $admin_email,
    'body' => $mail_body,
    'additional_headers' => 'Reply-To: [your-email]',
    'attachments' => '',
    'use_html' => false,
    'exclude_blank' => false,
];

$properties = [
    'form' => $form,
    'mail' => $mail,
];

// Find an existing form with this title to stay idempotent.
$existing = get_posts([
    'post_type' => 'wpcf7_contact_form',
    'title' => $title,
    'posts_per_page' => 1,
    'post_status' => 'any',
]);

if (! empty($existing)) {
    $contact_form = WPCF7_ContactForm::get_instance($existing[0]->ID);
    $contact_form->set_properties($properties);
    $contact_form->save();
    $id = $contact_form->id();
    echo "Updated existing Contact form (ID {$id}).\n";
} else {
    $contact_form = WPCF7_ContactForm::get_template(['title' => $title]);
    $contact_form->set_properties($properties);
    $id = $contact_form->save();
    echo "Created Contact form (ID {$id}).\n";
}

$hash = get_post_meta($id, '_hash', true);
echo "Shortcode: [contact-form-7 id=\"" . ($hash ?: $id) . "\" title=\"{$title}\"]\n";
