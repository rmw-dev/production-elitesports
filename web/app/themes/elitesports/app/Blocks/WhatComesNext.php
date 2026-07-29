<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class WhatComesNext extends BaseBlock
{
    public $name = 'What Comes Next';

    public $description = 'Future-focused section: heading, body, image and a grid of icon cards.';

    public $category = 'formatting';

    public $icon = 'arrow-right-alt';

    public $keywords = ['future', 'next', 'college', 'pathway'];

    public $post_types = ['page'];

    public $mode = 'preview';

    public $supports = [
        'align' => ['full'],
        'mode' => true,
        'multiple' => true,
        'jsx' => false,
    ];

    public $align = 'full';

    public const ICONS = [
        'college' => 'College',
        'trophy' => 'Trophy',
        'sunrise' => 'Sunrise',
        'shield' => 'Shield',
        'community' => 'Community',
    ];

    public function blockWith(): array
    {
        return [
            'eyebrow' => get_field('eyebrow'),
            'title' => get_field('title'),
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'body' => get_field('body'),
            'image' => get_field('image') ?: [],
            'items' => get_field('items') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'What Comes Next'])
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
            ->addImage('image', [
                'label' => 'Image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addRepeater('items', [
                'label' => 'Items',
                'button_label' => 'Add Item',
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
                ->addSelect('icon', [
                    'label' => 'Icon',
                    'choices' => self::ICONS,
                    'default_value' => 'college',
                    'allow_null' => 0,
                ])
                ->addImage('icon_image', [
                    'label' => 'Icon Image',
                    'instructions' => 'Optional. Upload a custom icon to replace the built-in icon above.',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                ])
            ->endRepeater();
    }
}
