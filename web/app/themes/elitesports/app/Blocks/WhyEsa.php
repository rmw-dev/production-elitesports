<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class WhyEsa extends BaseBlock
{
    public $name = 'Why ESA (Model)';

    public $slug = 'why-esa';

    public $description = 'The half-day model section: headline, body, colored payoff lines, image and four pillars.';

    public $category = 'formatting';

    public $icon = 'screenoptions';

    public $keywords = ['why', 'model', 'pillars', 'payoff'];

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
            'body' => get_field('body'),
            'payoff' => get_field('payoff') ?: [],
            'image' => get_field('image') ?: [],
            'pillars' => get_field('pillars') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Why Elite?'])
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
            ->addRepeater('payoff', [
                'label' => 'Payoff Lines',
                'instructions' => 'Short punchy lines shown in brand colors.',
                'button_label' => 'Add Line',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('text', ['label' => 'Text'])
                ->addSelect('color', [
                    'label' => 'Color',
                    'choices' => ['orange' => 'Orange', 'purple' => 'Purple', 'white' => 'White'],
                    'default_value' => 'orange',
                    'allow_null' => 0,
                ])
            ->endRepeater()
            ->addImage('image', [
                'label' => 'Image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addRepeater('pillars', [
                'label' => 'Pillars',
                'button_label' => 'Add Pillar',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('title_uppercase', [
                    'label' => 'Title — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addWysiwyg('copy', [
                    'label' => 'Copy',
                    'tabs' => 'all',
                    'media_upload' => 0,
                    'delay' => 1,
                ])
            ->endRepeater();
    }
}
