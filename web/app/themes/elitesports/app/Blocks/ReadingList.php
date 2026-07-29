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
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'body' => get_field('body'),
            'rows' => get_field('rows') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Classical Reading'])
            ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
            ->addTrueFalse('title_uppercase', [
                'label' => 'Title — uppercase',
                'instructions' => 'Display this heading in uppercase.',
                'ui' => 1,
                'default_value' => 0,
            ])
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
                ->addTextarea('label', ['label' => 'Row Label', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('label_uppercase', [
                    'label' => 'Row Label — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
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
