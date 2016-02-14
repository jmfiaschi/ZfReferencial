<?php
namespace ZfReferential;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ControllerProviderInterface;

class Module
    implements BootstrapListenerInterface,
               ConfigProviderInterface,
               ServiceProviderInterface,
               ControllerProviderInterface,
               DependencyIndicatorInterface
{

    /**
     * Listen to the bootstrap event
     *
     * @param MvcEvent|EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {

        $application    = $e->getApplication();
        $serviceManager = $application->getServiceManager();
    }


    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     *
     * Set autoloader config for RbacUserDoctrineOrm module
     *
     * @return array\Traversable
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ServiceProviderInterface::getServiceConfig()
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/../../config/services.config.php';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ControllerProviderInterface::getControllerConfig()
     */
    public function getControllerConfig() {
    	return include __DIR__ . '/../../config/controllers.config.php';
    }

    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\DependencyIndicatorInterface::getModuleDependencies()
     */
    public function getModuleDependencies()
    {
        return array();
    }
}
