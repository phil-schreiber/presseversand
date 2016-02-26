<?php

namespace nltool\Modules\Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller as Controller,
	Phalcon\Mvc\Dispatcher;
use \Exception;


class Triggerauth extends Controller
{
	
	
		public function beforeExecuteRoute(Dispatcher $dispatcher)
	{
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
				$baseUri=$this->config['application'][$environment]['staticBaseUri'];
				$this->view->setVar('baseurl', $baseUri);
				
			try {
				
				$client_id = $this->request->getQuery('client_id' );
				$client_secret=$this->request->getQuery('client_secret');
				if(!($client_id=='sendtrigger' && $client_secret=='X4lPahQud43tfojn')){
					throw new Exception('Auth failed');
				}
				//$result=$this->oauth->getGrantType('client_credentials')->completeFlow($params);
				
				
					
				
			}catch (\Exception $e) {
				
				echo $e->getMessage();
				//$this->response->sendHeaders();
				return false;
			} 
				
			/*try {
				
				$params = $this->oauth->getParam(array('client_id', 'client_secret'));
				
				$result=$this->oauth->getGrantType('client_credentials')->completeFlow($params);
				
				var_dump($result);
					
				
			} catch (\League\OAuth2\Server\Exception\ClientException $e) {
				
				echo $e->getMessage();
				
				echo('<img src="'.$baseUri.'images/cowboy-shaking-head.gif" style="position:absolute;top:40%;left:40%;">');
				$this->response->sendHeaders();
				return false;
			} catch (\Exception $e) {
				
				echo $e->getTraceAsString();
				$this->response->sendHeaders();
				return false;
			}*/
			
			$this->view->setVar('controller', '');
		$this->view->setVar('language', '');
		
	}
}