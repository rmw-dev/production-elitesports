<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Faq extends BaseBlock
{
    public $name = 'FAQ';

    public $slug = 'faq';

    public $description = 'Family FAQs: heading, image and an accordion of question / answer pairs.';

    public $category = 'formatting';

    public $icon = 'editor-help';

    public $keywords = ['faq', 'questions', 'accordion'];

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
        return [
            'eyebrow' => get_field('eyebrow'),
            'title' => get_field('title'),
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'image' => get_field('image') ?: [],
            'items' => get_field('items') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Family FAQs'])
            ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
            ->addTrueFalse('title_uppercase', [
                'label' => 'Title — uppercase',
                'instructions' => 'Display this heading in uppercase.',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addImage('image', [
                'label' => 'Image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addRepeater('items', [
                'label' => 'Questions',
                'button_label' => 'Add Question',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addTextarea('question', ['label' => 'Question', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('question_uppercase', [
                    'label' => 'Question — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addWysiwyg('answer', [
                    'label' => 'Answer',
                    'tabs' => 'all',
                    'media_upload' => 0,
                    'delay' => 1,
                ])
            ->endRepeater();
    }
}
