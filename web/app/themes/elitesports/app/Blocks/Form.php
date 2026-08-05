<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Form extends BaseBlock
{
    public $name = 'Form';

    public $slug = 'form';

    public $description = 'Insert a Contact Form 7 form styled to the site design, with an optional heading and intro copy.';

    public $category = 'formatting';

    public $icon = 'feedback';

    public $keywords = ['form', 'contact', 'enquiry', 'cf7'];

    public $post_types = ['page'];

    public $mode = 'preview';

    public $supports = [
        'align' => ['full'],
        'mode' => true,
        'multiple' => true,
        'jsx' => false,
    ];

    public $align = 'full';

    public function blockWith(): array
    {
        $formId = get_field('form_id');

        return [
            'eyebrow' => get_field('eyebrow'),
            'title' => get_field('title'),
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'body' => get_field('body'),
            'formHtml' => $this->renderForm($formId),
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Get in touch'])
            ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
            ->addTrueFalse('title_uppercase', [
                'label' => 'Title — uppercase',
                'instructions' => 'Display this heading in uppercase.',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addSelect('form_id', [
                'label' => 'Form',
                'instructions' => 'Choose which Contact Form 7 form to display.',
                'choices' => $this->formChoices(),
                'allow_null' => 0,
                'ui' => 1,
                'return_format' => 'value',
            ]);
    }

    /**
     * Build the list of available Contact Form 7 forms for the select field.
     *
     * Choices are keyed by the CF7 hash (a hex string) rather than the numeric
     * post ID: ACF's acf_encode_choices() drops integer array keys and would
     * otherwise store the label as the value. The hash also renders reliably
     * via the [contact-form-7] shortcode.
     */
    protected function formChoices(): array
    {
        $forms = get_posts([
            'post_type' => 'wpcf7_contact_form',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        $choices = [];

        foreach ($forms as $form) {
            $hash = get_post_meta($form->ID, '_hash', true) ?: (string) $form->ID;
            $choices[$hash] = $form->post_title ?: "Form #{$form->ID}";
        }

        return $choices;
    }

    /**
     * Render the selected form via the Contact Form 7 shortcode.
     *
     * Resolves the stored value flexibly — CF7 hash, numeric ID, or (for legacy
     * blocks saved before the hash change) the form title.
     */
    protected function renderForm($value): string
    {
        if (! $value || ! class_exists('WPCF7_ContactForm') || ! function_exists('do_shortcode')) {
            return '';
        }

        $form = null;

        if (function_exists('wpcf7_get_contact_form_by_hash')) {
            $form = wpcf7_get_contact_form_by_hash($value);
        }

        if (! $form && is_numeric($value)) {
            $form = wpcf7_contact_form((int) $value);
        }

        if (! $form && function_exists('wpcf7_get_contact_form_by_title')) {
            $form = wpcf7_get_contact_form_by_title($value);
        }

        if (! $form) {
            return '';
        }

        return do_shortcode('[contact-form-7 id="' . esc_attr($form->hash()) . '"]');
    }
}
