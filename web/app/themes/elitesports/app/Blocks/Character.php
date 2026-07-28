<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Character extends BaseBlock
{
    public $name = 'Character & Leadership';

    public $slug = 'character';

    public $description = 'Character section: heading, body, texture image and a framework list with icons.';

    public $category = 'formatting';

    public $icon = 'shield';

    public $keywords = ['character', 'leadership', 'framework', 'values'];

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
        'shield' => 'Shield (Liberty)',
        'scales' => 'Scales (Integrity)',
        'community' => 'Community (Leadership)',
    ];

    public function blockWith(): array
    {
        return [
            'eyebrow' => get_field('eyebrow'),
            'title' => get_field('title'),
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'body' => get_field('body'),
            'intro' => get_field('intro'),
            'image' => get_field('image') ?: [],
            'framework' => get_field('framework') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Character & Leadership'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addText('intro', ['label' => 'Framework Intro'])
            ->addImage('image', [
                'label' => 'Texture Image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addRepeater('framework', [
                'label' => 'Framework',
                'button_label' => 'Add Item',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addText('title', ['label' => 'Title'])
                ->addWysiwyg('copy', [
                    'label' => 'Copy',
                    'tabs' => 'all',
                    'media_upload' => 0,
                    'delay' => 1,
                ])
                ->addSelect('icon', [
                    'label' => 'Icon',
                    'choices' => self::ICONS,
                    'default_value' => 'shield',
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
