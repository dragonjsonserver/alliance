<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

/**
 * @return array
 */
return [
	'dragonjsonserveralliance' => [
		'taglength' => [
			'min' => '1',
			'max' => '5',
		],
		'namelength' => [
			'min' => '3',
			'max' => '255',
		],
	    'roles' => ['applicant', 'member', 'leader'],
	],
	'dragonjsonserver' => [
	    'apiclasses' => [
	        '\DragonJsonServerAlliance\Api\Alliance' => 'Alliance',
	        '\DragonJsonServerAlliance\Api\Allianceavatar' => 'Allianceavatar',
	    ],
	],
	'service_manager' => [
		'invokables' => [
            '\DragonJsonServerAlliance\Service\Alliance' => '\DragonJsonServerAlliance\Service\Alliance',
            '\DragonJsonServerAlliance\Service\Allianceavatar' => '\DragonJsonServerAlliance\Service\Allianceavatar',
		],
	],
	'doctrine' => [
		'driver' => [
			'DragonJsonServerAlliance_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [
					__DIR__ . '/../src/DragonJsonServerAlliance/Entity'
				],
			],
			'orm_default' => [
				'drivers' => [
					'DragonJsonServerAlliance\Entity' => 'DragonJsonServerAlliance_driver'
				],
			],
		],
	],
];
