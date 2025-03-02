<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Entrypoints
    |--------------------------------------------------------------------------
    |
    | Here you can specify which files will be considered entry points
    | in the build system. You can also include or exclude certain
    | files to control the behavior of the compilation process.
    |
    */

    'entrypoints' => [
        'resources/js/app.tsx',
    ],

    /*
    |--------------------------------------------------------------------------
    | Build Path
    |--------------------------------------------------------------------------
    |
    | This is the directory where Vite will generate its compiled assets.
    | It should be inside the public directory so that Laravel can
    | serve the built files in production without Vite running.
    |
    */

    'build_path' => 'public/build',

    /*
    |--------------------------------------------------------------------------
    | Hot Module Replacement (HMR)
    |--------------------------------------------------------------------------
    |
    | This configuration controls how Laravel interacts with Vite's
    | development server for hot module replacement (HMR).
    |
    */

    'hmr' => env('VITE_HMR', true),

    /*
    |--------------------------------------------------------------------------
    | Asset URL Prefix
    |--------------------------------------------------------------------------
    |
    | If your assets are hosted on a CDN or a different subdomain, you
    | may specify a prefix here that will be prepended to all asset URLs.
    |
    */

    'asset_url' => env('VITE_ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Server Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the Vite development server settings. These
    | settings should match the server configuration in vite.config.ts.
    |
    */

    'server' => [
        'host' => env('VITE_HOST', 'pos.test'),
        'port' => env('VITE_PORT', 5173),
        'protocol' => env('VITE_HTTPS', true) ? 'https' : 'http',
    ],

    /*
    |--------------------------------------------------------------------------
    | SSR (Server-Side Rendering)
    |--------------------------------------------------------------------------
    |
    | If you're using server-side rendering with Inertia.js or another
    | framework, you may need to configure this option to properly
    | load your SSR entry points.
    |
    */

    'ssr' => [
        'enabled' => env('VITE_SSR', false),
        'entry' => 'resources/js/ssr.tsx',
    ],

];
