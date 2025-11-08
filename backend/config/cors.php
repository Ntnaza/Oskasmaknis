<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tentukan path mana yang harus menangani CORS.
    |--------------------------------------------------------------------------
    |
    | Anda bisa menentukan path (misal: 'api/*') atau biarkan ['*']
    | untuk menerapkan CORS ke semua rute.
    |
    */

    'paths' => [
    'api/*',
    'sanctum/csrf-cookie',
    'storage/*', // <-- TAMBAHKAN BARIS INI
],

    /*
    |--------------------------------------------------------------------------
    | Metode HTTP yang Diizinkan.
    |--------------------------------------------------------------------------
    |
    | Masukkan metode (verbs) yang diizinkan untuk cross-origin.
    | ['*'] akan mengizinkan semua metode.
    |
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Asal (Origins) yang Diizinkan.
    |--------------------------------------------------------------------------
    |
    | Tentukan origin mana yang boleh mengakses.
    | Kita sudah tambahkan 'http://localhost:8080' untuk Anda.
    |
    */

    'allowed_origins' => [
        'http://localhost:8080', // <-- INI UNTUK VUE FRONTEND ANDA
    ],

    /*
    |--------------------------------------------------------------------------
    | Pola Origins yang Diizinkan.
    |--------------------------------------------------------------------------
    |
    | Anda bisa menggunakan wildcard (tanda *) di sini, misal: '*.example.com'.
    |
    */

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Header yang Diizinkan.
    |--------------------------------------------------------------------------
    |
    | Header yang diizinkan saat request 'Access-Control-Request-Headers'.
    | ['*'] akan mengizinkan semua.
    |
    */

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Header yang Boleh Diekspos.
    |--------------------------------------------------------------------------
    |
    | Header yang boleh diekspos ke browser (client).
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age (Durasi Cache Preflight).
    |--------------------------------------------------------------------------
    |
    | Mengatur durasi (detik) cache untuk respons preflight (OPTIONS).
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Mendukung Kredensial (Supports Credentials).
    |--------------------------------------------------------------------------
    |
    | Mengatur header 'Access-Control-Allow-Credentials'.
    | Ini diperlukan untuk hal seperti session/cookies.
    |
    */

    'supports_credentials' => false,

];