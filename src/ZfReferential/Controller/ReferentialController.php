<?php

namespace ZfReferential\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Form;
use Zend\Http\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use ZfReferential\Hydrator\ObjectProperty;
use ZfReferential\Form\FormBuilder;
use ZfReferential\Collection\RessourceCollectionInterface;
use ZfcDB\Mapper\MapperInterface;
use Zend\Mvc\MvcEvent;

class ReferentialController extends AbstractActionController {
	/**
	 *
	 * @var RessourceCollectionInterface
	 */
	protected $referentialList = null;
	
	/**
	 *
	 * @var Form $formBuilder
	 */
	protected $formBuilder = null;
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see \Zend\Mvc\Controller\AbstractActionController::onDispatch()
	 */
	public function onDispatch(MvcEvent $e) {
		$response = parent::onDispatch ( $e );
		
		if ($response instanceof \Zend\View\Model\ViewModel) {
			if ($this->getRequest ()->isXmlHttpRequest ()) {
				$response->setTerminal ( true );
			}
		}
		
		return $response;
	}
	
	/**
	 *
	 * @param RessourceCollectionInterface $referentialList        	
	 */
	public function setReferentialList(RessourceCollectionInterface $referentialList) {
		$this->referentialList = $referentialList;
		
		return $this;
	}
	
	/**
	 *
	 * @return RessourceCollectionInterface $referentialList
	 */
	public function getReferentialList() {
		return $this->referentialList;
	}
	
	/**
	 * list referential elements
	 *
	 * @throws InvalidArgumentException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function indexAction() {
		$view = new ViewModel ();
		
		$table = $this->getServiceLocator ()->get ( 'ZfReferential\Table\Index' );
		$table->setParamAdapter ( $this->getRequest ()->getPost () );
		
		return new ViewModel ( array (
				'table' => $table 
		) );
	}
	
	/**
	 * list all elements of a referential
	 *
	 * @throws InvalidArgumentException
	 * @return \Zend\View\Model\ViewModel
	 */
	public function listAction() {
		/* @var $mapper MapperInterface */
		if (! ($mapper = $this->referentialList->get ( $this->params ( 'name', null ) ))) {
			throw new InvalidArgumentException ( sprintf ( 'This referential not exist: "%s"', $this->params ( 'name', null ) ) );
		}
		
		if ($mapper instanceof MapperInterface) {
			$dataList = $mapper->fetch ();
		}
		
		/* @var \ZfReferential\Table\Lister $table */
		$table = $this->getServiceLocator ()->get ( 'ZfReferential\Table\Lister' );
		$table->setObjectPrototype ( $mapper->getEntityPrototype () );
		$table->setDataList ( $dataList );
		$table->setParamAdapter ( $this->getRequest ()->getPost () );
		
		return new ViewModel ( array (
				'table' => $table 
		) );
	}
	
	/**
	 * edit an element
	 *
	 * @throws InvalidArgumentException
	 * @return Zend\Http\Response
	 */
	public function editAction() {
		/* @var $mapper MapperInterface */
		if (! ($mapper = $this->referentialList [$this->params ( 'name', null )])) {
			throw new InvalidArgumentException ( sprintf ( 'This referential not exist: "%s"', $this->params ( 'name', null ) ) );
		}
		
		if (! count ( array_filter ( $this->params ()->fromQuery () ) )) {
			$object = clone $mapper->getEntityPrototype ();
		} else {
			$object = $mapper->fetchOneBy ( $this->params ()->fromQuery () );
		}
		
		// get the builder
		$formBuilder = $this->getServiceLocator ()->get ( 'ZfReferential\Form\FormBuilder' );
		
		$form = $formBuilder->createForm ( $object );
		$form->setHydrator ( $mapper->getHydrator () );
		$form->bind ( $object );
		
		$request = $this->getRequest ();
		if ($request->isPost ()) {
			$form->setData ( $request->getPost () );
			if ($form->isValid ()) {
				$data = $form->getData ( \Zend\Form\FormInterface::VALUES_AS_ARRAY );
				if (isset ( $data [FormBuilder::BUTTON_CANCEL] ) && $data [FormBuilder::BUTTON_CANCEL]) {
					return $this->redirect ()->toRoute ( 'modules.zf-referential/list', array (
							'name' => $this->params ( 'name', null ) 
					) );
				}
				if (isset ( $data [FormBuilder::BUTTON_SAVE] ) && $data [FormBuilder::BUTTON_SAVE]) {
					$mapper->save ( $form->getData () );
					
					return $this->redirect ()->toRoute ( 'modules.zf-referential/list', array (
							'name' => $this->params ( 'name', null ) 
					) );
				}
			}
		}
		
		return new ViewModel ( array (
				'form' => $form 
		) );
	}
	
	/**
	 * add an element
	 *
	 * @throws InvalidArgumentException
	 * @return Zend\Http\Response
	 */
	public function addAction() {
		/* @var $mapper MapperInterface */
		if (! ($mapper = $this->referentialList [$this->params ( 'name', null )])) {
			throw new InvalidArgumentException ( sprintf ( 'This referential not exist: "%s"', $this->params ( 'name', null ) ) );
		}
		
		if (count ( array_filter ( $this->params ()->fromQuery () ) )) {
			$object = $mapper->save ( $this->params ()->fromQuery () );
		}
		
		return $this->redirect ()->toRoute ( 'modules.zf-referential/list', array (
				'name' => $this->params ( 'name', null ) 
		) );
	}
	
	/**
	 * delete an element
	 *
	 * @throws InvalidArgumentException
	 * @return Zend\Http\Response
	 */
	public function deleteAction() {
		/* @var $mapper MapperInterface */
		if (! ($mapper = $this->referentialList [$this->params ( 'name', null )])) {
			throw new InvalidArgumentException ( sprintf ( 'This referential not exist: "%s"', $this->params ( 'name', null ) ) );
		}
		
		if (count ( array_filter ( $this->params ()->fromQuery () ) )) {
			$object = $mapper->delete ( $this->params ()->fromQuery () );
		}
		
		return $this->redirect ()->toRoute ( 'modules.zf-referential/list', array (
				'name' => $this->params ( 'name', null ) 
		) );
	}
}
