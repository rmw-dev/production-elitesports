<?php

namespace App\Blocks;

use App\Fields\Partials\Buttons;
use Log1x\AcfComposer\Builder;

class Hero extends BaseBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Hero';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Full-height hero with background video, headline, CTAs and a stat card.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'formatting';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'cover-image';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['hero', 'banner', 'video', 'cover'];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = ['page'];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => ['full'],
        'mode' => true,
        'multiple' => true,
        'jsx' => false,
    ];

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = 'full';

    /**
     * Data passed to the view.
     */
    public function blockWith(): array
    {
        return [
            'eyebrow' => get_field('eyebrow'),
            'headline' => get_field('headline'),
            'brandLine' => get_field('brand_line'),
            'subhead' => get_field('subhead'),
            'micro' => get_field('micro'),
            'buttons' => get_field('buttons') ?: [],
            'card' => get_field('card') ?: [],
            'stats' => $this->stats(),
            'media' => get_field('media') ?: [],
            'meta' => get_field('meta') ?: [],
        ];
    }

    /**
     * Retrieve the stat rows from the card group.
     */
    public function stats(): array
    {
        $card = get_field('card') ?: [];

        return $card['stats'] ?? [];
    }

    /**
     * The block field group.
     */
    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'default_value' => 'Elite Sports Academy',
            ])
            ->addText('headline', [
                'label' => 'Headline',
                'default_value' => 'Where Serious Athletes Are Built',
            ])
            ->addText('brand_line', [
                'label' => 'Brand Tagline',
                'instructions' => 'Short uppercase line under the headline.',
            ])
            ->addWysiwyg('subhead', [
                'label' => 'Subhead',
                'instructions' => 'Supporting paragraph. Rich text supported.',
                'tabs' => 'all',
                'media_upload' => 0,
                'delay' => 1,
            ])
            ->addText('micro', [
                'label' => 'Micro Line',
                'instructions' => 'Small highlighted line below the subhead.',
            ]);

        $fields->addPartial(Buttons::class);

        $fields
            ->addGroup('card', [
                'label' => 'Stat Card',
                'layout' => 'block',
            ])
                ->addText('heading', [
                    'label' => 'Card Heading',
                    'default_value' => 'The Daily Standard',
                ])
                ->addWysiwyg('support', [
                    'label' => 'Card Body',
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
                    ->addText('link_label', ['label' => 'Link Label (optional)'])
                    ->addUrl('link_url', ['label' => 'Link URL (optional)'])
                ->endRepeater()
            ->endGroup();

        $fields
            ->addGroup('media', [
                'label' => 'Media',
                'layout' => 'block',
            ])
                ->addImage('poster', [
                    'label' => 'Poster Image',
                    'instructions' => 'Shown before the video loads / as a fallback.',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ])
                ->addFile('video_landscape', [
                    'label' => 'Background Video (Landscape)',
                    'instructions' => 'MP4. Used on tablet/desktop.',
                    'return_format' => 'url',
                    'mime_types' => 'mp4,webm',
                ])
                ->addFile('video_portrait', [
                    'label' => 'Background Video (Portrait)',
                    'instructions' => 'Optional MP4 used on narrow/mobile screens.',
                    'return_format' => 'url',
                    'mime_types' => 'mp4,webm',
                ])
                ->addText('sound_label', [
                    'label' => 'Sound Button Label (muted)',
                    'default_value' => 'Play Film',
                ])
                ->addText('sound_label_active', [
                    'label' => 'Sound Button Label (playing)',
                    'default_value' => 'Sound Off',
                ])
            ->endGroup();

        $fields
            ->addGroup('meta', [
                'label' => 'Meta Links',
                'layout' => 'block',
            ])
                ->addText('location_text', ['label' => 'Location Text'])
                ->addUrl('map_url', ['label' => 'Map URL'])
                ->addText('phone_label', ['label' => 'Phone Label'])
                ->addText('phone_url', ['label' => 'Phone Href (e.g. tel:...)'])
            ->endGroup();
    }
}
