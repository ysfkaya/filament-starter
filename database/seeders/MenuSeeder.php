<?php

namespace Database\Seeders;

use App\Traits\HasTemporaryFiles;
use Illuminate\Database\Seeder;
use Ysfkaya\Menu\Facades\Menu;

class MenuSeeder extends Seeder
{
    use HasTemporaryFiles;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // MENU
        $tree = [
            'en' => [
                'header' => [
                    [
                        'title' => 'A',
                        'url' => '#',
                        'children' => [
                            [
                                'title' => 'A1',
                                'url' => '#',
                            ],
                            [
                                'title' => 'A2',
                                'url' => '#',
                            ],
                        ],
                    ],
                    [
                        'title' => 'B',
                        'url' => '#',
                    ],
                    [
                        'title' => 'C',
                        'url' => '#',
                        'children' => [
                            [
                                'title' => 'C1',
                                'url' => '#',
                            ],
                            [
                                'title' => 'C2',
                                'url' => '#',
                            ],
                        ],
                    ],
                ],
                'footer' => [
                    [
                        'title' => 'Company',
                        'url' => '#',
                        'children' => [
                            [
                                'title' => 'About Us',
                                'url' => '/about-us',
                            ],
                            [
                                'title' => 'Contact Us',
                                'url' => '/contact-us',
                            ],
                            [
                                'title' => 'Terms of Service',
                                'url' => '/terms-of-service',
                            ],
                            [
                                'title' => 'Privacy Policy',
                                'url' => '/privacy-policy',
                            ],
                            [
                                'title' => 'Copyright Notice',
                                'url' => '/copyright-notice',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Social',
                        'url' => '#',
                        'children' => [
                            [
                                'title' => 'Facebook',
                                'url' => 'https://www.facebook.com/',
                            ],
                            [
                                'title' => 'Twitter',
                                'url' => 'https://twitter.com/',
                            ],
                            [
                                'title' => 'Instagram',
                                'url' => 'https://www.instagram.com/',
                            ],
                            [
                                'title' => 'LinkedIn',
                                'url' => 'https://www.linkedin.com/',
                            ],
                            [
                                'title' => 'YouTube',
                                'url' => 'https://www.youtube.com/',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($tree as $locale => $groups) {
            foreach ($groups as $group => $items) {
                Menu::create($this->mapItems($items, $group, $locale));
            }
        }
    }

    protected function mapItems($items, $group, $locale)
    {
        return array_map(fn ($item) => [
            'title' => $item['title'],
            'url' => $item['url'],
            'target' => '_self',
            'group' => $group,
            'locale' => $locale,
            'children' => $this->mapItems($item['children'] ?? [], $group, $locale),
        ], $items);
    }
}
