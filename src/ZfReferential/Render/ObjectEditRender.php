<?php

/**
 * ZfTable ( Module for Zend Framework 2)
 *
 * @copyright Copyright (c) 2013 Piotr Duda dudapiotrek@gmail.com
 * @license   MIT License 
 */
namespace ZfReferential\Render;

use Zend\View\Resolver;
use Zend\View\Renderer\PhpRenderer;
use ZfTable\Options\ModuleOptions;
use ZfTable\Render;
use ZfReferential\Table\ObjectEditTable;

class ObjectEditRender extends Render
{

    /**
     * (non-PHPdoc)
     *
     * @see \ZfTable\Render::renderTableAsHtml()
     */
    public function renderTableAsHtml()
    {
        $render = '';
        $tableConfig = $this->getTable()->getOptions();
        
        if ($tableConfig->getShowColumnFilters()) {
            $render .= $this->renderFilters();
        }
        
        $header = sprintf('<thead>%s</thead>', $this->renderHead());
        $body = $this->getTable()
            ->getRow()
            ->renderRows();
        $footer = sprintf('<tfoot>%s</tfoot>', $this->renderFoot());
        $table = sprintf('<table %s>%s</table>', $this->getTable()->getAttributes(), $header . $body . $footer);
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setTemplate('container');
        
        $view->setVariable('table', $table);
        
        $view->setVariable('paginator', $this->renderPaginator());
        $view->setVariable('paramsWrap', $this->renderParamsWrap());
        $view->setVariable('itemCountPerPage', $this->getTable()
            ->getParamAdapter()
            ->getItemCountPerPage());
        $view->setVariable('quickSearch', $this->getTable()
            ->getParamAdapter()
            ->getQuickSearch());
        $view->setVariable('name', $tableConfig->getName());
        $view->setVariable('itemCountPerPageValues', $tableConfig->getValuesOfItemPerPage());
        $view->setVariable('showQuickSearch', $tableConfig->getShowQuickSearch());
        $view->setVariable('showPagination', $tableConfig->getShowPagination());
        $view->setVariable('showItemPerPage', $tableConfig->getShowItemPerPage());
        $view->setVariable('showExportToCSV', $tableConfig->getShowExportToCSV());
        
        return $this->getRenderer()->render($view);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \ZfTable\Render::renderHead()
     */
    public function renderHead()
    {
        $render = parent::renderHead();
        
        $this->getTable()
            ->getRow()
            ->setActualRow($this->getTable()
            ->getHydrator()
            ->extract($this->getTable()
            ->getObjectPrototype()));
        
        foreach ($this->getTable()->getHeaders() as $name => $options) {
            if ($name == ObjectEditTable::HEADER_EDIT_KEY) {
                $render .= $this->getTable()
                    ->getHeader($name)
                    ->getCell()
                    ->render('html');
            } else {
                $render .= '<td>&nbsp;</td>';
            }
        }
        
        return $render;
    }

    /**
     * render footer
     */
    public function renderFoot()
    {
        $render = '';
        
        if ($this->getTable()
            ->getData()
            ->count()) {
            $this->getTable()
                ->getRow()
                ->setActualRow($this->getTable()
                ->getHydrator()
                ->extract($this->getTable()
                ->getObjectPrototype()));
            
            foreach ($this->getTable()->getHeaders() as $name => $options) {
                if ($name == ObjectEditTable::HEADER_EDIT_KEY) {
                    $render .= $this->getTable()
                        ->getHeader($name)
                        ->getCell()
                        ->render('html');
                } else {
                    $render .= '<td>&nbsp;</td>';
                }
            }
        }
        
        return $render;
    }
}

