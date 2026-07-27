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
            ->addText('title', [
                'label' => 'Title',
                'default_value' => $titleDefault,
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
