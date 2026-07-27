<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use App\Fields\Partials\Heading;
use Log1x\AcfComposer\Builder;

class MediaText extends BaseBlock
{
    public $name = 'Media + Text';

    public $description = 'Two-column section with an image on one side and heading, body, bullet list and buttons on the other.';

    public $category = 'formatting';

    public $icon = 'align-pull-left';

    public $keywords = ['media', 'text', 'two column', 'image', 'feature'];

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
            'body' => get_field('body'),
            'bullets' => get_field('bullets') ?: [],
            'buttons' => get_field('buttons') ?: [],
            'image' => get_field('image') ?: [],
            'imageSide' => get_field('image_side') ?: 'right',
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields->addPartial(Heading::class);

        $fields
            ->addRepeater('bullets', [
                'label' => 'Bullet List',
                'button_label' => 'Add Bullet',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('text', ['label' => 'Text'])
            ->endRepeater();

        $fields->addPartial(Buttons::class);

        $fields
            ->addImage('image', [
                'label' => 'Image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addSelect('image_side', [
                'label' => 'Image Side',
                'choices' => ['right' => 'Right', 'left' => 'Left'],
                'default_value' => 'right',
                'allow_null' => 0,
            ]);
    }
}
