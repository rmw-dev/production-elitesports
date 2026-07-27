<?php

namespace App\Blocks;

use Log1x\AcfComposer\Builder;

class Container extends BaseBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Container';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = 'container';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Constrains inner blocks to the site content width.';

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
    public $icon = 'align-center';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['container', 'wrapper', 'width', 'content', 'wrap'];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = ['page', 'post'];

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
        'jsx' => true,
    ];

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = 'full';

    /**
     * The block template.
     *
     * @var array
     */
    public $template = [];

    /**
     * Data passed to the view.
     */
    public function blockWith(): array
    {
        return [
            'eyebrow' => get_field('eyebrow'),
            'boxed' => (bool) get_field('boxed'),
            'firstBlock' => (bool) get_field('first_block'),
        ];
    }

    /**
     * The block field group.
     *
     * Exposes an optional eyebrow + a "Boxed" toggle on top of the shared
     * BaseBlock Settings (background color + padding); wraps InnerBlocks.
     */
    public function blockFields(Builder $fields): void
    {
        $fields
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'instructions' => 'Optional small uppercase label above the content.',
            ])
            ->addTrueFalse('first_block', [
                'label' => 'First Block',
                'instructions' => 'Add top spacing to clear the fixed site header when this is the first block on the page.',
                'ui' => 1,
                'default_value' => 0,
            ])
            ->addTrueFalse('boxed', [
                'label' => 'Boxed',
                'instructions' => 'Wrap the content in a bordered card with corner glow.',
                'ui' => 1,
                'default_value' => 0,
            ]);
    }
}
