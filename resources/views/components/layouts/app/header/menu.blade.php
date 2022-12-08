<div class="menu-field">
    <div class="menu-trigger h-40 rounded-full flex space-x-10 items-center px-35 duration-300 cursor-pointer sm:space-x-0 sm:px-10 sm:h-30">
        <div class="text text-white font-semibold duration-300 sm:hidden">Kategoriler</div>
        <div class="web-icon sm:hidden">
            <x-app-icon name="chevron-to-bottom" class="w-10 fill-white duration-300 sm:hidden" />
        </div>
        <div class="mobile-icon hidden sm:block ml-0">
            <div class="mobile-menu-selector">
                <div class="inner"></div>
            </div>
        </div>
    </div>
    <?php
    $menuItems = [
        [
            'title' => 'Kodlama',
            'childs' => [
                [
                    'title' => 'Web Geliştirme',
                    'childs' => [
                        ['title' => 'Python',],
                        ['title' => 'Javascript',],
                    ],
                ],
                [
                    'title' => 'Veri Bilimi',
                    'childs' => [
                        ['title' => 'CSS',],
                        ['title' => 'HTML',],
                    ],
                ],
                [
                    'title' => 'Programlama Dilleri',
                    'childs' => [
                        ['title' => 'CSS',],
                        ['title' => 'HTML',],
                    ],
                ],
                [
                    'title' => 'Mobil Yazılım Geliştirme',
                    'childs' => [
                        ['title' => 'CSS',],
                        ['title' => 'HTML',],
                    ],
                ],
                [
                    'title' => 'Oyun Geliştirme',
                    'childs' => [
                        ['title' => 'CSS',],
                        ['title' => 'HTML',],
                    ],
                ],
                [
                    'title' => 'Yazılım Testi',
                    'childs' => [
                        ['title' => 'CSS',],
                        ['title' => 'HTML',],
                    ],
                ],
                [
                    'title' => 'Yazılım Mühendisliği',
                    'childs' => [
                        ['title' => 'CSS',],
                        ['title' => 'HTML',],
                    ],
                ],
            ],
        ],
        [
            'title' => 'Yabancı Dil',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Matematik',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Fen Bilimleri',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Tasarım',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Türkçe',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Sınav Rehberi',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Fitness',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Müzik',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Sağlık',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Marketing',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
        [
            'title' => 'Fotoğraf',
            'childs' => [
                [
                    'title' => 'Asya Dil Ailesi',
                    'childs' => [
                        ['title' => 'Japonca',],
                        ['title' => 'Çince',],
                    ],
                ],
                [
                    'title' => 'Avrupa Dil Ailesi',
                    'childs' => [
                        ['title' => 'İngilizce',],
                        ['title' => 'İspanya',],
                    ],
                ]
            ],
        ],
    ]
    ?>
    <div class="menu-wrapper">
        <ul class="menu-group min-w-300 bg-deep-mine-shaft p-50 flex flex-col gap-10">
            <?php foreach ($menuItems as $menuItem) : ?>
                <li class="menu-el">
                    <a href="" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                        <span><?= $menuItem['title'] ?></span>
                        <x-app-icon name="chevron-to-right" class="h-10" />
                    </a>
                    <ul class="menu-items-2 bg-cod-gray min-w-300 p-50 flex flex-col gap-10 backface-hidden">
                        <div class="splash absolute w-40 h-40 top-0 left-0 z-minus bg-cod-gray">
                            <img src="{{ asset('assets/image/other/splash-deep-mine-shaft.png') }}" class="w-full h-full">
                        </div>
                        <div class="splash absolute w-40 h-40 right-full bottom-0 z-minus bg-deep-mine-shaft">
                            <img src="{{ asset('assets/image/other/splash-cod-gray-2.png') }}" class="w-full h-full">
                        </div>
                        <?php foreach ($menuItem['childs'] as $menuItemsTwo) : ?>
                            <li class="menu-item-2">
                                <a href="" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                                    <span><?= $menuItemsTwo['title'] ?></span>
                                    <x-app-icon name="chevron-to-right" class="h-10" />
                                </a>
                                <ul class="menu-items-3 bg-deep-cod-gray min-w-300 p-50 flex flex-col gap-10">
                                    <div class="splash absolute w-40 h-40 left-0 top-0 bg-deep-cod-gray z-minus">
                                        <img src="{{ asset('assets/image/other/splash-cod-gray.png') }}">
                                    </div>
                                    <div class="splash absolute w-40 h-40 right-full bottom-0 z-minus bg-cod-gray">
                                        <img src="{{ asset('assets/image/other/splash-deep-cod-gray-2.png') }}" class="w-full h-full">
                                    </div>
                                    <div class="splash absolute w-40 h-40 top-0 left-full z-minus">
                                        <img src="{{ asset('assets/image/other/splash-deep-cod-gray.png') }}">
                                    </div>
                                    <?php foreach ($menuItemsTwo['childs'] as $menuItemsThree) : ?>
                                        <li class="menu-item-3">
                                            <a href="" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                                                <span><?= $menuItemsThree['title'] ?></span>
                                                <x-app-icon name="chevron-to-right" class="h-10" />
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- MOBILE MENU -->
    <div class="mobile-menu-field hidden md:block">
        <ul class="menu-inner">
            <?php if (isset($headerLoggedin) && $headerLoggedin) : ?>
                <li class="menu-el">
                    <a href="page-profile-edit.php" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                        <span>Profil</span>
                    </a>
                </li>
                <li class="menu-el">
                    <a href="page-favorites-php" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                        <span>Favorilerim</span>
                    </a>
                </li>
            <?php else : ?>
                <li class="menu-el">
                    <a href="page-login.php" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                        <span>Giriş Yap</span>
                    </a>
                </li>
                <li class="menu-el">
                    <a href="page-register.php" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                        <span>Kayıt Ol</span>
                    </a>
                </li>
            <?php endif; ?>
            <!-- GENEL MENÜLER -->
            <li class="menu-el">
                <a href="page-about.php" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                    <span>Hakkımızda</span>
                </a>
            </li>
            <li class="menu-el">
                <a href="page-help-desk.php" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                    <span>Yardım Destek</span>
                </a>
            </li>
            <li class="menu-el">
                <a href="javascript:;" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                    <span>Buraya Menü Eklenebilir Çıkarılabilir</span>
                </a>
            </li>
            <!-- KATEGORİLER -->
            <li class="menu-el has-sub-menu">
                <a href="javascript:;" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                    <span>Kategoriler</span>
                    <x-app-icon name="chevron-to-right" class="h-10" />
                </a>
                <ul class="menu-items-1 subbable">
                    <?php foreach ($menuItems as $menuItem) : ?>
                        <li class="menu-el has-sub-menu">
                            <a href="" class="menu-link text-white fill-white duration-300 hover:text-cerulean hover:fill-cerulean flex items-center justify-between">
                                <span><?= $menuItem['title'] ?></span>
                                <x-app-icon name="chevron-to-right" class="h-10" />
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
