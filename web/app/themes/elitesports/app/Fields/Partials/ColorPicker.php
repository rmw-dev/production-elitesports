<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

/**
 * Reusable brand color control.
 *
 * Renders a Log1x "Editor Palette" swatch picker bound to the theme's
 * registered editor-color-palette (see app/setup.php). Returns the color
 * slug, which maps 1:1 to a Tailwind brand token (e.g. "orange" => bg-orange).
 *
 * Usage:
 *   $fields->addPartial(ColorPicker::class);
 *   $fields->addPartial(ColorPicker::class, ['name' => 'text_color', 'label' => 'Text Color']);
 */
class ColorPicker extends Partial
{
    /**
     * The brand palette slugs the picker is restricted to.
     *
     * Mirrors the editor-color-palette registered in app/setup.php.
     *
     * @var string[]
     */
    public const BRAND_COLORS = [
        'ink',
        'ink-soft',
        'cream',
        'orange',
        'orange-bright',
        'orange-deep',
        'purple',
        'purple-bright',
        'purple-deep',
    ];

    /**
     * The partial field group.
     */
    public function fields(
        string $name = 'background_color',
        string $label = 'Background Color',
        string $instructions = '',
    ): Builder {
        $fields = Builder::make("color_picker_{$name}");

        $fields->addField($name, 'editor_palette', [
            'label' => $label,
            'instructions' => $instructions,
            'return_format' => 'slug',
            'allow_null' => true,
            'allowed_colors' => self::BRAND_COLORS,
        ]);

        return $fields;
    }
}
