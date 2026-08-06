<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Form extends BaseBlock
{
    public $name = 'Form';

    public $slug = 'form';

    public $description = 'Insert a Gravity Forms form styled to the site design, with an optional heading and intro copy.';

    public $category = 'formatting';

    public $icon = 'feedback';

    public $keywords = ['form', 'contact', 'enquiry', 'gravity forms'];

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
                'instructions' => 'Choose which Gravity Form to display.',
                'choices' => $this->formChoices(),
                'allow_null' => 0,
                'ui' => 1,
                'return_format' => 'value',
            ]);
    }

    /**
     * Build the list of available Gravity Forms for the select field.
     *
     * Choices use a string key prefix (`gf_`) because ACF can normalize integer
     * keys in select choices.
     */
    protected function formChoices(): array
    {
        if (! class_exists('GFAPI')) {
            return [];
        }

        $forms = \GFAPI::get_forms(true, false, 'title', 'ASC');

        $choices = [];

        foreach ($forms as $form) {
            $id = (int) ($form['id'] ?? 0);

            if ($id < 1) {
                continue;
            }

            $title = trim((string) ($form['title'] ?? ''));
            $choices['gf_' . $id] = $title !== '' ? $title : "Form #{$id}";
        }

        return $choices;
    }

    /**
     * Render the selected form via the Gravity Forms shortcode.
     *
     * Resolves the stored value flexibly — `gf_{id}`, numeric ID, or legacy title.
     */
    protected function renderForm($value): string
    {
        if (! $value || ! class_exists('GFAPI') || ! function_exists('do_shortcode')) {
            return '';
        }

        $formId = 0;

        if (is_numeric($value)) {
            $formId = (int) $value;
        }

        if (! $formId && is_string($value) && preg_match('/^gf_(\d+)$/', $value, $matches)) {
            $formId = (int) $matches[1];
        }

        if (! $formId && is_string($value)) {
            $forms = \GFAPI::get_forms(true, false, 'title', 'ASC');

            foreach ($forms as $form) {
                if (strcasecmp((string) ($form['title'] ?? ''), $value) === 0) {
                    $formId = (int) ($form['id'] ?? 0);
                    break;
                }
            }
        }

        if ($formId < 1) {
            return '';
        }

        $form = \GFAPI::get_form($formId);

        if (! is_array($form) || empty($form['id'])) {
            return '';
        }

        return do_shortcode('[gravityform id="' . esc_attr((string) $form['id']) . '" title="false" description="false" ajax="true"]');
    }
}
