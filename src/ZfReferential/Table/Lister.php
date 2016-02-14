<?php

/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2015
 * @license   MIT License 
 */
namespace ZfReferential\Table;

use ZfReferential\Collection\RessourceCollectionInterface;

class Lister extends ObjectEditTable {
	/**
	 *
	 * @var RessourceCollectionInterface $referential
	 */
	protected $referentialList = null;
	
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
}
