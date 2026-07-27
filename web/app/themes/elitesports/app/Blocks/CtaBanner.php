<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use Log1x\AcfComposer\Builder;

class CtaBanner extends BaseBlock
{
    public $name = 'CTA Banner';

    public $slug = 'cta-banner';

    public $description = 'A full-width gradient call-to-action banner with eyebrow, heading, body and buttons.';

    public $category = 'formatting';

    public $icon = 'megaphone';

    public $keywords = ['cta', 'banner', 'call to action', 'next steps'];

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
            'buttons' => get_field('buttons') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Next Steps'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ]);

        $fields->addPartial(Buttons::class);
    }
}
