<?php
namespace ZfReferential\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfReferential\Table\Metadata;
use ZfReferential\Table\Entity;
use Zend\Form\Annotation\AnnotationBuilder;
use ZfTableDoctrine\Controller\ControllerAbstract;

class ReferentialController extends ControllerAbstract
{
    public function indexAction()
    {
    	$view = new ViewModel();
    	
    	/* @var $metadataTable Metadata */
        $metadataTable = $this->getServiceLocator()->get('ZfReferential\Table\Metadata');
        $metadataTable->setParamAdapter($this->getRequest()->getPost());

        return new ViewModel(array(
            'table' => $metadataTable
        ));
    }
    
    public function listAction(){
    	$entityName = $this->params('name',null);
    	
    	/* @var $entityTable Entity */
    	$entityTable = $this->getServiceLocator()->get('ZfReferential\Table\Entity');
    	$entityTable->setEntityName($entityName);
    	$entityTable->setParamAdapter($this->getRequest()->getPost());
    	
    	return new ViewModel(array(
            'table' => $entityTable
        ));
    }
    
    public function editAction(){
    	$entityName = $this->params('name',null);
    	$ids = explode('-',$this->params('ids',null));
 
    	/* @var $formBuilder \ZfReferential\Form\Entity */
    	$form = $this->getServiceLocator()->get('ZfReferential\Form\Entity');
    	$form->setName($entityName);
    	
    	return new ViewModel(array(
            'form' => $form
        ));
    }
}
