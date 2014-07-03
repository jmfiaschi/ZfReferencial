<?php
namespace ZfReferential\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfReferential\Table\Index;
use ZfReferential\Mapper\MapperInterface;
use ZfReferential\Form\FormBuilder;

class FormBuilderFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return Index
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $moduleOptions \ZfReferential\Options\ModuleOptions  */
        $moduleOptions = $serviceLocator->get('ZfReferential\Options\ModuleOptions');
   
        $form = new FormBuilder();
        
        return $form;
    }
}
