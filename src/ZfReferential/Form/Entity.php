<?php 
namespace ZfReferential\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;

class Entity extends Form
{
	/**
	 * 
	 * @var Adapter
	 */
	protected $adapter = null;
	
	/**
	 * 
	 * @var string
	 */
	protected $entityName = '';
	
	/**
	 * 
	 * @var Metadata
	 */
	protected $metadata;
	
	/**
	 * 
	 * @var TableObject $entity
	 */
	protected $entity = null;
	
	public function __construct($entityName = null, $options = array(), $adapter){
		parent::__construct($entityName, $options);
		
		$this->adapter = $adapter;
		
		if($entityName){
			$this->setName($entityName);
		}
	}
	
	public function setName($entityName){
		parent::setName($entityName);
		$this->entityName = $entityName;
		$this->metadata = new Metadata($this->adapter);
		
		if( !$this->metadata ){
			throw new \Zend\Mvc\Exception\InvalidArgumentException("This resource name $entityName don't exist",404);
		}
		
		$this->entity = $this->metadata->getTable($entityName);
		
		if(count($this->entity->getColumns())){
			foreach($this->entity->getColumns() as $key=>$column){
				$this->add(array(
					'type' => 'Zend\Form\Element\Text',
					'name' => $column->getName(),
					'tabindex' =>$key,
					'options' => array(
						'label' => $column->getName(),
					)
				));
			}
		}
	}
}