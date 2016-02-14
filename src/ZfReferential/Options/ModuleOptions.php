<?php
namespace ZfReferential\Options;


use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions {

    /**
     * @var array
     */
    protected $ressources = array();

    /**
     * Get the ressources options
     *
     * @return array
     */
    public function getRessources()
    {
    	return $this->ressources;
    }
    
    /**
     * Set the ressources options
     *
     * @param  array $ressources
     * @return void
     */
    public function setRessources($ressources)
    {
    	$this->ressources = $ressources;
    }
}