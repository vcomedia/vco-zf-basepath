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

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Class Module
 *
 * @see ConfigProviderInterface
 * @see ViewHelperProviderInterface
 * @package VcoZfMediaPath
 */

class Module implements ConfigProviderInterface, ViewHelperProviderInterface {

    /**
     * @return array
     */
    public function getConfig () {
        return require __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig () {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    /** @return array */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'mediapath' => 'VcoZfMediaPath\Factory\MediaPathFactory'
            )
        );
    }
}
