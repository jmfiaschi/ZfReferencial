<?php
namespace ZfReferential\Collection;

use ArrayIterator;
use Closure;

/**
 * @author Jean-Marc
 *
 */
class RessourceCollection implements RessourceCollectionInterface
{
	/**
	 * An array containing the entries of this collection.
	 *
	 * @var array
	 */
	private $elements;
	
	/**
	 * 
	 * @var unknown
	 */
	private $serviceLocator;
	
	/**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }
    
    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
    	return $this->elements;
    }
    
    /**
     * {@inheritDoc}
     */
    public function first()
    {
    	return reset($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function last()
    {
    	return end($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function key()
    {
    	return key($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function next()
    {
    	return next($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function current()
    {
    	return current($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
    	if ( ! isset($this->elements[$key]) && ! array_key_exists($key, $this->elements)) {
    		return null;
    	}
    
    	$removed = $this->elements[$key];
    	unset($this->elements[$key]);
    
    	return $removed;
    }
    
    /**
     * {@inheritDoc}
     */
    public function removeElement($element)
    {
    	$key = array_search($element, $this->elements, true);
    
    	if ($key === false) {
    		return false;
    	}
    
    	unset($this->elements[$key]);
    
    	return true;
    }
    
    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
    	return $this->containsKey($offset);
    }
    
    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
    	return $this->get($offset);
    }
    
    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
    	if ( ! isset($offset)) {
    		return $this->add($value);
    	}
    
    	$this->set($offset, $value);
    }
    
    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
    	return $this->remove($offset);
    }
    
    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
    	return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
    	return in_array($element, $this->elements, true);
    }
    
    /**
     * {@inheritDoc}
     */
    public function exists(Closure $p)
    {
    	foreach ($this->elements as $key => $element) {
    		if ($p($key, $element)) {
    			return true;
    		}
    	}
    
    	return false;
    }
    
    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
    	return array_search($element, $this->elements, true);
    }
    
    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
    	return isset($this->elements[$key]) ? $this->getServiceLocator()->get($this->elements[$key]) : null;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
    	return array_keys($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
    	return array_values($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function count()
    {
    	return count($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
    	$this->elements[$key] = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
    	return empty($this->elements);
    }
    
    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritDoc}
     */
    public function getIterator()
    {
    	return new ArrayIterator($this->elements);
    }
    
    /**
     * {@inheritDoc}
     */
    public function map(Closure $func)
    {
    	return new static(array_map($func, $this->elements));
    }
    
    /**
     * {@inheritDoc}
     */
    public function filter(Closure $p)
    {
    	return new static(array_filter($this->elements, $p));
    }
    
	public function getServiceLocator() {
		return $this->serviceLocator;
	}
	
	public function setServiceLocator($serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}
	
}
