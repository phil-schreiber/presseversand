<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller as Controller,
	Phalcon\Mvc\Dispatcher,
	nltool\Models\Linklookup,
	nltool\Models\Linkclicks,
	nltool\Models\Openclicks;


class LinkrefererController extends Controller
{
	
	
	public function beforeExecuteRoute(Dispatcher $dispatcher)
	{
			$actionName=$this->dispatcher->getActionName();
			if($actionName=="index"){
				$callUrl=$_SERVER['REQUEST_URI'];
				$urlArray=explode('?',$callUrl);
				$params='';
				if(count($urlArray)>1){
					$params='?'.$urlArray[1];
				}
				$time=time();
				$linklookupUid = $this->dispatcher->getParam("uid");
				$LinklookupRecord = Linklookup::findFirst(array(
				"conditions" => "uid = ?1",
				"bind" => array(1 => $linklookupUid)
				));

				header('Location: '.$LinklookupRecord->url.$params); 
				$linkClick=new Linkclicks();
				$linkClick->assign(array(
					'pid' =>0,
					'deleted'=>0,
					'hidden' => 0,
					'tstamp' => $time,				
					'crdate' => $time,
					'url' => $LinklookupRecord->url.$params,
					'campaignuid' => $LinklookupRecord->campaignuid,
					'mailobjectuid' => $LinklookupRecord->mailobjectuid,
					'sendoutobjectuid' => $LinklookupRecord->sendoutobjectuid,
					'linkuid' => $this->dispatcher->getParam("uid"),
					'addressuid' => $this->dispatcher->getParam("addressuid")
				));
				$linkClick->save();
			
			die();
			} elseif($actionName=='open') {
				
				$time=time();
				header('Content-type: image/gif');
				readfile('images/blank.gif');
				$linkClick=new Openclicks();
				$linkClick->assign(array(
					'pid' =>0,
					'deleted'=>0,
					'hidden' => 0,
					'tstamp' => $time,				
					'crdate' => $time,
					'sendoutobjectuid' => $this->dispatcher->getParam("sendoutobjectuid"),
					'addressuid' => $this->dispatcher->getParam("addressuid")
				));
				$linkClick->save();
				die();
				
			}
			
	}
	public function indexAction(){
		
	}
	
	public function openAction(){		
		
	}
	
	
}