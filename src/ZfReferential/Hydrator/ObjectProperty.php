<?php
namespace ZfReferential\Hydrator;

use Zend\Stdlib\Exception;
use Zend\Stdlib\Hydrator\ObjectProperty as ZendObjectProperty;

class ObjectProperty extends ZendObjectProperty
{
    public function hydrate(array $data, $object)
    {
        if (!is_object($object)) {
            throw new Exception\BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)', __METHOD__
            ));
        }
        unset($data['save']);
        unset($data['cancel']);
        unset($data['csrf']);
        
        foreach ($data as $name => $value) {
            $property = $this->hydrateName($name, $data);
            $object->$property = $this->hydrateValue($property, $value, $data);
        }
        return $object;
    }
}
