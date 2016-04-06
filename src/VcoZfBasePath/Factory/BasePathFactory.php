<?php
namespace VcoZfBasePath\Factory;

use VcoZfBasePath\View\Helper\BasePath;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BasePathFactory implements FactoryInterface {

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService (ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $config = $realServiceLocator->get('Config');
        $basePath = new BasePath();
        $basePath->setConfig($config['VcoZfBasePath']);
        return $basePath;
    }
}
