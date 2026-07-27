<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class ReadingList extends BaseBlock
{
    public $name = 'Reading List';

    public $slug = 'reading-list';

    public $description = 'A section heading beside a list of grouped rows (e.g. grade-level reading lists).';

    public $category = 'formatting';

    public $icon = 'book';

    public $keywords = ['reading', 'list', 'books', 'rows'];

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
            'rows' => get_field('rows') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Classical Reading'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Intro',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addRepeater('rows', [
                'label' => 'Rows',
                'button_label' => 'Add Row',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addText('label', ['label' => 'Row Label'])
                ->addRepeater('items', [
                    'label' => 'Items',
                    'button_label' => 'Add Item',
                    'layout' => 'table',
                    'min' => 0,
                ])
                    ->addTextarea('text', ['label' => 'Item', 'rows' => 2, 'new_lines' => ''])
                ->endRepeater()
            ->endRepeater();
    }
}
