<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

/**
 * Reusable "buttons" repeater.
 *
 * Lets editors add an arbitrary number of buttons anywhere a block needs
 * calls-to-action. Each row maps directly to the <x-button-link> component
 * (label, url, new tab, variant).
 *
 * Usage:
 *   $fields->addPartial(Buttons::class);
 *   $fields->addPartial(Buttons::class, ['name' => 'footer_buttons', 'label' => 'Footer Buttons']);
 *
 * Render with the matching Blade component:
 *   <x-buttons :buttons="$buttons" />
 */
class Buttons extends Partial
{
    /**
     * The available button variants (mirrors resources/css/app.css .button-link-*).
     *
     * @var array<string, string>
     */
    public const VARIANTS = [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'tertiary' => 'Tertiary',
        'ghost' => 'Ghost',
    ];

    /**
     * The partial field group.
     */
    public function fields(
        string $name = 'buttons',
        string $label = 'Buttons',
        string $instructions = '',
    ): Builder {
        $fields = Builder::make("buttons_{$name}");

        $fields
            ->addRepeater($name, [
                'label' => $label,
                'instructions' => $instructions,
                'button_label' => 'Add Button',
                'layout' => 'block',
                'min' => 0,
            ])
                ->addText('label', [
                    'label' => 'Label',
                    'required' => 1,
                ])
                ->addUrl('url', [
                    'label' => 'URL',
                ])
                ->addTrueFalse('new_tab', [
                    'label' => 'Open in new tab',
                    'ui' => 1,
                    'default_value' => 0,
                ])
                ->addSelect('variant', [
                    'label' => 'Style',
                    'choices' => self::VARIANTS,
                    'default_value' => 'primary',
                    'allow_null' => 0,
                    'return_format' => 'value',
                ])
            ->endRepeater();

        return $fields;
    }
}
