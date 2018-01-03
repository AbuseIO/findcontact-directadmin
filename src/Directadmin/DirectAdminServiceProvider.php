<?php

namespace AbuseIO\Findcontact\Directadmin;

use Illuminate\Support\ServiceProvider;

class DirectAdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * merge the config
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->mergeConfigFrom(base_path('vendor/abuseio/findcontact-directadmin').'/config/Directadmin.php', 'Findcontact');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}