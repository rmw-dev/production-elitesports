<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class SiteSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Site Settings';

    /**
     * The option page menu icon.
     *
     * @var string
     */
    public $icon = 'dashicons-admin-settings';

    /**
     * The option page position.
     *
     * @var int
     */
    public $position = 59;

    /**
     * The option page field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('site_settings');

        $fields
            ->addTab('Header')
            ->addText('apply_label', [
                'label' => 'Apply Button Label',
                'default_value' => 'Apply Now',
            ])
            ->addUrl('apply_url', [
                'label' => 'Apply Button URL',
                'default_value' => 'https://heritageacademy.schoolmint.net/',
            ])
            ->addText('tour_label', [
                'label' => 'Tour Button Label',
                'default_value' => 'Schedule a Tour',
            ])
            ->addUrl('tour_url', [
                'label' => 'Tour Button URL',
                'default_value' => 'https://docs.google.com/forms/d/e/1FAIpQLSdagnRNZfXdf5yl_XXAaBS2Cn_DgD7qNzdJElZUtp8ngKnxoA/viewform',
            ]);

        $fields
            ->addTab('Footer')
            ->addText('footer_name', [
                'label' => 'Identity Name',
                'default_value' => 'Elite Sports Academy',
            ])
            ->addTextarea('footer_address', [
                'label' => 'Identity Lines',
                'instructions' => 'One line per row (e.g. "Powered by Heritage Academy", street, city).',
                'new_lines' => 'br',
                'default_value' => "Powered by Heritage Academy\n10215 N 43rd Ave\nPhoenix, AZ 85051",
            ])
            ->addText('footer_copyright', [
                'label' => 'Copyright Line',
                'default_value' => '© 2026 Elite Sports Academy & Heritage Academy. All rights reserved.',
            ])
            ->addTextarea('footer_media_notice', [
                'label' => 'Media Notice',
                'instructions' => 'One line per row.',
                'new_lines' => 'br',
                'default_value' => "Media Notice: Some visuals are AI-generated, conceptual, or illustrative.\nFinal facilities, uniforms, programs, and details may vary.",
            ]);

        $fields
            ->addGroup('legal_links', [
                'label' => 'Legal Links',
                'layout' => 'row',
            ])
            ->addText('terms_label', ['label' => 'Terms Label', 'default_value' => 'Terms of Service'])
            ->addUrl('terms_url', ['label' => 'Terms URL', 'default_value' => '/terms-of-service'])
            ->addText('privacy_label', ['label' => 'Privacy Label', 'default_value' => 'Privacy Policy'])
            ->addUrl('privacy_url', ['label' => 'Privacy URL', 'default_value' => '/privacy-policy'])
            ->endGroup();

        $fields
            ->addTab('Social')
            ->addUrl('instagram_url', [
                'label' => 'Instagram URL',
                'default_value' => 'https://www.instagram.com/elitesportsacademyaz/',
            ])
            ->addUrl('facebook_url', [
                'label' => 'Facebook URL',
                'default_value' => 'https://www.facebook.com/EliteSportsAcademyPHX',
            ])
            ->addUrl('youtube_url', [
                'label' => 'YouTube URL',
                'default_value' => 'https://www.youtube.com/@heritageacademyaz/shorts',
            ]);

        return $fields->build();
    }
}
