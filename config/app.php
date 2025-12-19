<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    | The application name can be set dynamically based on the domain.
    | Define domain-to-name mappings in the 'domain_names' array below.
    | The AppServiceProvider will override this value based on the current domain.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Domain to Application Name Mapping
    |--------------------------------------------------------------------------
    |
    | This array maps domains to application names. When a request comes from
    | one of these domains, the application name will be automatically set.
    | If no mapping is found, the default APP_NAME will be used.
    |
    */

    'domain_names' => [
        'venditio.at' => 'quickBill',
        'kmu-office.at' => 'KMU-Office',
        // Füge hier weitere Domain-Mappings hinzu
    ],

    /*
    |--------------------------------------------------------------------------
    | Domain to Logo Mapping
    |--------------------------------------------------------------------------
    |
    | This array maps domains to logo filenames. When a request comes from
    | one of these domains, the corresponding logo will be used.
    | The logo files should be located in the public/logo directory.
    | If no mapping is found, the default logo will be used.
    |
    */

    'domain_logos' => [
        'venditio.at' => 'VenditioLogo.png',
        'kmu-office.at' => 'VenditioLogo.png', // Ändere dies zu deinem KMU-Office Logo
        // Füge hier weitere Domain-zu-Logo-Mappings hinzu
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Logo
    |--------------------------------------------------------------------------
    |
    | The default logo filename to use when no domain-specific logo is found.
    |
    */

    'default_logo' => 'VenditioLogo.png',

    /*
    |--------------------------------------------------------------------------
    | Domain to Favicon Mapping
    |--------------------------------------------------------------------------
    |
    | This array maps domains to favicon filenames. When a request comes from
    | one of these domains, the corresponding favicon will be used.
    | The favicon files should be located in the public/logo directory.
    | If no mapping is found, the default favicon will be used.
    |
    */

    'domain_favicons' => [
        'venditio.at' => 'VenditioIcon.svg',
        'kmu-office.at' => 'VenditioIcon.svg', // Ändere dies zu deinem KMU-Office Favicon
        // Füge hier weitere Domain-zu-Favicon-Mappings hinzu
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Favicon
    |--------------------------------------------------------------------------
    |
    | The default favicon filename to use when no domain-specific favicon is found.
    |
    */

    'default_favicon' => 'VenditioIcon.svg',

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | This value determines the "version" of your application. This can be
    | overridden by git tags or composer.json version.
    |
    */

    'version' => env('APP_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => env('APP_LOCALE', 'de'),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'de',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store' => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        Intervention\Image\ImageServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),

];
