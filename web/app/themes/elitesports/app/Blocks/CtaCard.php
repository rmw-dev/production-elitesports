<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use Log1x\AcfComposer\Builder;

class CtaCard extends BaseBlock
{
    public $name = 'CTA Card';

    public $slug = 'cta-card';

    public $description = 'A bold gradient call-to-action panel with an image, label, heading, body and buttons.';

    public $category = 'formatting';

    public $icon = 'megaphone';

    public $keywords = ['cta', 'call to action', 'banner', 'card'];

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
            'label' => get_field('label'),
            'title' => get_field('title'),
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'body' => get_field('body'),
            'buttons' => get_field('buttons') ?: [],
            'image' => get_field('image') ?: [],
            'imageSide' => get_field('image_side') ?: 'left',
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('label', ['label' => 'Label / Eyebrow'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ]);

        $fields->addPartial(Buttons::class);

        $fields
            ->addImage('image', [
                'label' => 'Image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addSelect('image_side', [
                'label' => 'Image Side',
                'choices' => ['left' => 'Left', 'right' => 'Right'],
                'default_value' => 'left',
                'allow_null' => 0,
            ]);
    }
}
