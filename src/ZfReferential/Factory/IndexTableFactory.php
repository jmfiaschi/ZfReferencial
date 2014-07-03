<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Table\Index;
use ZfReferential\Mapper\MapperInterface;

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
        
        $translate = $serviceLocator->get('translator');
        
        
        
        $referentials = null;
        if(is_string($moduleOptions->getReferentials())){
        	$referentials = $serviceLocator->get($moduleOptions->getReferentials());
        }elseif($moduleOptions->getReferentials() instanceof Countable){
        	$referentials = $moduleOptions->getReferentials();
        }elseif(is_array($moduleOptions->getReferentials())){
        	$referentials = $moduleOptions->getReferentials();
        }
        
        $table = new Index($referentials);
        $table->setTranslate($translate);

        return $table;
    }
}
