<?php 
use ZfReferential\Table\Metadata;
use ZfReferential\Table\Entity as EntityTable;
use ZfReferential\Form\Entity as EntityForm;
use Zend\Form\Factory;
use Zend\Form\FormElementManager;

return array(
	'aliases'	=>	array(
		'zf_referential_adapter'	=>	'Zend\Db\Adapter\Adapter'
	),
	'factories' => array(
		'ZfReferential\Table\Metadata' => function($serviceManager) {
			$metadataTable = new Metadata($serviceManager->get('zf_referential_adapter'));
			$metadataTable->setTranslate($serviceManager->get('translator'));
			return $metadataTable;
		},
		'ZfReferential\Table\Entity' => function($serviceManager) {
			$EntityTable = new EntityTable($serviceManager->get('zf_referential_adapter'));
			$EntityTable->setTranslate($serviceManager->get('translator'));
			return $EntityTable;
		},
		'ZfReferential\Form\Entity' => function($serviceManager) {
			$form = new EntityForm(null,null,$serviceManager->get('zf_referential_adapter'));
			return $form;
		}
	)
);