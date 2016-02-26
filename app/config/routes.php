<?php


$router = new Phalcon\Mvc\Router(true);

$router->setDefaultModule("frontend");
$router->removeExtraSlashes(TRUE);

$router->add(
	'/:controller/:action[/]{0,1}', 
	array(	
		'module'=>'frontend',
		'controller' => 1,
		'action' => 2,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);
$router->add(
	'/{language:[a-z]{2}}/:controller[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => 2,
		'action' => "index",
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);

$router->add(
	'/{language:[a-z]{2}}/:controller/:action/:int[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => 2,
		'action' => 3,
		'uid'=>4,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);

$router->add(
	'/linkreferer/:int/:int[/]{0,1}', 
	array(		
		'uid' => 1,
		'addressuid'=>2,
		'controller' => "linkreferer",
		'action' => "index",		
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);
$router->add(
	'/linkreferer/:int[/]{0,1}', 
	array(		
		'uid' => 1,		
		'controller' => "linkreferer",
		'action' => "index",		
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/linkreferer/open/:int/:int[/]{0,1}', 
	array(		
		'sendoutobjectuid' => 1,
		'addressuid'=>2,
		'controller' => "linkreferer",
		'action' => "open",		
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/triggersend/generate[/]{0,1}', 
	array(		
		'controller' => "triggersend",
		'action' => "generate",		
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/triggersend/send[/]{0,1}', 
	array(		
		'controller' => "triggersend",
		'action' => "send",		
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/{language:[a-z]{2}}/mailobjects/update/:int[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => "mailobjects",
		'action' => "update",
		'uid' => 2,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/{language:[a-z]{2}}/addressfolders/update/:int[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => "addressfolders",
		'action' => "update",
		'uid' => 2,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/{language:[a-z]{2}}/campaignobjects/update/:int[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => "campaignobjects",
		'action' => "update",
		'uid' => 2,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/mailobjects/update/:int[/]{0,1}', 
	array(	
		'controller' => "mailobjects",
		'action' => "update",
		'uid' => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);

$router->add(
	'/{language:[a-z]{2}}/subscription/unsubscribe/:params[/]{0,1}', 
	array(	
		'controller' => "subscription",
		'action' => "unsubscribe",	
		'language'=>1,
		'email'=>2,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);


$router->add(
		'/configurationobjects/:action[/]{0,1}',
		array(
		'controller' => "configurationobjects",
		'action' => 1,		
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
		)
);

$router->add(
	'/{language:[a-z]{2}}/:controller/:action[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => 2,
		'action' => 3,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);

$router->add(
	'/{language:[a-z]{2}}/configurationobjects/update/:int[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => "configurationobjects",
		'action' => "update",
		'uid' => 2,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers'
	)
);



$router->add(
	'/contentobjects/:action[/]{0,1}', 
	array(	
		'controller' => 'contentobjects',
		'action' => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);

$router->add(
	'/campaignobjects/:action[/]{0,1}', 
	array(	
		'controller' => 'campaignobjects',
		'action' => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);
$router->add(
	'/addressfolders/:action[/]{0,1}', 
	array(	
		'controller' => 'addressfolders',
		'action' => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);

$router->add(
	'/addresses/:action[/]{0,1}', 
	array(	
		'controller' => 'addresses',
		'action' => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);



$router->add(
	'/:controller[/]{0,1}', 
	array(	
		'module'=>'frontend',
		'controller' => 1,
		'action' => "index",
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
	)
);


$router->add(
	'/{language:[a-z]{2}}/', 
	array(
		'language' => 1,
		'controller' => "index",
		'action' => "index",
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
));

$router->add(
    '/',
    array(		
		'controller' => 'index',
		'action'     => 'index',
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);

$router->add(
    '/session/index/',
    array(
		'controller' => 'session',
		'action'     => 'index',
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);

$router->add(
    '/session/start[/]{0,1}',
    array(
       'controller' => 'session',
       'action'     => 'start',
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);

$router->add(
    '/session/logout[/]{0,1}',
    array(
       'controller' => 'session',
       'action'     => 'logout',
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);

$router->add(
    '/testmail/create[/]{0,1}',
    array(
       'controller' => 'testmail',
       'action'     => 'create',
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);
$router->add(
    '/review/update[/]{0,1}',
    array(
       'controller' => 'review',
       'action'     => 'update',
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);



$router->add(
    '/clickconditions/:action[/]{0,1}',
    array(
       'controller' => 'clickconditions',
       'action'     => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);

$router->add(
    '/addressconditions/:action[/]{0,1}',
    array(
       'controller' => 'addressconditions',
       'action'     => 1,
		'module'=>'frontend',
		'namespace'  => 'nltool\Modules\Modules\Frontend\Controllers',
    )
);

$router->add(
	'/backend/{language:[a-z]{2}}/:controller[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => 2,
		'action' => "index",
		'module'=>'backend',
		'namespace'  => 'nltool\Modules\Modules\Backend\Controllers',
	)
);
$router->add(
	'/backend/{language:[a-z]{2}}/:controller/:action[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => 2,
		'action' => 3,		
		'module'=>'backend',
		'namespace'  => 'nltool\Modules\Modules\Backend\Controllers',
	)
);

$router->add(
	'/backend/{language:[a-z]{2}}/:controller/:action/:int[/]{0,1}', 
	array(
		'language' => 1,
		'controller' => 2,
		'action' => 3,
		'uid'=>4,
		'module'=>'backend',
		'namespace'  => 'nltool\Modules\Modules\Backend\Controllers',
	)
);



$router->add(
	'/backend', 
	array(		
		'controller' => 'index',
		'action' => 'index',		
		'module'=>'backend',
		'namespace'  => 'nltool\Modules\Modules\Backend\Controllers',
	)
);



$router->handle();
return $router;