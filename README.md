Zend Framework 2 Cachebuster
===================

ZF2 Cachebuster is a cache buster module closely modeled after https://github.com/gordonknoppe/magento-cachebuster.  It facilitates automatic purging of static assets from HTTP caches such as browser cache, CDN, Varnish, etc using best practices outlined within the HTML5 boilerplate community.

See section "Filename-based cache busting" in:
https://github.com/h5bp/server-configs-apache/blob/2.14.0/dist/.htaccess#L968

URLs affected:

* /js/ 
* /media/
* /skin/

## Overview

The module provides cachebusting by automatically altering the URI created by Magento for static files by adding the timestamp of the file to the filename:

* Before: http://www.example.com/js/file.js
* After:  http://www.example.com/js/file.1324429472.js

## Example uses

* Automatically invalidating cache when using Cloudfront CDN 
  * Amazon's Cloudfront CDN can be configured to use an origin server but by it's nature will not refresh your updated file until it's cache time expires or you send an invalidation request using their API.  
* No more browser cache issues (ie. Them: "Where's that CSS change I requested?".  You: "Oh, did you hit refresh?")
  * Adding far-future expires headers, which is good for reducing the number of requests to your server, means that even without a CDN you have probably experienced browser cache causing a waste of time on what turns out to be a non-issues.

## Installation

### Composer
 * Install [Composer](http://getcomposer.org/doc/00-intro.md)
 * Install the module using Composer into your application's vendor directory. Add the following line to your `composer.json`.

 ```json
 {
    "require": {
        "vcomedia/vco-zf-mediapath": "dev-master"
    }
 }
```
 * Execute ```composer update```
 * Enable the module in your ZF2 `application.config.php` file.

 ```php
 return array(
     'modules' => array(
         'VcoZfMediaPath'
     )
 );
 ```
 * Copy and paste the `vco-zf-logger/config/module.vco-zf-mediapath.local.php.dist` file to your `config/autoload` folder and customize it with your configuration settings. Make sure to remove `.dist` from your file. Your `module.vco-zf-mediapath.local.php` might look something like the following:

  ```php
<?php
/**
 * VcoZfMediaPath - Zend Framework 2 basePath view helper wrapper.
 *
 * @category Module
 * @package  VcoZfMediaPath
 * @author   Vahag Dudukgian (valeeum)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/vcomedia/vco-zf-mediapath/
 */

namespace VcoZfMediaPath;

return array(
    'VcoZfMediaPath' => array(
        'docRoot' => '/public',
        'cdnOptions' => array(
            'enabled' => true,
            'defaultDomain' => array(
                'http' => 'http://cdn.getpaidlaw.com',
                'https' => 'http://cdn.getpaidlaw.com'
            )
        ),
        'cacheBusterOptions' => array(
            'enabled' => true,
            'basePaths' => array(
                '/skin/',
                '/js/',
                '/scripts/',
                '/css/',
                '/stylesheets/',
                '/media/',
                '/uploads/',
                '/images/',
                '/img/',
                '/imgs/',
                '/cache/',
                '/fonts/'
            ),
           'extensions' => array(
                'js',
                'css',
                'jpg',
                'jpeg',
                'gif',
                'png',
                'ttf',
                'woff',
                'pdf'
            )            
        )
    )
);

  ```

Note: The configuration array returned by the top level 'VcoZfLogger' key is passed directly into the Log class constructor with the exception of the mail transport and mongo credential injection which are both optional.

## mod_rewrite configuration

The following mod_rewrite rules need to be enabled for your store when using this module, potentially via `.htaccess` file or Virtualhost definition.  

    <IfModule mod_rewrite.c>

    ############################################
    ## rewrite files for magento cachebuster

        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.+)\.(\d+)\.(js|css|png|jpeg|jpg|gif)$ $1.$3 [L]

    </IfModule>

## nginx configuration

For nginx you will need to add a rule like the following to your site definition.

    location ~* (.+)\.(\d+)\.(js|css|png|jpg|jpeg|gif)$ {
        try_files $uri $1.$3;
    }

## License

Licensed under the Apache License, Version 2.0