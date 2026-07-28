<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

/**
 * Reusable section heading group: eyebrow + title + rich-text body.
 *
 * Render with <x-section-heading :eyebrow :title :body />.
 */
class Heading extends Partial
{
    public function fields(
        string $eyebrowDefault = '',
        string $titleDefault = '',
    ): Builder {
        $fields = Builder::make('section_heading');

        $fields
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'default_value' => $eyebrowDefault,
            ])
            ->addTextarea('title', [
                'label' => 'Title',
                'rows' => 2,
                'new_lines' => '',
                'default_value' => $titleDefault,
            ])
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
            ]);

        return $fields;
    }
}
