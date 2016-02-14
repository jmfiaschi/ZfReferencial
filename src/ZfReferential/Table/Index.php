<?php

namespace ZfReferential\Table;

use Zend\Db\Metadata\Metadata as DbMetadata;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use ZfTable\Source\SourceInterface;
use ZfTable\Source\ArrayAdapter;
use ZfReferential\Collection\RessourceCollectionInterface;
use ZfcBase\Mapper\Exception\InvalidArgumentException;

class Index extends TableAbstract {
	/**
	 *
	 * @var RessourceCollectionInterface $referential
	 */
	protected $referentialList = null;
	
	/**
	 *
	 * @var array
	 */
	protected $config = array (
			'name' => 'Tables',
			'showPagination' => false,
			'showQuickSearch' => false,
			'showItemPerPage' => false,
			'showColumnFilters' => false 
	);
	// Definition of headers
	protected $headers = array (
			'name' => array (
					'title' => 'Name',
					'filters' => 'text' 
			) 
	);
	
	/**
	 *
	 * @param Adapter $adapter        	
	 */
	public function __construct(RessourceCollectionInterface $referentialList = null) {
		if ($referentialList) {
			$this->setReferentialList ( $referentialList );
		}
	}
	
	/**
	 * Set the referentialList
	 *
	 * @param RessourceCollectionInterface $referentialList
	 */
	public function setReferentialList(RessourceCollectionInterface $referentialList) {
		$this->referentialList = $referentialList;
	}
	
	/**
	 * Get the referentialList
	 *
	 * @return \ZfReferential\Collection\RessourceCollectionInterface
	 */
	public function getReferentialList() {
		return $this->referentialList;
	}
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see \ZfTable\AbstractTable::setSource()
	 */
	public function setSource($referentialList) {
		$sources = array ();
		
		if (! ($referentialList instanceof RessourceCollectionInterface)) {
			throw new InvalidArgumentException ( 'The source class need implement RessourceCollectionInterface' );
		}
		
		if ($this->referentialList->count ()) {
			foreach ( $this->referentialList as $key => $referential ) {
				$sources [] = array (
						'name' => $key 
				);
			}
		}
		
		parent::setSource ( $sources );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ZfTable\AbstractTable::getSource()
	 */
	public function getSource() {
		/**
		 * if source null and referentialList nul, we set the source data with referentialList
		 */
		if (! $this->source && ($referentialList = $this->getReferentialList ())) {
			$this->setSource ( $referentialList );
		}
		return parent::getSource ();
	}
	
	/**
	 * init table columns
	 */
	public function init() {
		$this->getHeader ( 'name' )->getCell ()->addDecorator ( 'link', array (
				'url' => '/referential/list/%s',
				'vars' => array (
						'name' 
				) 
		) );
	}
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see \ZfTable\Table\TableAbstract::initFilters()
	 */
	protected function initFilters($source) {
		$headers = $this->getHeaders ();
		if (count ( $headers )) {
			foreach ( $headers as $header ) {
				if (isset ( $header ['filters'] ) && ($value = $this->getParamAdapter ()->getValueOfFilter ( 'name' ))) {
					$source = array_filter ( ( array ) $source, function ($var) {
						return preg_match ( '/^' . $value . '.*/', $var );
					} );
				}
			}
		}
	}
}