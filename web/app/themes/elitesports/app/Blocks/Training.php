<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Training extends BaseBlock
{
    public $name = 'Training';

    public $description = 'Elite training section: intro, image, training pillars and the sports offered groups.';

    public $category = 'formatting';

    public $icon = 'awards';

    public $keywords = ['training', 'sports', 'pillars', 'practice'];

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
            'pillars' => get_field('pillars') ?: [],
            'sports' => get_field('sports') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Elite Training'])
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
            ->addRepeater('pillars', [
                'label' => 'Training Pillars',
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
                ->addImage('icon_image', [
                    'label' => 'Icon Image',
                    'instructions' => 'Optional. Upload a custom icon to replace the built-in pillar icon.',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                ])
            ->endRepeater();

        $fields
            ->addGroup('sports', ['label' => 'Sports Offered', 'layout' => 'block'])
                ->addText('label', ['label' => 'Label', 'default_value' => 'Sports Offered'])
                ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('title_uppercase', [
                    'label' => 'Title — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addImage('image', [
                    'label' => 'Team Photo',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ])
                ->addText('intro', ['label' => 'Intro', 'default_value' => 'Elite offers programs in:'])
                ->addRepeater('groups', [
                    'label' => 'Groups',
                    'button_label' => 'Add Group',
                    'layout' => 'block',
                    'min' => 0,
                ])
                    ->addText('label', ['label' => 'Group Label'])
                    ->addRepeater('items', [
                        'label' => 'Sports',
                        'button_label' => 'Add Sport',
                        'layout' => 'table',
                        'min' => 0,
                    ])
                        ->addText('name', ['label' => 'Sport'])
                    ->endRepeater()
                ->endRepeater()
            ->endGroup();
    }
}
