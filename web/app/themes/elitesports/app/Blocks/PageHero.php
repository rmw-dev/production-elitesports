<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class PageHero extends BaseBlock
{
    public $name = 'Page Hero';

    public $slug = 'page-hero';

    public $description = 'Interior page hero: kicker, headline, intro and optional stat cards over an optional background image.';

    public $category = 'formatting';

    public $icon = 'align-wide';

    public $keywords = ['hero', 'page', 'header', 'stats'];

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
            'image' => get_field('image') ?: [],
            'stats' => get_field('stats') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Elite Sports Academy'])
            ->addText('title', ['label' => 'Title'])
            ->addTextarea('body', [
                'label' => 'Intro',
                'rows' => 3,
                'new_lines' => '',
            ])
            ->addImage('image', [
                'label' => 'Background Image',
                'instructions' => 'Optional. Sits behind the hero content.',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addRepeater('stats', [
                'label' => 'Stat Cards',
                'button_label' => 'Add Stat',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('value', ['label' => 'Value'])
                ->addText('label', ['label' => 'Label'])
            ->endRepeater();
    }
}
