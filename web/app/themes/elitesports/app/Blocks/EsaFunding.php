<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use Log1x\AcfComposer\Builder;

class EsaFunding extends BaseBlock
{
    public $name = 'ESA Funding';

    public $slug = 'esa-funding';

    public $description = 'Two-column funding section: heading and copy beside a stat snapshot card with highlighted rows.';

    public $category = 'formatting';

    public $icon = 'chart-bar';

    public $keywords = ['esa', 'funding', 'snapshot', 'scholarship'];

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
            'introExtra' => get_field('intro_extra'),
            'buttons' => get_field('buttons') ?: [],
            'snapshotLabel' => get_field('snapshot_label'),
            'snapshotValue' => get_field('snapshot_value'),
            'snapshotCaption' => get_field('snapshot_caption'),
            'snapshotBody' => get_field('snapshot_body'),
            'snapshotRows' => get_field('snapshot_rows') ?: [],
            'disclaimer' => get_field('disclaimer'),
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addTextarea('intro_extra', [
                'label' => 'Extra Paragraph',
                'rows' => 3,
                'new_lines' => '',
            ]);

        $fields->addPartial(Buttons::class);

        $fields
            ->addText('snapshot_label', ['label' => 'Snapshot Label', 'default_value' => 'Funding snapshot'])
            ->addText('snapshot_value', ['label' => 'Snapshot Value', 'default_value' => '90%'])
            ->addText('snapshot_caption', ['label' => 'Snapshot Caption'])
            ->addTextarea('snapshot_body', ['label' => 'Snapshot Body', 'rows' => 3, 'new_lines' => ''])
            ->addRepeater('snapshot_rows', [
                'label' => 'Snapshot Rows',
                'button_label' => 'Add Row',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('text', ['label' => 'Row'])
            ->endRepeater()
            ->addTextarea('disclaimer', ['label' => 'Disclaimer', 'rows' => 2, 'new_lines' => '']);
    }
}
