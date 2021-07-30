<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Path
    |--------------------------------------------------------------------------
    |
    | Path to the JSON file where settings are stored.
    |
    */

    'path' => storage_path('app/site.json'),

    /*
    |--------------------------------------------------------------------------
    | Sidebar Label
    |--------------------------------------------------------------------------
    |
    | The text that Nova displays for this tool in the navigation sidebar.
    |
    */

    'sidebar-label' => '站点设置',

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The browser/meta page title for the tool.
    |
    */

    'title' => '站点设置',

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | The good stuff :). Each setting defined here will render a field in the
    | tool. The only required key is `key`, other available keys include `type`,
    | `label`, `help`, `placeholder`, `language`, and `panel`.
    |
    */

    'settings' => [

        [
            'key' => 'site_name',
            'label' => '站点名称：',
            'panel' => '站点设置',
        ],
        [
            'key' => 'contact_email',
            'label' => '联系邮箱：',
            'panel' => '站点设置',
        ],
        [
            'key' => 'seo_description',
            'label' => 'SEO-Description：',
            'panel' => '站点设置',
            'type' => 'textarea',
        ],
        [
            'key' => 'seo_keyword',
            'label' => 'SEO-Keywords：',
            'panel' => '站点设置',
            'type' => 'textarea',
        ],


//        [
//            'key' => 'twitter_url',
//            'label' => 'Twitter Profile',
//            'panel' => 'Social',
//        ],
//
//        [
//            'key' => 'feature_42',
//            'label' => 'Feature 42',
//            'type' => 'toggle',
//            'help' => 'For the upcoming release. <a href="/docs#feature_42">Read more here.</a>',
//        ],
//
//        [
//            'key' => 'welcome',
//            'label' => 'Welcome Message',
//            'type' => 'textarea',
//            'help' => 'Greeting for new users on their first login.',
//        ],
//
//        [
//            'key' => 'snippet',
//            'label' => 'Tracking Snippet',
//            'type' => 'code',
//            'language' => 'htmlmixed',
//            'help' => 'Analytics snippet to add to all marketing pages.',
//        ],
//
//        [
//            'key' => 'theme',
//            'label' => 'Default App Theme',
//            'type' => 'select',
//            'options' => [
//                'dark' => 'Dark theme',
//                'light' => 'Light theme',
//            ],
//        ],
//
//        [
//            'key' => 'timeout',
//            'type' => 'Number',
//            'label' => 'Timeout (min.)',
//        ],

    ],

];
