<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Icecat API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the Icecat API integration.
    | You can set your Icecat username and other related settings here.
    |
    */

    'username' => env('ICECAT_USERNAME', 'xpertitsolution6'),

    /*
    | Maximum gallery images stored per imported product (500x500 versions).
    */
    'max_images' => (int) env('ICECAT_MAX_IMAGES', 8),
];
