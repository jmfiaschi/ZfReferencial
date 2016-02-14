<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Controller\ReferentialController;
use ZfReferential\Collection\RessourceCollection;

class RessourceCollectionFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return ReferentialController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$ressourceList = $serviceLocator->get('Config')['zf_referential']['ressources'];
    	$ressourceCollection = new RessourceCollection($ressourceList);
    	$ressourceCollection->setServiceLocator($serviceLocator);
        return $ressourceCollection;
    }
}
