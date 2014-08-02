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
use ZfcBase\Mapper\AbstractDbMapper;
use ZfReferential\Mapper\MapperInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use ZfTable\Source\InMemory;
use Zend\Mvc\Exception\RuntimeException;

class Lister extends TableAbstract
{
    protected $config = array(
        'name' => 'List',
    	'showPagination' => true,
    	'showQuickSearch'	=>	false,
    	'showItemPerPage'	=>	false,
    	'showColumnFilters' => false,
    );
    
    /**
     * 
     * @var MapperInterface $referential
     */
    protected $list = null;
    
    /**
     * 
     * @var string $referential
     */
    protected $referential = null;
    
    /**
     * 
     * @var HydratorAwareInterface
     */
    protected $hydrator = null;
    
    /**
     * 
     * @param HydratorAwareInterface $hydrator
     */
    public function __construct($hydrator = null) {
    	if(!$hydrator){
    		$this->hydrator = new ObjectProperty();
    	}
    }

    public function init()
    {
    	if(count($this->getHeaders())){
	    	$array = array_keys($this->getHeaders());
	    	$this->getHeader('edit')->getCell()->addDecorator('template', array(
	    			'template' => '<button type="button" class="btn btn-primary btn-sm edit" onclick="javascript:window.location=\'/referential/edit/'.$this->referential.'?'.implode('=%s&',$this->fields).'=%s\';"><span class="glyphicon glyphicon-pencil"></span></button>',
	    			'vars'	=>	$this->fields
	    	));
	    	$this->getHeader('delete')->getCell()->addDecorator('template', array(
	    			'template' => '<button type="button" class="btn btn-danger btn-sm delete" onclick="javascript:window.location=\'/referential/delete/'.$this->referential.'?'.implode('=%s&',$this->fields).'=%s\';"><span class="glyphicon glyphicon-remove-circle"></span></button>',
	    			'vars'	=>	$this->fields
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
     * @param Object $list
     * @return \ZfReferential\Table\Lister
     */
    public function setList($list){
    	$this->list = $list;
    	$this->fields = null;
    	
    	if(count($list)){
    		if(is_object($list[0])){
    			$array = array();
    			foreach($list as $element){
    				$array[] = $this->hydrator->extract($element);
    			}
    			$list = $array;
    		}
    		$this->fields = array_keys($list[0]);
    		
    		if(count($this->fields)){
	    		foreach ($this->fields as $value){
	    			$headers[$value] = array('title' => $this->getTranslate()->translate($value), 'filters' => 'text');
	    		}
	    		$headers['edit'] = array('title' => $this->getTranslate()->translate('edit'));
	    		$headers['delete'] = array('title' => $this->getTranslate()->translate('delete'));
	    		$this->setHeaders($headers);
    		}else{
    			throw new RuntimeException("Impossible to get public properties of object");
    		}
    	}
    	$this->setSource( new InMemory( $list ) );

    	$this->init();
    	
    	return $this;
    }
    
    /**
     * 
     * @param string referential
     * @return \ZfReferential\Table\Lister
     */
    public function setReferential($referential){
    	$this->referential = $referential;
    	
    	return $this;
    }
    
    /**
     * 
     * @param unknown $hydrator
     * @return \ZfReferential\Table\Lister
     */
    public function setHydrator($hydrator){
    	$this->hydrator = $hydrator;
    	
    	return $this;
    }
}
