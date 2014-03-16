<?php
/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */


namespace ZfReferential\Table;

use ZfTable\Table;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Sql\Select;
use Zend\Filter\Word\UnderscoreToCamelCase;
use ZfTable\Table\TableAbstract;
use Zend\Db\Adapter\Adapter;
use ZfTable\Source\SourceInterface;
use ZfTable\Source\SqlSelect;
use Zend\Form\Annotation\Hydrator;

class Entity extends TableAbstract
{
    protected $config = array(
        'name' => '',
    	'showPagination' => false,
    	'showQuickSearch'	=>	false,
    	'showItemPerPage'	=>	false,
    	'showColumnFilters' => false,
    );
    
     //Definition of headers
    protected $headers = array();
    
    /**
     * 
     * @var Metadata $metadata
     */
    protected $metadata = null;
    
    /**
     * 
     * @var array $associations
     */
    protected $associations = null;
    
    /**
     * 
     * @var array $primaries
     */
    protected $primaries = null;
    
    /**
     * 
     * @var TableObject $entity
     */
    protected $entity = null;
    
    /**
     * 
     * @var string $tableName
     */
    protected $tableName = null;
    
    /**
     * 
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter) {
    	$this->adapter = $adapter;
    }

    public function init()
    {
        //Table attributes
        if($this->entity){
        	$this->addAttr('id', $this->entity->getName());
        }
        
        if($this->entity && count($this->primaries)){
        	$ids = '';
        	foreach($this->primaries as $key=>$primary){
        		if($key != 0){
        			$ids.='-';
        		}
        		$ids.='%s';
        	}
        	
	        $this->getHeader($this->primaries[0])->getCell()->addDecorator('link', array(
	        	'url' => '/referential/edit/'.$this->entity->getName().'/'.$ids,
	        	'vars'	=>	$this->primaries
	        ));
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \ZfTable\DoctrineTable::initFilters()
     */
    protected function initFilters(SourceInterface $query)
    {
       
    }
    
    /**
     * 
     * @param string $entityName
     */
    public function setEntityName($entityName){
    	$this->entityName = $entityName;
    	
    	$this->metadata = new Metadata($this->adapter);
    	$headers = array();
    	 
    	if( !$this->metadata ){
    		throw new \Zend\Mvc\Exception\InvalidArgumentException("This resource name $entityName don't exist",404);
    	}
    	
    	$this->entity = $this->metadata->getTable($entityName);
    	$this->setSource( new SqlSelect( new Select($this->entity->getName()) ) );
    	$this->config['name'] = $this->entity->getName();
    	 
    	foreach($this->entity->getConstraints() as $constraint){
    		if( $constraint->getType() == 'PRIMARY KEY' ){
    			$this->primaries = $constraint->getColumns();
    		}
    	}
    	 
    	if(count($this->primaries)){
    		$headers[$this->primaries[0]] = array('title'=>$this->translate('id'), 'filters' => 'text');
    	}
    	 
    	if(count($this->entity->getColumns())){
    		foreach($this->entity->getColumns() as $column){
    			if(!in_array($column->getName(),$this->primaries)){
    				$headers[$column->getName()] = array('title'=>$column->getName(), 'filters' => 'text');
    			}
    		}
    	}
    	 
    	$this->setHeaders($headers);
    	
    	$this->init();
    }
    
    /**
     * 
     * @return TableObject
     */
    public function getEntity(){
    	return $this->entity;
    }
}
