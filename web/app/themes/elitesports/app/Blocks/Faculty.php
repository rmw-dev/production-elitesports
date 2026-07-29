<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Faculty extends BaseBlock
{
    public $name = 'Faculty';

    public $slug = 'faculty';

    public $description = 'Faculty hero and roster of faculty cards with headshot, category, title and bio.';

    public $category = 'formatting';

    public $icon = 'welcome-learn-more';

    public $keywords = ['faculty', 'staff', 'educators', 'team', 'roster'];

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
            'faculty' => get_field('faculty') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Elite Sports Academy'])
            ->addTextarea('title', ['label' => 'Title', 'default_value' => 'Meet Our Faculty', 'rows' => 2, 'new_lines' => ''])
            ->addTrueFalse('title_uppercase', [
                'label' => 'Title — uppercase',
                'instructions' => 'Display this heading in uppercase.',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addTextarea('body', [
                'label' => 'Intro',
                'rows' => 3,
                'new_lines' => '',
            ])
            ->addRepeater('faculty', [
                'label' => 'Faculty',
                'button_label' => 'Add Faculty Member',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addText('category', [
                    'label' => 'Category',
                    'instructions' => 'Small tag above the name, e.g. "Faculty" or "Academic Support".',
                    'default_value' => 'Faculty',
                ])
                ->addTextarea('name', ['label' => 'Name', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('name_uppercase', [
                    'label' => 'Name — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addText('title', ['label' => 'Title', 'instructions' => 'Role, e.g. "English Instructor".'])
                ->addImage('photo', [
                    'label' => 'Photo',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                ])
                ->addText('object_position', [
                    'label' => 'Photo Focus',
                    'instructions' => 'CSS object-position, e.g. "50% 28%".',
                    'default_value' => '50% 30%',
                ])
                ->addWysiwyg('bio', [
                    'label' => 'Bio',
                    'tabs' => 'visual',
                    'media_upload' => 0,
                    'toolbar' => 'basic',
                ])
            ->endRepeater();
    }
}
