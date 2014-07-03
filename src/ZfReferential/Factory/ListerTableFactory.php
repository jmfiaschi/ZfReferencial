<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Table\Lister;

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
        
        $translate = $serviceLocator->get('translator');
        
        $table = new Lister();
        $table->setTranslate($translate);
        
        return $table;
    }
}
