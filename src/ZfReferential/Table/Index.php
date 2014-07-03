<?php
namespace ZfReferential\Table;

use ZfTable\Table\InMemory;
use Zend\Db\Metadata\Metadata as DbMetadata;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use ZfTable\Source\InMemory as InMemorySource;
use ZfTable\Source\SourceInterface;

class Index extends InMemory
{
	/**
	 *
	 * @var array
	 */
	protected $referentials = array();
	
	/**
	 * 
	 * @var array
	 */
    protected $config = array(
        'name' => 'Tables',
    	'showPagination' => true,
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
    public function __construct($referentials){
    	$this->referentials = $referentials;
    	
    	$sources = array();
    	if(count($this->referentials)){
	    	foreach($this->referentials as $key=>$referential){
	    		$sources[] = array('name'=>$key);
	    	}
    	}
    	
    	$this->setSource(new InMemorySource($sources));
    }
	
    /**
     * init table columns
     */
    public function init()
    {
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