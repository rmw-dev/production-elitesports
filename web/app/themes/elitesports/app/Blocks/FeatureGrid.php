<?php

namespace App\Blocks;

use App\Fields\Partials\Heading;
use Log1x\AcfComposer\Builder;

class FeatureGrid extends BaseBlock
{
    public $name = 'Feature Grid';

    public $description = 'A heading plus a responsive grid of numbered feature cards with alternating brand accents.';

    public $category = 'formatting';

    public $icon = 'grid-view';

    public $keywords = ['features', 'grid', 'cards', 'pillars'];

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
            'columns' => (int) (get_field('columns') ?: 4),
            'showNumbers' => (bool) get_field('show_numbers'),
            'items' => get_field('items') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields->addPartial(Heading::class);

        $fields
            ->addSelect('columns', [
                'label' => 'Columns',
                'choices' => [2 => 'Two', 3 => 'Three', 4 => 'Four'],
                'default_value' => 4,
                'allow_null' => 0,
            ])
            ->addTrueFalse('show_numbers', [
                'label' => 'Show numbers',
                'instructions' => 'Display an incrementing 01, 02, 03 badge on each card.',
                'ui' => 1,
                'default_value' => 1,
            ])
            ->addRepeater('items', [
                'label' => 'Items',
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
            ->endRepeater();
    }
}
