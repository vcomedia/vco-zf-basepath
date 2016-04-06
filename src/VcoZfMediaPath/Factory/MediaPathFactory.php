<?php
namespace VcoZfMediaPath\Factory;

use VcoZfMediaPath\View\Helper\MediaPath;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MediaPathFactory implements FactoryInterface {

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
        $mediaPath = new MediaPath();
        $mediaPath->setConfig($config['VcoZfMediaPath']);
        return $mediaPath;
    }
}
