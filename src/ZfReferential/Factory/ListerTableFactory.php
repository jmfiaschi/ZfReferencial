<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Table\Lister;
use ZfReferential\Render\ObjectEditRender;

class ListerTableFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return Lister
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $moduleOptions \ZfReferential\Options\ModuleOptions  */
        $moduleOptions = $serviceLocator->get('ZfReferential\Options\ModuleOptions');
        
        $referentialList = null;
        if(is_string($moduleOptions->getRessources())){
        	$referentialList = $serviceLocator->get($moduleOptions->getRessources());
        }elseif($moduleOptions->getRessources() instanceof \Countable
        		|| is_array($moduleOptions->getRessources()) ){
        	$referentialList = $moduleOptions->getRessources();
        }
        
        $translate = $serviceLocator->get('translator');
        
        $table = new Lister();
        $table->setRender(new ObjectEditRender($table));
        $table->setReferentialList($referentialList);
        $table->setTranslate($translate);
        
        return $table;
    }
}
