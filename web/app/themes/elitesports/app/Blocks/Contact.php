<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use Log1x\AcfComposer\Builder;

class Contact extends BaseBlock
{
    public $name = 'Contact';

    public $description = 'Closing contact panel: address, phone, email, buttons and a media notice.';

    public $category = 'formatting';

    public $icon = 'email-alt';

    public $keywords = ['contact', 'address', 'cta', 'phone'];

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
            'title' => get_field('title'),
            'titleUppercase' => (bool) get_field('title_uppercase'),
            'body' => get_field('body'),
            'addressLines' => get_field('address') ?: [],
            'phoneLabel' => get_field('phone_label'),
            'phoneUrl' => get_field('phone_url'),
            'emailLabel' => get_field('email_label'),
            'emailUrl' => get_field('email_url'),
            'buttons' => get_field('buttons') ?: [],
            'note' => get_field('note'),
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
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
            ->addRepeater('address', [
                'label' => 'Address Lines',
                'button_label' => 'Add Line',
                'layout' => 'table',
                'min' => 0,
            ])
                ->addText('text', ['label' => 'Line'])
            ->endRepeater()
            ->addText('phone_label', ['label' => 'Phone Label'])
            ->addText('phone_url', ['label' => 'Phone URL', 'instructions' => 'e.g. tel:4804614487'])
            ->addText('email_label', ['label' => 'Email Label'])
            ->addText('email_url', ['label' => 'Email URL', 'instructions' => 'e.g. mailto:info@example.com'])
            ->addPartial(Buttons::class)
            ->addTextarea('note', ['label' => 'Media Notice', 'rows' => 2]);
    }
}
