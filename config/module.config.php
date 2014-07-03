<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'ZfReferential' => __DIR__ . '/../view',
        ),
    ),
	'translator' => array(
		'locale' => 'fr_FR',
		'translation_file_patterns' => array(
			array(
				'type'     => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.mo',
			),
		),
	),
    'router' => array(
        'routes' => array(
            'referential' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/referential',
                    'defaults' => array(
                        'controller' => 'ZfReferential\Controller\ReferentialController',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
            	'child_routes' => array(
            		// Segment route for viewing one blog post
            		'list' => array(
            			'type' => 'segment',
            			'options' => array(
            				'route' => '/list/[:name]',
            				'constraints' => array(
            					'name' => '[a-zA-Z_-]+'
            				),
            				'defaults' => array(
            					'action'     => 'list',
            				)
            			)
            		),
            		// Segment route for viewing one blog post
            		'edit' => array(
            			'type' => 'segment',
            			'options' => array(
            				'route' => '/edit/[:name]',
            				'constraints' => array(
            					'name' => '[a-zA-Z0-9_-]+',
            					'ids' => '[0-9-]*'
            				),
            				'defaults' => array(
            					'action'     => 'edit',
            				)
            			)
            		)
            	)
            ),
        ),
    )
);
