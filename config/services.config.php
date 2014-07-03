<?php 
return array(
	'factories' => array(
		'ZfReferential\Options\ModuleOptions' 	=> 'ZfReferential\Factory\ModuleOptionsFactory',
		'ZfReferential\Table\Index'				=> 'ZfReferential\Factory\IndexTableFactory',
		'ZfReferential\Table\Lister' 			=> 'ZfReferential\Factory\ListerTableFactory',
		'ZfReferential\Form\FormBuilder' 		=> 'ZfReferential\Factory\FormBuilderFactory'
	)
);