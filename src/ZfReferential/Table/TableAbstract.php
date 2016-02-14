<?php
namespace ZfReferential\Table;

use ZfTable\Table\TableInterface;
use ZfTable\Params\AdapterInterface as ParamAdapterInterface;
use ZfTable\Params\AdapterArrayObject;
use ZfTable\Table\Exception;
use ZfTable\Options\ModuleOptions;
use ZfTable\Source\SourceInterface as SourceInterface;
use ZfTable\Example\TableExample\Mix;
use ZfTable\AbstractElement;
use ZfTable\Table\Exception\LogicException;
use ZfTable\Table\Exception\RuntimeException;
use ZfTable\Header;
use ZfTable\Render;
use ZfTable\Decorator\Exception\InvalidArgumentException;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\I18n\Translator\TranslatorInterface;

abstract class TableAbstract extends \ZfTable\AbstractTable
{

    /**
     *
     * @var Translate $translate
     */
    protected $translate = null;

    /**
     *
     * @param TranslatorInterface $translate            
     */
    public function setTranslate(TranslatorInterface $translate)
    {
        $this->translate = $translate;
    }

    /**
     *
     * @return I18nTranslatorInterface $translate
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    /**
     *
     * @param string $var            
     */
    public function translate($var)
    {
        return $this->translate->translate($var);
    }

    /**
     *
     * @param string $var            
     */
    public function _($var)
    {
        return $this->translate($var);
    }

    public function init()
    {
        if (count($this->headersObjects)) {
            foreach ($this->headersObjects as $header) {
                $header->getCell()->addDecorator('callable', array(
                    'callable' => function ($value, $row)
                    {
                        return $this->_($value);
                    }
                ));
            }
        }
    }
}