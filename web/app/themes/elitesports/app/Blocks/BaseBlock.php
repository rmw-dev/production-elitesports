<?php

namespace App\Blocks;

use App\Fields\Partials\ColorPicker;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

abstract class BaseBlock extends Block
{
    /**
     * Use block API version 3 so previews render in an isolated iframe,
     * matching the front end (theme styles in, wp-admin styles out).
     */
    public $apiVersion = 3;

    /**
     * The block's own content fields.
     *
     * Each block that extends this base class implements this method to add
     * its own fields. Shared settings (e.g. spacing) are appended automatically.
     */
    abstract public function blockFields(Builder $fields): void;

    /**
     * Additional data passed to the view by the extending block.
     */
    public function blockWith(): array
    {
        return [];
    }

    /**
     * The block field group.
     *
     * Composes the extending block's fields and appends the shared
     * "Settings" tab with the spacing controls.
     */
    public function fields(): array
    {
        $fields = Builder::make(Str::snake(class_basename($this)));

        $fields->addTab('Content');

        $this->blockFields($fields);

        $fields
            ->addTab('Settings')
            ->addPartial(ColorPicker::class)
            ->addRange('padding_top', [
                'label' => 'Padding Top',
                'instructions' => 'Spacing above the block (1–16).',
                'default_value' => 1,
                'min' => 1,
                'max' => 16,
                'step' => 1,
            ])
            ->addRange('padding_bottom', [
                'label' => 'Padding Bottom',
                'instructions' => 'Spacing below the block (1–16).',
                'default_value' => 1,
                'min' => 1,
                'max' => 16,
                'step' => 1,
            ]);

        return $fields->build();
    }

    /**
     * Data passed to the view.
     *
     * Merges the extending block's data with the shared spacing classes.
     */
    public function with(): array
    {
        return array_merge($this->blockWith(), [
            'paddingClasses' => $this->paddingClasses(),
            'backgroundClass' => $this->backgroundClass(),
        ]);
    }

    /**
     * Build the spacing utility classes from the block settings.
     */
    public function paddingClasses(): string
    {
        $top = (int) get_field('padding_top');
        $bottom = (int) get_field('padding_bottom');

        return collect([
            $top ? "pt-{$top}" : null,
            $bottom ? "pb-{$bottom}" : null,
        ])->filter()->implode(' ');
    }

    /**
     * Build the background color utility class from the selected palette color.
     *
     * The palette slug maps directly to a Tailwind brand token,
     * e.g. "orange" => "bg-orange".
     */
    public function backgroundClass(): string
    {
        $color = get_field('background_color');

        return $color ? "bg-{$color}" : '';
    }

    /**
     * Auto-load this block's dedicated stylesheet and script.
     *
     * Convention: each block may ship its own assets at
     *   resources/css/blocks/{slug}.css
     *   resources/js/blocks/{slug}.js
     * where {slug} is the kebab-cased class name (e.g. Hero => "hero").
     * They are bundled by Vite (auto-globbed in vite.config.js) and injected
     * only when the block actually renders, so unused block CSS/JS never ships.
     */
    public function assets(array $block): void
    {
        $slug = Str::kebab(class_basename($this));

        $entries = collect([
            "resources/css/blocks/{$slug}.css",
            "resources/js/blocks/{$slug}.js",
        ])->filter(fn ($entry) => is_file(get_theme_file_path($entry)))
            ->values()
            ->all();

        if (empty($entries)) {
            return;
        }

        // Inject each block's bundle at most once per request.
        static $injected = [];

        if (isset($injected[$slug])) {
            return;
        }

        $injected[$slug] = true;

        echo Vite::withEntryPoints($entries)->toHtml();
    }
}
