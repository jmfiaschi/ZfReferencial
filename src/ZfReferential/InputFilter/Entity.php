<?php
namespace ZfReferential\InputFilter;

use Zend\InputFilter\Factory as InputFactory;    
use Zend\InputFilter\InputFilter;                 
use Zend\InputFilter\InputFilterAwareInterface;   
use Zend\InputFilter\InputFilterInterface;       

class Entity extends InputFilter implements InputFilterAwareInterface
{
	/**
	 * 
	 * @var Adapter
	 */
	protected $adapter = null;
	
	/**
	 *
	 * @var Metadata
	 */
	protected $metadata;
	
	/**
	 *
	 * @var string
	 */
	protected $entityName = '';
	
	public function __construct($adapter, $entityName = null){
		$this->adapter = $adapter;
		$this->entityName = $entityName;
	}
	
	public function setName($entityName){
		$this->entityName = $entityName;
	}

    // Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
    	$this->metadata = new Metadata($this->adapter);
    	if( !$this->metadata ){
    		throw new \Zend\Mvc\Exception\InvalidArgumentException("This resource name $entityName don't exist",404);
    	}
    	$this->entity = $this->metadata->getTable($this->entityName);
    	
    	$constraints = $this->entity->getConstraints();
    	$primaries = array();
    	if(count($constraints)){
    		foreach($constraints as $constraint){
    			if($constraint->getType() == 'PRIMARY KEY'){
    				$primaries = $constraint->getColumns();
    			}
    		}
    	}
    	
    	$factory     = new InputFactory();

		if(count($this->entity->getColumns())){
			foreach($this->entity->getColumns() as $key=>$column){
				/* @var $column \Zend\Db\Metadata\Object\ColumnObject */
				$inputFilter = array(
					'name' => $column->getName(),
					'tabindex' =>$key,
					'filters' => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
							array('name' => 'StringToLower'),
					),
					'options' => array(
							'label' => $column->getName(),
					),
					'attributes'=>array(),
					'validators'=> array()
				);
		
				if(!$column->getIsNullable()){
					$inputFilter['required'] = true;
				}else{
					$inputFilter['required'] = false;
				}
		
				switch ($column->getDataType()){
					case 'tinyint':
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0;
							$inputFilter['attributes']['max'] = 65535;
						}else{
							$inputFilter['attributes']['min'] = -32768;
							$inputFilter['attributes']['max'] = 32767;
						}
					case 'smallint':
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0;
							$inputFilter['attributes']['max'] = 255;
						}else{
							$inputFilter['attributes']['min'] = -128;
							$inputFilter['attributes']['max'] = 127;
						}
					case 'mediumint':
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0;
							$inputFilter['attributes']['max'] = 16777215;
						}else{
							$inputFilter['attributes']['min'] = -8388608;
							$inputFilter['attributes']['max'] = 8388607;
						}
					case 'int':
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0;
							$inputFilter['attributes']['max'] = 4294967295;
						}else{
							$inputFilter['attributes']['min'] = -2147483648;
							$inputFilter['attributes']['max'] = 2147483647;
						}
					case 'bigint':
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0;
							$inputFilter['attributes']['max'] = 18446744073709551615;
						}else{
							$inputFilter['attributes']['min'] = -9223372036854775808;
							$inputFilter['attributes']['max'] = 9223372036854775807;
						}
						$inputFilter['attributes']['type'] ='number';
						$inputFilter['filters'][] = array('name' => 'int');
						break;
					case 'bit':
						$inputFilter['validators'][] = array('name' => 'Regex','pattern'=>'/[0-1]*/i');
						break;
					case 'numeric':
						$inputFilter['validators'][] = array('name' => 'Digits');
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0;
						}
						break;
					case 'decimal':
					case 'double':
					case 'float':
					case 'real':
						$inputFilter['validators'][] = array('name' => 'Float');
						$inputFilter['filters'][] = array('name' => 'LocalizedToNormalized');
						if($column->getNumericUnsigned()){
							$inputFilter['attributes']['min'] = 0.00;
						}
						break;
					case 'bool':
					case 'boolean':
						$inputFilter['filters'][] = array('name' => 'Boolean');
						break;
					case 'binary':
					case 'char':
					case 'varchar':
					case 'tinytext':
					case 'text':
					case 'longtext':
						$inputFilter['validators'][] = array('name' => 'StringLength','max'=>$column->getCharacterMaximumLength());
						break;
					case 'tinyblob':
					case 'blob':
					case 'mediumblob':
					case 'longblob':
						$inputFilter['validators'][] = array('name' => 'FileSize','max'=>$column->getCharacterMaximumLength());
						break;
					case 'date':
						$inputFilter['attributes']['type'] ='date';
						break;
					case 'datetime':
						$inputFilter['attributes']['type'] ='datetime-local';
						$inputFilter['validators'][] = array('name' => 'DateStep');
						break;
					case 'time':
					case 'timestamp':
						$inputFilter['validators'][] = array('name' => 'Digits');
						$inputFilter['attributes']['min'] = 0.00;
						break;
					case 'enum':
						$inputFilter['options']['value_options'] = $column->getErrata('permitted_values');
						$inputFilter['validators'][] = array('name' => 'InArray','haystack'=>$column->getErrata('permitted_values'));
						$inputFilter['validators'][] = array('name' => 'StringLength','max'=>$column->getCharacterMaximumLength());
						break;
				}
		
				$this->add($inputFilter);
			}
		}

        return $this->inputs;
    }
}