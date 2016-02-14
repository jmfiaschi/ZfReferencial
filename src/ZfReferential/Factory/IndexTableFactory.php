<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Table\Index;
use ZfReferential\Collection\RessourceCollectionInterface;

class IndexTableFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return Index
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $moduleOptions \ZfReferential\Options\ModuleOptions  */
        $moduleOptions = $serviceLocator->get('ZfReferential\Options\ModuleOptions');
        
        $referentials = null;
        if(is_string($moduleOptions->getRessources())){
        	$referentials = $serviceLocator->get($moduleOptions->getRessources());
        }elseif($moduleOptions->getRessources() instanceof \Countable
			|| is_array($moduleOptions->getRessources()) ){
        	$referentials = $moduleOptions->getRessources();
        }
        
        $table = new Index($referentials);
        $translate = $serviceLocator->get('translator');
        $table->setTranslate($translate);

        return $table;
    }
}
