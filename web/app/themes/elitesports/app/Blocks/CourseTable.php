<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class CourseTable extends BaseBlock
{
    public $name = 'Course Table';

    public $slug = 'course-table';

    public $description = 'A horizontally scrolling table of columns (e.g. grade levels) each listing course rows.';

    public $category = 'formatting';

    public $icon = 'editor-table';

    public $keywords = ['table', 'courses', 'sequence', 'grades'];

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
            'columns' => get_field('columns') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Course Sequence'])
            ->addText('title', ['label' => 'Title'])
            ->addRepeater('columns', [
                'label' => 'Columns',
                'button_label' => 'Add Column',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addText('heading', ['label' => 'Column Heading'])
                ->addRepeater('items', [
                    'label' => 'Rows',
                    'button_label' => 'Add Row',
                    'layout' => 'table',
                    'min' => 0,
                ])
                    ->addText('text', ['label' => 'Course'])
                ->endRepeater()
            ->endRepeater();
    }
}
