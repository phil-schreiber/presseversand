<?php


$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces(
    array(
		'nltool\Models'        => $this->config->application->modelsDir,
		'nltool\Forms'        => $this->config->application->formsDir,		
		'nltool\Controllers'   => $this->config->application->controllersDir,
		'nltool\Modules\Modules\Frontend'=>$this->config->application->frontendDir,
		'nltool\Modules\Modules\Frontend\Controllers'=>$this->config->application->frontendControllersDir,
		'nltool\Modules\Modules\Backend'=>$this->config->application->backendDir,
		'nltool\Modules\Modules\Backend\Controllers'=>$this->config->application->backendControllersDir,
		'nltool\app' => $this->config->application->appsDir,
		'nltool' => $this->config->application->libraryDir,	
		'Sum' => $this->config->application->libraryDir
       
    )
);

 $loader->registerDirs(array(
        $this->config->application->controllersDir,
        $this->config->application->modelsDir
    ));

$loader->register();