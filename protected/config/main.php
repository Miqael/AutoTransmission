<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'Real Body Shop',
    'defaultController'=>'admin',
	// preloading 'log' component
//	'preload'=>array('log'),
    'preload' => array('log', 'session', 'db', 'cache'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'ext.giix-components.*', // giix components
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
            'class' => 'system.gii.GiiModule',
            'generatorPaths' => array(
                'ext.giix-core', // giix generators
            ),
			'password'  => 'asdasd',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array('127.0.0.1','::1'),
		),
        'account'=>array(
            'defaultController'=>'account',
        ),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'loginUrl' => array('/account/account/login'),
		),
        'request'=>array(
            'enableCookieValidation'=>true,
            'enableCsrfValidation'=>false,
        ),
		// uncomment the following to enable URLs in path-format
        /**/

		'urlManager'=>array(
            'class'=>'application.components.UrlManager',
			'urlFormat'         => 'path',
            'showScriptName'    => false,
            'caseSensitive'     => false,
			'rules' => array(
				'/' => 'admin/index',
//				'<language:(en|es)>/' => 'admin/index',
//                '<language:(en|es)>/<action:(contact|login|logout)>/*' => 'admin/<action>',
//                '<language:(en|es)>/<controller:\w+>/<id:\d+>'=>'<controller>/view',
//                '<language:(en|es)>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
//                '<language:(en|es)>/<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
			),
			//'rules' => array(
			//	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
			//	'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			//	'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			//),
		),
        /**/

        /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
        */
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=real_body_shop',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'admin/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@loseparts.com',
        'languages'=>array('en'=>'English'),
	),

    'behaviors' => array(
        'onBeginRequest' => array(
            'class'  => 'application.components.behaviors.LanguageBehavior'
        ),
    ),

    'sourceLanguage'=>'en',
    'language'=>'en',
);