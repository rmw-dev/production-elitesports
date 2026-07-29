<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use Log1x\AcfComposer\Builder;

class InfoColumns extends BaseBlock
{
    public $name = 'Info Columns';

    public $slug = 'info-columns';

    public $description = 'A section heading with a grid of info cards, each with an optional bullet list. Layout can be split or stacked.';

    public $category = 'formatting';

    public $icon = 'screenoptions';

    public $keywords = ['cards', 'columns', 'features', 'list'];

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
            'introExtra' => get_field('intro_extra'),
            'layout' => get_field('layout') ?: 'split',
            'columns' => (int) (get_field('columns') ?: 2),
            'cardStyle' => get_field('card_style') ?: 'surface',
            'titleStyle' => get_field('title_style') ?: 'label',
            'cards' => get_field('cards') ?: [],
            'buttons' => get_field('buttons') ?: [],
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow'])
            ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
            ->addTrueFalse('title_uppercase', [
                'label' => 'Title — uppercase',
                'instructions' => 'Display this heading in uppercase.',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addTextarea('intro_extra', [
                'label' => 'Extra Intro Paragraph',
                'instructions' => 'Optional paragraph shown beneath the heading.',
                'rows' => 3,
                'new_lines' => '',
            ])
            ->addSelect('layout', [
                'label' => 'Layout',
                'choices' => ['split' => 'Heading beside cards', 'stack' => 'Heading above cards'],
                'default_value' => 'split',
                'allow_null' => 0,
            ])
            ->addSelect('columns', [
                'label' => 'Card Columns',
                'choices' => [1 => '1', 2 => '2', 3 => '3'],
                'default_value' => 2,
                'allow_null' => 0,
            ])
            ->addSelect('card_style', [
                'label' => 'Card Style',
                'choices' => ['surface' => 'Surface card', 'outline' => 'Outline'],
                'default_value' => 'surface',
                'allow_null' => 0,
            ])
            ->addSelect('title_style', [
                'label' => 'Card Title Style',
                'choices' => ['kicker' => 'Kicker', 'label' => 'Label', 'display' => 'Display heading'],
                'default_value' => 'label',
                'allow_null' => 0,
            ])
            ->addRepeater('cards', [
                'label' => 'Cards',
                'button_label' => 'Add Card',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addTextarea('title', ['label' => 'Title', 'rows' => 2, 'new_lines' => ''])
                ->addTrueFalse('title_uppercase', [
                    'label' => 'Title — uppercase',
                    'instructions' => 'Display this heading in uppercase.',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addWysiwyg('body', [
                    'label' => 'Body',
                    'tabs' => 'all',
                    'media_upload' => 0,
                    'delay' => 1,
                ])
                ->addRepeater('bullets', [
                    'label' => 'Bullets',
                    'button_label' => 'Add Bullet',
                    'layout' => 'table',
                    'min' => 0,
                ])
                    ->addText('text', ['label' => 'Bullet'])
                ->endRepeater()
            ->endRepeater();

        $fields->addPartial(Buttons::class);
    }
}
