<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Controller\ReferentialController;
use ZfReferential\Mapper\MapperInterface;

class ReferentialControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return ReferentialController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /* @var $moduleOptions \ZfReferential\Options\ModuleOptions  */
        $moduleOptions = $parentLocator->get('ZfReferential\Options\ModuleOptions');
        
        $controller = new ReferentialController();
        if(is_string($moduleOptions->getReferentials())){
        	$controller->setReferentials($parentLocator->get($moduleOptions->getReferentials()));
        }elseif($moduleOptions->getReferentials() instanceof Countable){
        	$controller->setReferentials($moduleOptions->getReferentials());
        }elseif(is_array($moduleOptions->getReferentials())){
        	$controller->setReferentials($moduleOptions->getReferentials());
        }

        return $controller;
    }
}
