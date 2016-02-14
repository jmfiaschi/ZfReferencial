<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return ModuleOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$referential = $serviceLocator->get('Config')['zf_referential'];
    	
    	$ressourceCollection = $serviceLocator->get('ZfReferential\Collection\Ressource');

    	$referential['ressources'] = $ressourceCollection;
    	
        return new ModuleOptions($referential);
    }
}
