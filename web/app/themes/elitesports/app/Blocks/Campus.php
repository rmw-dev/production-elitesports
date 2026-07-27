<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Campus extends BaseBlock
{
    public $name = 'Campus';

    public $description = 'Campus & facilities: heading, stats, campus map with address, and a features grid.';

    public $category = 'formatting';

    public $icon = 'location-alt';

    public $keywords = ['campus', 'facilities', 'map', 'features'];

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
            'stats' => get_field('stats') ?: [],
            'map' => get_field('map') ?: [],
            'addressLines' => get_field('address') ?: [],
            'features' => get_field('features') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Campus & Facilities'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addRepeater('stats', [
                'label' => 'Stats',
                'button_label' => 'Add Stat',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('value', ['label' => 'Value'])
                ->addText('label', ['label' => 'Label'])
            ->endRepeater()
            ->addImage('map', [
                'label' => 'Campus Map',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addRepeater('address', [
                'label' => 'Address Lines',
                'button_label' => 'Add Line',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('text', ['label' => 'Line'])
            ->endRepeater()
            ->addRepeater('features', [
                'label' => 'Features',
                'button_label' => 'Add Feature',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('text', ['label' => 'Feature'])
            ->endRepeater();
    }
}
