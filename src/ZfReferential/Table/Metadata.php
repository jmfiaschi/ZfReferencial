<?php
namespace ZfReferential\Table;

use ZfTable\Table\InMemory;
use Zend\Db\Metadata\Metadata as DbMetadata;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use ZfTable\Source\InMemory as InMemorySource;
use ZfTable\Source\SourceInterface;

class Metadata extends InMemory
{
    protected $config = array(
        'name' => 'Tables',
    	'showPagination' => false,
    	'showQuickSearch'	=>	false,
    	'showItemPerPage'	=>	false,
    	'showColumnFilters' => false,
    );
    
     //Definition of headers
    protected $headers = array(
        'name' => array('title' => 'Name', 'filters' => 'text' )
    );
    
    /**
     * 
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter){
    	$metadata = new DbMetadata($adapter);
    	$tableNames = $metadata->getTableNames();
    	
    	$metadatas = array();
    	if(count($tableNames)){
    		foreach($tableNames as $tableName){
    			/* @var $table TableObject */
    			$table = $metadata->getTable($tableName);
    			
    			/** Add only if is not a table join */
    			$pkc = null;
    			if( count($table->getConstraints()) ){
	    			foreach($table->getConstraints() as $constraint){
	    				/* @var $constraint ConstraintObject */
			            if ( $constraint->getType() == 'PRIMARY KEY' ){ 
			            	if( count($table->getColumns()) != count($constraint->getColumns()) ){
			                	$metadatas[$tableName] = array('name'=>$tableName,'metadata'=>$table);
			            	}
			            }
	    			}
    			}
    		}
    	}
    	$this->setSource(new InMemorySource($metadatas));
    }
	
    /**
     * init table columns
     */
    public function init()
    {
        //Table attributes
        $this->addAttr('id', 'metadata');
        
        $this->getHeader('name')->getCell()->addDecorator('link', array(
        		'url' => '/referential/list/%s',
        		'vars' => array('name')
        ));
    }
    
    /**
     * (non-PHPdoc)
     * @see \ZfTable\Table\TableAbstract::initFilters()
     */
    protected function initFilters(SourceInterface $source)
    {
       
    }
}
