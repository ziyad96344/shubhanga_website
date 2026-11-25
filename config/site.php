<?php

/*
|--------------------------------------------------------------------------
| Site settings
|--------------------------------------------------------------------------
| Shubhanga Publications, taken from https://shubhanga.co.in.
| Contact details, shipping and social links can be overridden from
| Admin > Settings (the `settings` table); these are the defaults.
*/

return [
    'name' => 'Shubhanga',
    'full_name' => 'Shubhanga Publications',
    'tagline' => 'Igniting Minds, Fueling Discovery',
    'founded' => 2024,
    'domain' => 'shubhanga.co.in',

    'logo' => '/images/brand/shubhanga-logo.webp',
    'logo_icon' => '/images/brand/shubhanga-icon.png',

    // Sample hero photo (Wikimedia Commons, CC BY 2.0, Shixart1985), saved
    // locally in public/images/site. Replace with the client's own photo.
    'hero_image' => '/images/site/hero-library.webp',

    'announcement' => 'Free shipping on prepaid orders above ₹499. Cash on delivery available.',

    // Editable in Admin > Settings.
    'shipping' => [
        'fee' => 49,
        'free_from' => 499,
    ],

    'contact' => [
        'address' => '3rd & 4th Floor, Balaji Empire, Jayabheri Enclave, Gachibowli, Hyderabad, Telangana 500032',
        'email' => 'info@shubhanga.co.in',
        'order_email' => 'order@shubhanga.co.in',
        // Working hours are not on the current site: confirm with the client.
        'hours' => 'Monday to Saturday, 9:30 am to 6:30 pm',
        'phones' => [
            ['label' => 'General enquiries', 'number' => '+91 90323 92424'],
            ['label' => 'Orders and schools', 'number' => '+91 78930 72424'],
            ['label' => 'Specimen copies', 'number' => '+91 78930 72424'],
        ],
    ],

    'stats' => [
        ['value' => 560, 'suffix' => '+', 'label' => 'titles from Nursery to Grade 10'],
        ['value' => 1000, 'suffix' => '+', 'label' => 'partner schools across India'],
        ['value' => 4, 'suffix' => '', 'label' => 'syllabuses: CBSE, Karnataka, Andhra Pradesh, Telangana'],
        ['value' => 2024, 'suffix' => '', 'label' => 'year founded'],
    ],

    'social' => [
        'Instagram' => 'https://www.instagram.com/shubhanga.publications',
        'YouTube' => 'https://youtube.com/@shubhanga.publications',
        'Facebook' => 'https://www.facebook.com/share/19m9mDLsrb/',
        'LinkedIn' => 'https://www.linkedin.com/company/shubhanga-publications1/',
    ],
];
