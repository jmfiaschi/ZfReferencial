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
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;

class ObjectEditTable extends ObjectTable
{

    /**
     *
     * @var string
     */
    const HEADER_EDIT_KEY = 'edit';

    /**
     *
     * @var string
     */
    const HEADER_DELETE_KEY = 'delete';

    /**
     * (non-PHPdoc)
     * 
     * @see \ZfReferential\Table\ObjectTable::init()
     */
    public function init()
    {
        parent::init();
        
        if (count($this->getHeaders())) {
            $filter = new FilterChain();
            $filter->attach(new StringToLower());
            
            $this->getHeader(self::HEADER_EDIT_KEY)
                ->getCell()
                ->addDecorator('template', array(
                'template' => '<a class="btn btn-primary btn-sm edit" href="/referential/edit/' . $filter->filter($this->getName()) . '?' . implode('=%s&', $this->getFieldList()) . '=%s"><span class="glyphicon glyphicon-pencil"></span></a>',
                'vars' => $this->getFieldList()
            ));
            $this->getHeader(self::HEADER_DELETE_KEY)
                ->getCell()
                ->addDecorator('template', array(
                'template' => '<a class="btn btn-danger btn-sm delete" href="/referential/delete/' . $filter->filter($this->getName()) . '?' . implode('=%s&', $this->getFieldList()) . '=%s"><span class="glyphicon glyphicon-remove-circle"></span></a>',
                'vars' => $this->getFieldList()
            ));
        }
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \ZfReferential\Table\ObjectTable::setDataList()
     */
    public function setDataList($dataList)
    {
        parent::setDataList($dataList);
        
        /**
         * set edit and delete field
         */
        if (count($this->getHeaders())) {
            $headers = $this->getHeaders();
            $headers[self::HEADER_EDIT_KEY] = array(
                'title' => $this->getTranslate()->translate(self::HEADER_EDIT_KEY)
            );
            $headers[self::HEADER_DELETE_KEY] = array(
                'title' => $this->getTranslate()->translate(self::HEADER_DELETE_KEY)
            );
            $this->setHeaders($headers);
        }
        
        return $this;
    }
}
