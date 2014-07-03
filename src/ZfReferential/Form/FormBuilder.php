<?php 
namespace ZfReferential\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Form\Element\DateTime;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\ArrayUtils;
use Zend\Code\Reflection\PropertyReflection;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory;
use Zend\Form\Fieldset;

class FormBuilder extends AnnotationBuilder
{	
	const BUTTON_SAVE = "save";
	
	const BUTTON_CANCEL = "cancel";
	
	public function createForm($entity){
		$form = parent::createForm($entity);
		$hydrator = new ObjectProperty();
		$properties = $hydrator->extract($entity);
		
		$form->add(array(
			'name' => self::BUTTON_SAVE,
			'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
            	'type'  => 'submit',
                'value' => 'Save',
             ),
			'options' => array(
				'label' => 'Save',
				'allowValueBinding'	=>	false
			),
		));
		
		$form->add(array(
			'name' => self::BUTTON_CANCEL,
			'type' => 'Zend\Form\Element\Button',
			'attributes' => array(
				'type'  => 'submit',
				'value' => 'Cancel',
			),
			'options' => array(
				'label' => 'Cancel',
				'allowValueBinding'	=>	false
			),
		));
		
		$form->add(array(
			'name' => 'csrf',
			'type' => 'Zend\Form\Element\Csrf',
				
		));
		
		return $form;
	}
}