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
            'enabled' => false,
            'defaultDomain' => array(
                'http' => '',
                'https' => ''
            )
        ),
        'cacheBusterOptions' => array(
            'enabled' => false,
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
