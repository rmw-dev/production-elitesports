<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Vite;
use Roots\Acorn\View\Composer;
use WP_Post;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to the views.
     */
    public function with(): array
    {
        return [
            'siteName' => $this->siteName(),
            'logoUrl' => $this->logoUrl(),
            'brandName' => $this->brandName(),
            'primaryNav' => $this->primaryNav(),
            'footerNav' => $this->footerNav(),
            'ctas' => $this->ctas(),
            'footerIdentity' => $this->footerIdentity(),
            'footerLegal' => $this->footerLegal(),
            'socialLinks' => $this->socialLinks(),
        ];
    }

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * The brand logo URL (bundled theme asset).
     */
    public function logoUrl(): string
    {
        return Vite::asset('resources/images/ESA-Logo.png');
    }

    /**
     * The brand display name.
     */
    public function brandName(): string
    {
        return get_field('footer_name', 'option') ?: 'Elite Sports Academy';
    }

    /**
     * Header call-to-action buttons (ACF options, with defaults).
     */
    public function ctas(): array
    {
        return [
            'apply' => [
                'label' => get_field('apply_label', 'option') ?: 'Apply Now',
                'url' => get_field('apply_url', 'option') ?: 'https://heritageacademy.schoolmint.net/',
            ],
            'tour' => [
                'label' => get_field('tour_label', 'option') ?: 'Schedule a Tour',
                'url' => get_field('tour_url', 'option') ?: 'https://docs.google.com/forms/d/e/1FAIpQLSdagnRNZfXdf5yl_XXAaBS2Cn_DgD7qNzdJElZUtp8ngKnxoA/viewform',
            ],
        ];
    }

    /**
     * Footer identity block.
     */
    public function footerIdentity(): array
    {
        $address = get_field('footer_address', 'option')
            ?: "Powered by Heritage Academy\n10215 N 43rd Ave\nPhoenix, AZ 85051";

        return [
            'name' => $this->brandName(),
            'lines' => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|<br\s*\/?>/i', $address)))),
        ];
    }

    /**
     * Footer legal block (copyright, media notice, legal links).
     */
    public function footerLegal(): array
    {
        $notice = get_field('footer_media_notice', 'option')
            ?: "Media Notice: Some visuals are AI-generated, conceptual, or illustrative.\nFinal facilities, uniforms, programs, and details may vary.";

        $links = get_field('legal_links', 'option') ?: [];

        return [
            'copyright' => get_field('footer_copyright', 'option')
                ?: '© 2026 Elite Sports Academy & Heritage Academy. All rights reserved.',
            'mediaNotice' => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|<br\s*\/?>/i', $notice)))),
            'terms' => [
                'label' => $links['terms_label'] ?? 'Terms of Service',
                'url' => $links['terms_url'] ?? '/terms-of-service',
            ],
            'privacy' => [
                'label' => $links['privacy_label'] ?? 'Privacy Policy',
                'url' => $links['privacy_url'] ?? '/privacy-policy',
            ],
        ];
    }

    /**
     * Social links (ACF options, with defaults).
     */
    public function socialLinks(): array
    {
        $defaults = [
            'instagram' => 'https://www.instagram.com/elitesportsacademyaz/',
            'facebook' => 'https://www.facebook.com/EliteSportsAcademyPHX',
            'youtube' => 'https://www.youtube.com/@heritageacademyaz/shorts',
        ];

        return collect($defaults)
            ->map(fn ($default, $key) => [
                'label' => ucfirst($key),
                'icon' => $key,
                'url' => get_field("{$key}_url", 'option') ?: $default,
            ])
            ->filter(fn ($link) => ! empty($link['url']))
            ->values()
            ->all();
    }

    /**
     * The primary navigation as a nested array of items + children.
     */
    public function primaryNav(): array
    {
        return $this->navItems('primary_navigation');
    }

    /**
     * The footer navigation as a nested array (top-level = columns).
     */
    public function footerNav(): array
    {
        return $this->navItems('footer_navigation');
    }

    /**
     * Build a nested menu tree for the given theme location.
     */
    protected function navItems(string $location): array
    {
        $locations = get_nav_menu_locations();

        if (empty($locations[$location])) {
            return [];
        }

        $items = wp_get_nav_menu_items($locations[$location]);

        if (! $items) {
            return [];
        }

        return $this->buildTree($items);
    }

    /**
     * Convert a flat list of WP menu items into a nested tree.
     *
     * @param  WP_Post[]  $items
     */
    protected function buildTree(array $items, int $parent = 0): array
    {
        return collect($items)
            ->filter(fn (WP_Post $item) => (int) $item->menu_item_parent === $parent)
            ->map(fn (WP_Post $item) => [
                'id' => (int) $item->ID,
                'label' => $item->title,
                'url' => $item->url,
                'target' => $item->target ?: null,
                'rel' => $item->xfn ?: ($item->target === '_blank' ? 'noopener noreferrer' : null),
                'children' => $this->buildTree($items, (int) $item->ID),
            ])
            ->values()
            ->all();
    }
}
