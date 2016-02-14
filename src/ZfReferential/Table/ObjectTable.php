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
use Zend\Stdlib\ArrayObject;

class ObjectTable extends TableAbstract
{

    /**
     *
     * @var array $config
     */
    protected $config = array(
        'name' => '',
        'showPagination' => true,
        'showQuickSearch' => true,
        'showItemPerPage' => true,
        'showColumnFilters' => true
    );

    /**
     *
     * @var HydratorAwareInterface $hydrator
     */
    protected $hydrator = null;

    /**
     *
     * @var mixed $objectPrototype
     */
    protected $objectPrototype = null;

    /**
     *
     * @var array $fieldList
     */
    protected $fieldList = null;

    /**
     *
     * @param HydratorAwareInterface $hydrator            
     */
    public function __construct($hydrator = null)
    {
        if (! $hydrator) {
            $this->hydrator = new ObjectProperty();
        }
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        if ($this->getObjectPrototype()) {
            $name = array_reverse(explode('\\', get_class($this->getObjectPrototype())));
            return $name[0];
        }
        return null;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \ZfReferential\Table\TableAbstract::init()
     */
    public function init()
    {
        if ($this->config instanceof \Countable) {
            $this->config['name'] = $this->getName();
        } elseif ($this->config instanceof \ZfTable\Options\ModuleOptions) {
            $this->config->setName($this->getName());
        }
        
        parent::init();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \ZfTable\AbstractTable::initFilters()
     */
    protected function initFilters($arrayData)
    {
        parent::initFilters($arrayData);
        
        /**
         * Filter element
         */
        $keys = array();
        if ($this->getFieldList()) {
            foreach ($arrayData as $key => $row) {
                foreach ($this->getFieldList() as $fieldName) {
                    if (($value = $this->getParamAdapter()->getValueOfFilter($fieldName)) && strpos($row[$fieldName], $value) === false && ! isset($keys[$key])) {
                        $keys[] = $key;
                    }
                }
            }
        }
        foreach ($keys as $key) {
            unset($arrayData[$key]);
        }
    }

    /**
     *
     * @param array $dataList            
     * @return \ZfReferential\Table\Lister
     */
    public function setDataList($dataList)
    {
        /**
         * set fieldList
         */
        if ($this->getObjectPrototype()) {
            $this->setFieldList(array_keys($this->hydrator->extract($this->getObjectPrototype())));
        } elseif (count($dataList)) {
            $this->setFieldList(array_keys($this->hydrator->extract($dataList[0])));
        } else {
            throw new InvalidArgumentException('You need specify an object prototype');
        }
        
        /**
         * set title of columns
         */
        if (count($this->getFieldList())) {
            foreach ($this->getFieldList() as $fieldName) {
                $headers[$fieldName] = array(
                    'title' => $this->getTranslate()->translate($fieldName),
                    'filters' => 'text'
                );
            }
            $this->setHeaders($headers);
        } else {
            throw new InvalidArgumentException("This object don't have public properties");
        }
        
        if (count($dataList)) {
            $this->setSource(array_map(function ($element)
            {
                return $this->hydrator->extract($element);
            }, $dataList));
        } else {
            $this->setSource(array());
        }
        
        return $this;
    }

    /**
     *
     * @param unknown $hydrator            
     * @return \ZfReferential\Table\Lister
     */
    public function setHydrator($hydrator)
    {
        $this->hydrator = $hydrator;
        
        return $this;
    }

    /**
     * Get the hydrator use to convert $objectPrototype
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * get the object prototype
     *
     * @return \ZfReferential\Table\mixed
     */
    public function getObjectPrototype()
    {
        return $this->objectPrototype;
    }

    /**
     * set the object prototype
     *
     * @param unknown $objectPrototype            
     * @return \ZfReferential\Table\ObjectTable
     */
    public function setObjectPrototype($objectPrototype)
    {
        $this->objectPrototype = $objectPrototype;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getFieldList()
    {
        return $this->fieldList;
    }

    /**
     *
     * @param array $fieldList            
     * @return \ZfReferential\Table\ObjectTable
     */
    public function setFieldList(array $fieldList)
    {
        $this->fieldList = $fieldList;
        return $this;
    }
}
