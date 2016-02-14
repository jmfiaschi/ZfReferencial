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
            'modules.zf-referential' => array(
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
            		'edit' => array(
            			'type' => 'segment',
            			'options' => array(
            				'route' => '/edit/[:name]',
            				'constraints' => array(
            					'name' => '[a-zA-Z0-9_-]+'
            				),
            				'defaults' => array(
            					'action'     => 'edit',
            				)
            			)
            		),
            		'delete' => array(
            			'type' => 'segment',
            			'options' => array(
            				'route' => '/delete/[:name]',
            				'constraints' => array(
            					'name' => '[a-zA-Z0-9_-]+'
            				),
            				'defaults' => array(
            					'action'     => 'delete',
            				)
            			)
            		),
            		'add' => array(
            			'type' => 'segment',
            			'options' => array(
            				'route' => '/add/[:name]',
            				'constraints' => array(
            					'name' => '[a-zA-Z0-9_-]+'
            				),
            				'defaults' => array(
            					'action'     => 'add',
            				)
            			)
            		)
            	)
            ),
        ),
    ),
	'assets_bundle' => array(
		'recursiveSearch' => true,
		'assets' => array(
			'ZfReferential' => array(
				'js' => array(
					'js/jquery.dataTables.min.js',
					'js/zf-table.js',
					'js/zf-referential.js'
				),
				'css'	=> array(
					'css/zf-table/zf-table.css'
				)
			)
		)
	),
);
