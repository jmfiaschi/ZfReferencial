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
        if(is_string($moduleOptions->getRessources())){
        	$controller->setReferentialList($parentLocator->get($moduleOptions->getRessources()));
        }elseif($moduleOptions->getRessources() instanceof \Countable
			|| is_array($moduleOptions->getRessources()) ){
        	$controller->setReferentialList($moduleOptions->getRessources());
        }

        return $controller;
    }
}
