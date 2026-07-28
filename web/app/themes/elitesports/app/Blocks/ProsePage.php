<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class ProsePage extends BaseBlock
{
    public $name = 'Prose Page';

    public $slug = 'prose-page';

    public $description = 'A constrained long-form prose article with kicker and headline. Ideal for legal and policy pages.';

    public $category = 'formatting';

    public $icon = 'media-document';

    public $keywords = ['prose', 'legal', 'policy', 'article', 'terms'];

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
        ];
    }

    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', ['label' => 'Eyebrow', 'default_value' => 'Elite Sports Academy'])
            ->addText('title', ['label' => 'Title'])
            ->addWysiwyg('body', [
                'label' => 'Body',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ]);
    }
}
