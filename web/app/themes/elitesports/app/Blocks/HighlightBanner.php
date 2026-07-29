<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class HighlightBanner extends BaseBlock
{
    public $name = 'Highlight Banner';

    public $slug = 'highlight-banner';

    public $description = 'A bold gradient banner pairing a short label with a single highlight statement.';

    public $category = 'formatting';

    public $icon = 'flag';

    public $keywords = ['highlight', 'banner', 'pathway', 'callout'];

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
            'labelUppercase' => (bool) get_field('label_uppercase'),
            'body' => get_field('body'),
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addTextarea('label', ['label' => 'Label', 'default_value' => 'The Elite Pathway', 'rows' => 2, 'new_lines' => ''])
            ->addTrueFalse('label_uppercase', [
                'label' => 'Label — uppercase',
                'instructions' => 'Display this heading in uppercase.',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addTextarea('body', [
                'label' => 'Statement',
                'rows' => 3,
                'new_lines' => '',
            ]);
    }
}
