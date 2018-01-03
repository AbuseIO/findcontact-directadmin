# findcontact-directadmin
findcontact module for IP lookups using the Directadmin Api

## Installation
    
    composer require abuseio/findcontact-directadmin
     
## Use the findcontact-directadmin module
copy the ```extra/config/main.php``` to the config override directory of your environment (e.g. production)

#### production

    cp vendor/abuseio/findcontact-directadmin/extra/config/main.php config/production/main.php
    
#### development

    cp vendor/abuseio/findcontact-directadmin/extra/config/main.php config/development/main.php
    
add the following line to providers array in the file config/app.php:

    'AbuseIO\FindContact\Directadmin\DirectadminServiceProvider'
    
## Configuration


Replace the null value in ````'appid' => null,```` with your application id, e.g.
    
    <?php
    
    return [
        'findcontact-directadmin' => [           
            'appid'          => 'MyAppId,
            'enabled'        => true,
            'auto_notify'    => false,
        ],
    ];

