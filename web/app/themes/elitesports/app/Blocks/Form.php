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
            $choices[$form->ID] = $form->post_title ?: "Form #{$form->ID}";
        }

        return $choices;
    }

    /**
     * Render the selected form via the Contact Form 7 shortcode.
     */
    protected function renderForm($formId): string
    {
        if (! $formId || ! function_exists('do_shortcode')) {
            return '';
        }

        return do_shortcode('[contact-form-7 id="' . (int) $formId . '"]');
    }
}
