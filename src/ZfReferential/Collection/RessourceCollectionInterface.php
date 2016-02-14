<?php
namespace ZfReferential\Collection;

/**
 * @author Jean-Marc
 *
 */
Interface RessourceCollectionInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{
    
    public function toArray();
    
    /**
     * Get all values of the collection
     */
    public function getValues();

    /**
     * Get all keys of the collection
     */
    public function getKeys();
    
    /**
     * 
     * @param unknown $key
     */
    public function get($key);
    
    /**
     * 
     * @param string $key
     * @param unknown $value
     */
    public function set($key, $value);
}
