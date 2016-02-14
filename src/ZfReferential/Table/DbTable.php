<?php

/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2015
 * @license   MIT License 
 */
namespace ZfReferential\Table;

use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Text\Table\Exception\InvalidArgumentException;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Server\Reflection\ReflectionParameter;
use Zend\Code\Reflection\ClassReflection;
use ZfcDB\Mapper\MapperInterface;

class DBTable extends ObjectTable {
	
	/**
	 *
	 * @var mixed $objectMapper
	 */
	protected $objectMapper = null;
	
	/**
	 *
	 * @param MapperInterface $objectMapper       	
	 */
	public function __construct(MapperInterface $objectMapper = null) {
		if (! $objectMapper) {
			$this->objectMapper = new ObjectProperty ();
		}
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \ZfTable\DoctrineTable::initFilters()
	 */
	protected function initFilters($arrayData) {
		parent::initFilters($arrayData);
		
	}
}
