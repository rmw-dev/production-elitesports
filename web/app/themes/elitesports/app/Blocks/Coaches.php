<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Coaches extends BaseBlock
{
    public $name = 'Coaches';

    public $slug = 'coaches';

    public $description = 'Coaching staff hero and roster of coach cards with portrait, role and bio.';

    public $category = 'formatting';

    public $icon = 'groups';

    public $keywords = ['coaches', 'team', 'staff', 'roster'];

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
            'coaches' => get_field('coaches') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Elite Sports Academy'])
            ->addTextarea('title', ['label' => 'Title', 'default_value' => 'Meet Our Team', 'rows' => 2, 'new_lines' => ''])
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
            ->addRepeater('coaches', [
                'label' => 'Coaches',
                'button_label' => 'Add Coach',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addTextarea('name', ['label' => 'Name', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('name_uppercase', [
                    'label' => 'Name — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addText('role', ['label' => 'Role'])
                ->addImage('photo', [
                    'label' => 'Photo',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                ])
                ->addText('object_position', [
                    'label' => 'Photo Focus',
                    'instructions' => 'CSS object-position, e.g. "50% 28%".',
                    'default_value' => '50% 28%',
                ])
                ->addSelect('accent', [
                    'label' => 'Accent',
                    'choices' => ['orange' => 'Orange', 'purple' => 'Purple'],
                    'default_value' => 'orange',
                    'allow_null' => 0,
                ])
                ->addTextarea('bio', [
                    'label' => 'Bio',
                    'rows' => 4,
                    'new_lines' => '',
                ])
            ->endRepeater();
    }
}
