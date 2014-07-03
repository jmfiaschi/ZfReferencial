<?php
/**
 * RoleOptions.php file
 * 
 * PHP Version 5
 * 
 * @category   ${category}
 * @package    Inventis
 * @subpackage Bricks
 * @author     Inventis Web Architects <info@inventis.be>
 * @license    Copyright © Inventis BVBA  - All rights reserved
 * @link       https://github.com/Inventis/Bricks
 */


namespace ZfReferential\Options;


use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions {

    /**
     * @var array
     */
    protected $referentials = array();

    /**
     * Get the referentials options
     *
     * @return array
     */
    public function getReferentials()
    {
    	return $this->referentials;
    }
    
    /**
     * Set the referentials options
     *
     * @param  array $referentials
     * @return void
     */
    public function setReferentials($referentials)
    {
    	$this->referentials = $referentials;
    }
}