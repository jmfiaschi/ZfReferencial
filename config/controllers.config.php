<?php 
use Zend\Mvc\Controller\ControllerManager;
use ZfReferential\Controller\ReferentialController;

return array(
	'factories' => array(
		'ZfReferential' => function(ControllerManager $cm) {
			$sm   = $cm->getServiceLocator();
			$adapter = $sm->get('zf_referential_adapter');
			return new ReferentialController($adapter);
		},
	),
);