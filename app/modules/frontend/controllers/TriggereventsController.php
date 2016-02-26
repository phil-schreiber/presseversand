<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Sendoutobjects,
	nltool\Models\Triggerevents,
	nltool\Models\Mailobjects,
	nltool\Models\Configurationobjects,
	nltool\Models\Distributors,
	nltool\Models\Addressfolders;

/**
 * Class IndexController
 *
 * @package baywa-nltool\Controllers
 */
class TriggereventsController extends ControllerBase
{
	private $events=array(
		/*1 => 'date',
		2 => 'recursive',*/
		3 => 'birthday',
		4 => 'subscribe',
		5 => 'unsubscribe'
	);
	public function indexAction()
	{
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language.'/triggerevents/update/';
		$triggerevents=Triggerevents::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));

		$this->view->setVar('triggerevents',$triggerevents);
		$this->view->setVar('path',$path);
		
	}
	
	public function createAction()
	{			
		if($this->request->isPost()){
			$time = time();
			
			$dateArr=explode(' ',$this->request->hasPost('sendoutdate') ? $this->request->getPost('sendoutdate') : '');
			$senddate=0;
			if(count($dateArr)>1){
				$dateTimeArr=explode(':',$dateArr[1]);
				$dateDataArr=explode('/',$dateArr[0]);
				$senddate=mktime($dateTimeArr[0],$dateTimeArr[1],0,$dateDataArr[1],$dateDataArr[2],$dateDataArr[0]);
			}
			
			$triggerevent=new Triggerevents();
			$triggerevent->assign(array(
				'pid' => 0,
				'tstamp' => $time,
				'crdate' => $time,
				'deleted' => 0,
				'hidden' => 0,
				'eventtype' => $this->request->hasPost('eventtype') ? $this->request->getPost('eventtype') : 0,
				'title' => $this->request->hasPost('title') ? $this->request->getPost('title') : 0,
				'repetitive' => $this->request->hasPost('eventtype') == 2 ? 1 : 0,				
				'repeatcycle' => $this->request->hasPost('repeatcycle') ? $this->request->getPost('repeatcycle') : '',
				'dayofweek' => $this->request->hasPost('dayofweek') ? $this->request->getPost('dayofweek') : 0,
				'repeatcycletime' => $this->request->hasPost('repeatcycletime') ? $this->request->getPost('repeatcycletime') : 0,
				'sendoutdate' => $senddate,
				'reviewed' => 0,
				'cleared' => 0,
				'inprogress' => 0,
				'usergroup' => $this->session->get('auth')['usergroup'],
				'mailobjectuid' => $this->request->hasPost('mailobject') ? $this->request->getPost('mailobject') : 0,
				'configurationuid' => $this->request->hasPost('configurationsobject') ? $this->request->getPost('configurationsobject') : 0,
				'subject' => $this->request->hasPost('subject') ? $this->request->getPost('subject') : 0,
				'distributoruid' => $this->request->hasPost('addresslistobject') ? $this->request->getPost('addresslistobject') : 0,
				'cruser_id' => $this->session->get('auth')['usergroup'],
				'addressfolder' => $this->request->hasPost('addressfolder') ? $this->request->getPost('addressfolder') : 0,
				'birthday' => $this->request->hasPost('birthday') ? $this->request->getPost('birthday') : ''
			));
			
			if(!$triggerevent->save()){
				$this->flash->error($triggerevent->getMessages());
			}else{
				$this->flash->success("Triggerevent was created successfully");
			}
		}
		
		$this->assets->addJs('js/vendor/triggereventsInit.js');
		$this->assets->addCss('css/jquery.datetimepicker.css');
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language;
		
		$mailobjects = Mailobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$configurationsobjects = Configurationobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$addresslistobjects = Distributors::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$addressfolders = Addressfolders::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$this->view->setVar("eventtypes",$this->events);
		$this->view->setVar("mailobjects",$mailobjects);
		$this->view->setVar("configurationsobjects",$configurationsobjects);
		$this->view->setVar("addresslistobjects",$addresslistobjects);
		$this->view->setVar("addressfolders",$addressfolders);
		$this->view->setVar('path',$path);
		
	}
	
	
	public function updateAction(){
		$triggereventUid=$this->dispatcher->getParam("uid")?$this->dispatcher->getParam("uid"):0;
		$this->assets->addJs('js/vendor/triggereventsInit.js');
		$this->assets->addCss('css/jquery.datetimepicker.css');
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language;
		
		$mailobjects = Mailobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$configurationsobjects = Configurationobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$addresslistobjects = Distributors::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$addressfolders = Addressfolders::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$triggerevent=Triggerevents::findFirstByUid($triggereventUid);
		if($this->request->isPost()){
			$dateArr=explode(' ',$this->request->hasPost('sendoutdate') ? $this->request->getPost('sendoutdate') : '');
			$senddate=0;
			if(count($dateArr)>1){
				$dateTimeArr=explode(':',$dateArr[1]);
				$dateDataArr=explode('/',$dateArr[0]);
				$senddate=mktime($dateTimeArr[0],$dateTimeArr[1],0,$dateDataArr[1],$dateDataArr[2],$dateDataArr[0]);
			}
			$time=time();
			$triggerevent->assign(array(				
				'tstamp' => $time,				
				'eventtype' => $this->request->hasPost('eventtype') ? $this->request->getPost('eventtype') : 0,
				'title' => $this->request->hasPost('title') ? $this->request->getPost('title') : 0,				
				'repetitive' => $this->request->hasPost('eventtype') == 2 ? 1 : 0,
				'repeatcycle' => $this->request->hasPost('repeatcycle') ? $this->request->getPost('repeatcycle') : '',
				'dayofweek' => $this->request->hasPost('dayofweek') ? $this->request->getPost('dayofweek') : 0,
				'repeatcycletime' => $this->request->hasPost('repeatcycletime') ? $this->request->getPost('repeatcycletime') : 0,
				'sendoutdate' => $senddate,				
				'usergroup' => $this->session->get('auth')['usergroup'],
				'mailobjectuid' => $this->request->hasPost('mailobject') ? $this->request->getPost('mailobject') : 0,
				'configurationuid' => $this->request->hasPost('configurationsobject') ? $this->request->getPost('configurationsobject') : 0,
				'subject' => $this->request->hasPost('subject') ? $this->request->getPost('subject') : 0,
				'distributoruid' => $this->request->hasPost('addresslistobject') ? $this->request->getPost('addresslistobject') : 0,
				'cruser_id' => $this->session->get('auth')['usergroup'],
				'addressfolder' => $this->request->hasPost('addressfolder') ? $this->request->getPost('addressfolder') : 0,
				'birthday' => $this->request->hasPost('birthday') ? $this->request->getPost('birthday') : ''
			));
			if(!$triggerevent->update()){
				$this->flash->error($triggerevent->getMessages());
			}else{
				$this->flash->success("Triggerevent was updated successfully");
			}
		}
		$this->view->setVar("triggerevent",$triggerevent);
		$this->view->setVar("eventtypes",$this->events);
		$this->view->setVar("mailobjects",$mailobjects);
		$this->view->setVar("configurationsobjects",$configurationsobjects);
		$this->view->setVar("addresslistobjects",$addresslistobjects);
		$this->view->setVar("addressfolders",$addressfolders);
		$this->view->setVar('path',$path);
	}
	
	public function subscriptionEventHandler($context)
	{				
		$eventtype = array_search($this->router->getActionName(),$this->events);		
		
		$source=$context->getSource();
		$events = $this->findEvents($eventtype,array('addressfolder'=>$source->pid ));		
		
		if($events){
			foreach($events as $event){						
				if(filter_var($address->email, FILTER_VALIDATE_EMAIL)){
					$this->writeEventbasedQueueObject($event,$source);		
				}
			}
		}		
	}	
	
	public function birthdayEventHandler(){
		$args=func_get_args();
		$event=$args[1];
		$address=$args[2];
		/*Check if already present*/
		
		$mailqueueObj=\nltool\Models\Mailqueue::findFirst(array(
			'conditions' => "DATE_FORMAT(FROM_UNIXTIME(crdate), '%Y-%m-%d')  = CURDATE() AND addressuid = ?1 AND pid = ?2",
			'bind' => array(
				1 => $address->uid,
				2 => $event->uid
			)
		));
		
		if(!$mailqueueObj){
			if(filter_var($address->email, FILTER_VALIDATE_EMAIL)){
				$this->writeEventbasedQueueObject($event,$address);

			}
		}
		
	}
	
	public function recursiveEventHandler(){
		$args=  func_get_arg();
		var_dump($args);
		
	}
	
	private function findEvents($eventtype,$additionalparams = null){		
		$additionalConds = '';
		$bonds=array(1 => $eventtype);
		if($additionalparams){
			foreach($additionalparams as $tableRow => $tableVal){
				$additionalConds .= 'AND '.$tableRow.' = :'.$tableRow.':';
				$bonds[$tableRow] = $tableVal;
			}
		}
		
		$events=  Triggerevents::find(array(
			'conditions' => 'deleted = 0 AND hidden = 0 AND reviewed = 1 AND cleared = 1 AND eventtype= ?1 '.$additionalConds,
			'bind' =>$bonds
		));
		
		return $events;
	}
	
	
	
	private function writeEventbasedQueueObject($event,$address){
		
		$time= time();
		$configuration=  Configurationobjects::findFirstByUid($event->configurationuid);
		$sendoutobject=new Sendoutobjects();
		$sendoutobject->assign(array(
			'pid' => 0,
			'tstamp' => 0,
			'sendstart' => 0,
			'sendend' => 0,
			'crdate' => $time,
			'cruser_id' => $event->cruser_id,
			'deleted' => 0,
			'hidden' => 0,
			'reviewed' => 1,
			'cleared' => 1,			
			'inprogress' => 1,
			'sent' => 0,
			'usergroup' => $event->usergroup,
			'campaignuid' => 0,
			'mailobjectuid' => $event->mailobjectuid,
			'configurationuid'=> $event->configurationuid,
			'subject' => $event->subject,
			'abtest' => 0,
			'distributoruid' => 0,
			'domid' => '',
			'eventuid' => $event->uid
		));
		$sendoutobject->save();
		
		$queueObject=new \nltool\Models\Mailqueue();
		$queueObject->assign(array(
			'pid' => $event->uid,
			'time' => 0,
			'deleted' => 0,
			'hidden' => 0,
			'sent' => 0,
			'mailbody' => Null,
			'crdate' => $time,
			'distributoruid' => 0,
			'addressuid' => $address->uid,
			'campaignuid' => 0,
			'sendoutobjectuid' => $sendoutobject->uid,
			'mailobjectuid' => $event->mailobjectuid,
			'configurationuid' => $event->configurationuid,
			'email' => $address->email,
			'subject' => $event->subject,
			'sendermail' => $configuration->sendermail,
			'sendername' => $configuration->sendername,
			'answermail' => $configuration->answermail,
			'answername' => $configuration->answername, 
			'returnpath' => $configuration->returnpath,
			'organisation' => $configuration->organisation
		));
		$queueObject->save();
		
	}
	
	
	

}