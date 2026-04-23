<?php

return [
    'basic' => [
        'name' => 'Basic',
        'price' => 29,
        'stripe_id' => 'price_1A2B3C4D5E6F7G8H9I0J',
        'features' => [
            'Up to 5 cases',
            'Basic reporting',
            'Email support',
        ],
    ],
    'business' => [
        'name' => 'Business',
        'price' => 89,
        'stripe_id' => 'price_2B3C4D5E6F7G8H9I0J1K',
        'features' => [
            'Up to 25 cases',
            'Advanced reporting',
            'Priority email support',
            'Custom fields',
            'Team collaboration',
        ],
    ],
    'enterprise' => [
        'name' => 'Enterprise',
        'price' => 199,
        'stripe_id' => 'price_3C4D5E6F7G8H9I0J1K2L',
        'features' => [
            'Unlimited cases',
            'Advanced analytics',
            'Phone & email support',
            'Custom integrations',
            'Dedicated account manager',
            'White-label options',
        ],
    ],
];
