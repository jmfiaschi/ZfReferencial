<?php
namespace ZfReferential\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Form\Annotation\AnnotationBuilder;
use ZfTableDoctrine\Controller\ControllerAbstract;
use Zend\Form\Form;
use Zend\Http\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use ZfReferential\Hydrator\ObjectProperty;
use ZfReferential\Form\FormBuilder;

class ReferentialController extends AbstractActionController
{
	/**
	 * 
	 * @var Countable
	 */
	protected $referentials = null;
	
	/**
	 * 
	 * @var Form $formBuilder
	 */
	protected $formBuilder = null;
	
	/**
	 * 
	 * @param Countable $referentials
	 */
	public function setReferentials($referentials){
		$this->referentials = $referentials;
		
		return $this;
	}
	
	/**
	 * 
	 * @return Countable $referentials
	 */
	public function getReferentials(){
		return $this->referentials;
	}
	
    public function indexAction()
    {
    	$view = new ViewModel();
    	
        $table = $this->getServiceLocator()->get('ZfReferential\Table\Index');
        $table->setParamAdapter($this->getRequest()->getPost());
	
        return new ViewModel(array(
            'table' => $table
        ));
    }
    
    public function listAction(){
    	if( !($list = $this->referentials[$this->params('name',null)] ) ){
    		throw new InvalidArgumentException(sprintf(
                'This referential not exist: "%s"',
                $this->params('name',null)
            ));
    	}
    	
    	$table = $this->getServiceLocator()->get('ZfReferential\Table\Lister');
    	$table->setReferential($this->params('name',null));
    	$table->setList($list);
    	$table->setParamAdapter($this->getRequest()->getPost());
    	
    	return new ViewModel(array(
            'table' => $table
        ));
    }
    
    public function addAction(){
    	
    }
    
    public function editAction(){
    	/* @var $referential \ZfReferential\Mapper\MapperInterface  */
    	if( !($list = $this->referentials[$this->params('name',null)] ) ){
    		throw new InvalidArgumentException(sprintf(
                'This referential not exist: "%s"',
                $this->params('name',null)
            ));
    	}
    	
    	if(!count($list)){
    		throw new InvalidArgumentException(sprintf(
    				'This referential not exist: "%s"',
    				$this->params('name',null)
    		));
    	}
    	
    	$hydrator = new ObjectProperty();
    	$object = null;
    	$position = null;
    	
    	foreach($list as $key=>$row){
    		if(is_object($row)){
    			$protectiesCheck = $hydrator->extract($row);
    		}else{
    			$protectiesCheck = $row;
    		}
    		if(count($protectiesCheck)){
    			foreach($protectiesCheck as $key=>$propertie){
    				if($propertie == $this->params()->fromQuery($key,null)){
    					unset($protectiesCheck[$key]);
    				}
    			}
    			if(count($protectiesCheck) == 0){
    				$object = $row;
    				$position = $key;
    				break;
    			}
    		}
    	}
    	
    	//get the builder
    	$formBuilder = $this->getServiceLocator()->get('ZfReferential\Form\FormBuilder');
    	if(!is_object($object)){
    		$object = $hydrator->hydrate($object, new \stdClass());
    	}
    	
    	$form = $formBuilder->createForm($object);
    	$form->setHydrator($hydrator);
    	$form->bind($object);
    	$request = $this->getRequest();
    	if ($request->isPost()){
    		$form->setData($request->getPost());
    		if ($form->isValid()){
    			$data = $form->getData(\Zend\Form\FormInterface::VALUES_AS_ARRAY);
    			if(isset($data[FormBuilder::BUTTON_CANCEL]) &&  $data[FormBuilder::BUTTON_CANCEL]){
    				return $this->redirect()->toRoute('referential/list',
					  array('name'=>$this->params('name',null)));
    			}
    			if(isset($data[FormBuilder::BUTTON_SAVE]) && $data[FormBuilder::BUTTON_SAVE] ){
    				$list[$position] = $form->getData();
    				
    				$this->referentials[$this->params('name',null)] = $list;
    				
    				return $this->redirect()->toRoute('referential/list',
    						array('name'=>$this->params('name',null)));
    			}
    		}
    	}
    	
    	return new ViewModel(array(
    		'form' => $form
    	));
    }
    
    public function deleteAction(){
    	 
    }
}
