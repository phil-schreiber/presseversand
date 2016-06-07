<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Mailobjects as Mailobjects,
	nltool\Models\Campaignobjects as Campaignobjects,	
	nltool\Models\Sendoutobjects as Sendoutobjects,
	nltool\Models\Addressconditions	as Addressconditions,
	nltool\Models\Configurationobjects	as Configurationobjects,
	nltool\Models\Clickconditions;
require_once '../app/library/Swiftmailer/swift_required.php';

/**
 * Class IndexController
 *
 * @package baywa-nltool\Controllers
 */
class CampaignobjectsController extends ControllerBase
{
	function encodeURI($url) {
		// http://php.net/manual/en/function.rawurlencode.php
		// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/encodeURI
		$unescaped = array(
			'%2D'=>'-','%5F'=>'_','%2E'=>'.','%21'=>'!', '%7E'=>'~',
			'%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'
		);
		$reserved = array(
			'%3B'=>';','%2C'=>',','%2F'=>'/','%3F'=>'?','%3A'=>':',
			'%40'=>'@','%26'=>'&','%3D'=>'=','%2B'=>'+','%24'=>'$'
		);
		$score = array(
			'%23'=>'#'
		);
		return strtr(rawurlencode($url), array_merge($reserved,$unescaped,$score));

	}
    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
    {
        //$this->flashSession->error('Page not found: ' . $this->escaper->escapeHtml($this->router->getRewriteUri()));
        
		if($this->request->isPost() && $this->request->getPost('campaignobjectuid')!=0){
			
			$campaignobject = Campaignobjects::findFirst(array(
			"conditions" => "uid = ?1",
			"bind" => array(1 => $this->request->getPost('campaignobjectuid'))
			));
			$frozenSendoutobjects=  Sendoutobjects::find(array(
				"conditions" => "deleted=0 AND hidden =0 AND pid=0 AND campaignuid=?1 AND (cleared=1 OR inprogress=1 OR sent=1)",
				"bind" => array(1 => $this->request->getPost('campaignobjectuid'))
			));
			$frozenDomIds='[';
			foreach($frozenSendoutobjects as $frozenSendoutobject){
				$frozenDomIds.='"'.$frozenSendoutobject->domid.'",';
			}
			if(strlen($frozenDomIds)>1){
			$frozenDomIds=substr($frozenDomIds,0,-1);
			}
			$frozenDomIds.=']';
			$campaignobjectJson=
					'{"uid":'.$campaignobject->uid.',
					"title":"'.$campaignobject->title.'",
				"automationgraphstring":"'.$this->encodeURI($campaignobject->automationgraphstring).'",
				"connections":'.substr($campaignobject->connections,1,-1).','.
				'"frozensendoutobjects":'.$frozenDomIds.
					'}';
			
			die($campaignobjectJson);
			
		}else{
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.$this->view->language.'/campaignobjects/update/';
			$campaignobjects=Campaignobjects::find(array(
					"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
					"bind" => array(1 => $this->session->get('auth')['usergroup']),
					"order" => "tstamp DESC"
				));

			$this->view->setVar('campaignobjects',$campaignobjects);
			$this->view->setVar('path',$path);
		}
		
		
		
    }
	
	public function createAction()
	{
		 if($this->request->isPost() && $this->request->getPost('campaignobjectuid')==0 ){
			 
				
				$automationgraphString=$this->request->getPost('htmlobjects');
				$time=time();												
				
				$campaignobjectRecord=new Campaignobjects();
				$campaignobjectRecord->assign(array(
					'pid'=>0,
					'crdate' => $time,
					'tstamp' => $time,
					'cruser_id' =>$this->session->get('auth')['uid'],
					'usergroup' =>$this->session->get('auth')['usergroup'],
					'deleted' =>0,
					'hidden' => 0,
					'title'=>$this->request->getPost('title','striptags')== '' ? 'no name' : $this->request->getPost('title','striptags'),
					'connections'=>$this->request->getPost('connections','striptags'),
					'automationgraphstring' =>$automationgraphString
				));
				if (!$campaignobjectRecord->save()) {
					$this->flash->error($campaignobjectRecord->getMessages());
				}
								
				$this->writeSendoutObjects($campaignobjectRecord);
				
				
				
				die($campaignobjectRecord->uid);
				
				$this->view->disable();                       
				
		 }elseif($this->request->isPost() && $this->request->getPost('campaignobjectuid')!=0 ){
			 $this->dispatcher->forward(array(
					"controller" => "campaignobjects",
					"action" => "update"
				));
			 
		 }else{
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.'public/mails/';
			$this->assets->addJs('js/vendor/campaignInit.js');
			$this->assets->addCss('css/jquery.datetimepicker.css');
                        
			$this->view->setVar('lang',$this->view->language);
			$this->view->setVar('mailpath',$path);
		 }
	}
	
	public function updateAction(){
		
		if($this->request->isPost()){
			$campaignObjectuid=$this->request->getPost('campaignobjectuid');
			$campaignobjectRecord = Campaignobjects::findFirst(array(
				"conditions" => "uid = ?1",
				"bind" => array(1 => $campaignObjectuid)
				));
			$campaignobjectRecord->assign(array(
				"tstamp" => time(),				
				"automationgraphstring"=>$this->request->getPost('htmlobjects'),
				'title'=>$this->request->getPost('title','striptags')== '' ? 'no name' : $this->request->getPost('title','striptags'),
				'connections'=>$this->request->getPost('connections','striptags'),
			));
			$campaignobjectRecord->update();			
			$this->removePreviousObjectsFromCampaign($campaignobjectRecord->uid);			
			$this->writeSendoutObjects($campaignobjectRecord);
			$this->view->disable();  
			
			die($campaignobjectRecord->uid);
			
		}else{
			$campaignObjectuid=$this->dispatcher->getParam("uid");
			$campaignobjectRecord = Campaignobjects::findFirst(array(
				"conditions" => "uid = ?1",
				"bind" => array(1 => $campaignObjectuid)
				));
			$this->view->setVar('campaignobjectUid',$campaignObjectuid);
			$this->view->setVar('campaignobjectTitle',$campaignobjectRecord->title);

			$this->assets->addJs('js/vendor/campaignInit.js');
			$this->assets->addCss('css/jquery.datetimepicker.css');
                        $this->assets->addCss('css/chosen.min.css');
			$this->view->setVar('lang',$this->view->language);
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.'public/mails/';
			$this->view->setVar('mailpath',$path);
		}
	}
	
	public function deleteAction(){
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$object= Campaignobjects::findFirstByUid($this->request->getPost('uid'));
				$object->assign(array(
					'tstamp' => time(),
					'deleted' =>1,
					'hidden' =>1
				));
				$object->update();
				$this->removePreviousObjectsFromCampaign($object->uid);
			}
			die();
		}
	}
	
	private function removePreviousObjectsFromCampaign($campaignobjectUid){
		$sendoutobjectRecords=  Sendoutobjects::find(array(
				"conditions" => "deleted = 0 AND hidden =0  AND campaignuid = ?1",
							"bind" => array(
								1 => $campaignobjectUid																
								)
			));
		foreach($sendoutobjectRecords as $sendoutobjectRecord){
			if($sendoutobjectRecord){
				$sendoutobjectRecord->deleted=1;
				$sendoutobjectRecord->hidden=1;				
				$sendoutobjectRecord->cleared=0;
				$sendoutobjectRecord->reviewed=0;
				$sendoutobjectRecord->update();
				$addressconditions=$sendoutobjectRecord->getAddressconditions();
				if($addressconditions){
					foreach($addressconditions as $addresscondition){						
						$addresscondition->delete();
					}
				}
				$clickconditions=$sendoutobjectRecord->getClickconditions();
				if($clickconditions){
					foreach($clickconditions as $clickcondition){
						$clickcondition->delete();
					}
				}
			}	
		}
			
	}
	
	private function writeSendoutObjects($campaignobjectRecord){
		$time=time();
		foreach($this->request->getPost('sendoutobjectelements') as $sendoutobjectElements){
					
					$rawArray=json_decode($sendoutobjectElements,true);					
					
					$rawdate=$rawArray['tstamp'];
					
					$dateArr=explode(' ',$rawdate);
					$senddate=0;
					if(count($dateArr)>1){
					$dateTimeArr=explode(':',$dateArr[1]);
					$dateDataArr=explode('/',$dateArr[0]);
					$senddate=mktime($dateTimeArr[0],$dateTimeArr[1],0,$dateDataArr[1],$dateDataArr[2],$dateDataArr[0]);
					}
					$sendoutobject=  Sendoutobjects::findFirst(array(
									"conditions" => "deleted=0 AND hidden=0 AND campaignuid = ?1 AND usergroup = ?2 AND domid LIKE ?3",
									"bind" => array(
											1 => $campaignobjectRecord->uid,
											2 => $this->session->get('auth')['usergroup'],
											3 => $rawArray['id']
										)
									));
					
					if(!$sendoutobject){
						$sendoutobject=new Sendoutobjects();
						$sendoutobject->assign(array(
							'pid'=>0,
							'crdate' => $time,
							'tstamp' => $senddate,
							'sendstart' =>0,
							'sendend' =>0,
							'cruser_id' =>$this->session->get('auth')['uid'],
							'usergroup' =>$this->session->get('auth')['usergroup'],
							'deleted' =>0,
							'hidden' => 0,
							'reviewed'=>0,
							'cleared'=>0,
							'inprogress'=>0,
							'sent'=>0,
							'campaignuid'=>$campaignobjectRecord->uid,						
							'mailobjectuid'=>intval($rawArray['mailobjectuid']),
							'configurationuid'=>intval($rawArray['configurationuid']),
							'subject'=>$rawArray['subject'] ? urldecode($rawArray['subject']) : 'undefined',
							'abtest'=>intval($rawArray['abtest']),
							'distributoruid'=>intval($rawArray['distributoruid']),
							'domid'=>isset($rawArray['id']) ? $rawArray['id'] : '',							
							'eventuid' => 0
						));
						
						if(!$sendoutobject->save()){
							$this->flash->error($sendoutobject->getMessages());
						}else{
							$this->notify($sendoutobject);
						}
					}else{
						if($sendoutobject->cleared!=1 || $sendoutobject->sent!=1 || $sendoutobject->inprogress!=1){
							$sendoutobject->assign(array(							
								'tstamp' => $senddate,
								'cruser_id' =>$this->session->get('auth')['uid'],														
								'mailobjectuid'=>intval($rawArray['mailobjectuid']),
								'configurationuid'=>intval($rawArray['configurationuid']),
								'subject'=>$rawArray['subject'] ? urldecode($rawArray['subject']) : 'undefined',
								'abtest'=>intval($rawArray['abtest']),
								'distributoruid'=>intval($rawArray['distributoruid'])							
							));
							if(!$sendoutobject->update()){
								$this->flash->error($sendoutobject->getMessages());
							}
						}

					}
					
					
					if($rawArray['abtest']==1){
						$rawdateB=$rawArray['tstampB'];
						
						$dateArrB=explode(' ',$rawdateB);
						$dateTimeArrB=explode(':',$dateArrB[1]);
						$dateDataArrB=explode('/',$dateArrB[0]);
						
						$sendoutobjectB=  Sendoutobjects::findFirst(array(
									"conditions" => "deleted=0 AND hidden=0 AND pid != 0 AND campaignuid = ?1 AND usergroup = ?2 AND domid = ?3",
									"bind" => array(
											1 => $campaignobjectRecord->uid,
											2 => $this->session->get('auth')['usergroup'],
											3 => $rawArray['id']
										)
									));
						
						if(!$sendoutobjectB){
							$sendoutobjectB=new Sendoutobjects();
							$sendoutobjectB->assign(array(
								'pid'=>$sendoutobject->uid,
								'crdate' => $time,
								'tstamp' => mktime($dateTimeArrB[0],$dateTimeArrB[1],0,$dateDataArrB[1],$dateDataArrB[2],$dateDataArrB[0]),
								'sendstart' =>0,
								'sendend' =>0,
								'cruser_id' =>$this->session->get('auth')['uid'],
								'usergroup' =>$this->session->get('auth')['usergroup'],
								'deleted' =>0,
								'hidden' => 0,
								'reviewed'=>0,
								'cleared'=>0,
								'inprogress'=>0,
								'sent'=>0,
								'campaignuid'=>$campaignobjectRecord->uid,						
								'mailobjectuid'=>$rawArray['mailobjectB'],
								'configurationuid'=>$rawArray['configurationuidB'],
								'subject'=>urldecode($rawArray['subjectB']),
								'abtest'=>1,
								'distributoruid'=>$sendoutobject->distributoruid,
								'domid'=>$rawArray['id'],
                                                            'eventuid' => 0

							));
							if(!$sendoutobjectB->save()){
								$this->flash->error($sendoutobjectB->getMessages());
							}
						}else{
							if($sendoutobjectB->cleared!=1 && $sendoutobjectB->sent!=1 && $sendoutobjectB->inprogress!=1){
								$sendoutobjectB->assign(array(							
									'tstamp' => $senddate,
									'cruser_id' =>$this->session->get('auth')['uid'],														
									'mailobjectuid'=>intval($rawArray['mailobjectB']),
									'configurationuid'=>intval($rawArray['configurationuidB']),
									'subject'=>urldecode($rawArray['subjectB']),
									'abtest'=>1,
									'distributoruid'=>intval($rawArray['distributoruid'])
								));
								if(!$sendoutobjectB->update()){
									$this->flash->error($sendoutobjectB->getMessages());
								}
							}
							
						}
					}
					
					if(isset($rawArray['conditions']) && $sendoutobject->cleared==0 && $sendoutobject->inprogress==0 && $sendoutobject->sent==0){
						
						$addressconditionsPrev=$sendoutobject->getAddressconditions();
							if($addressconditionsPrev){
								foreach($addressconditionsPrev as $addressconditionPrevEl){							
									$addressconditionPrevEl->delete();
								}
							}
						
						foreach($rawArray['conditions'] as $conditionArray){
							
							$addressconditions=new Addressconditions();				
							$addressconditions->assign(array(
								'pid'=>$sendoutobject->uid,
								'crdate'=>$time,
								'tstamp'=>$time,
								'cruser_id' =>$this->session->get('auth')['uid'],
								'usergroup' =>$this->session->get('auth')['usergroup'],
								'deleted' =>0,
								'hidden' => 0,
								'junctor' => intval($conditionArray[0]['value']),
								'conditionaloperator' => intval($conditionArray[1]['value']),
								'argument' => intval($conditionArray[2]['value']),
								'operator'=> intval($conditionArray[3]['value']),
								'argumentcondition' => $conditionArray[4]['value']
								
								
							));
							if(!$addressconditions->save()){
								$this->flash->error($addressconditions->getMessages());
							}
							
						}
					}
					
					if(isset($rawArray['clickconditionstrue']) || isset($rawArray['clickconditionsfalse'])){
						if(isset($rawArray['clickconditionstrue'])){
							$truefalse=1;
							$conditions=$rawArray['clickconditionstrue'];
						}else{
							$truefalse=0;
							$conditions=$rawArray['clickconditionsfalse'];
						}
						$clickconditionsPrev=$sendoutobject->getClickconditions();
						if($clickconditionsPrev){
							foreach($clickconditionsPrev as $clickconditionPrev){
								$clickconditionPrev->delete();
							}
						}
						
						foreach($conditions as $conditionArray){
							/*Search for SourceSendoutobject UID not perferctly placed performancewise*/
							$sourceSendoutObject= Sendoutobjects::findFirst(array(
								'conditions' => 'deleted=0 AND hidden=0 AND campaignuid=?1 AND domid LIKE ?2',
								'bind'=>array(
									1=>$campaignobjectRecord->uid,
									2=>$conditionArray[4]['value']
								)
							));
							$clickcondition=new Clickconditions();
							$clickcondition->assign(array(
								'pid' => $sendoutobject->uid,
								'crdate' => $time,
								'tstamp' => $time,
								'deleted'=>0,
								'hidden'=>0,
								'cruser_id' => $this->session->get('auth')['uid'],
								'usergroup' => $this->session->get('auth')['usergroup'],
								'junctor' => intval($conditionArray[0]['value']),
								'conditionaloperator' => intval($conditionArray[1]['value']),
								'thecondition' => intval($conditionArray[2]['value']),
								'argumentcondition' =>$conditionArray[3]['value'],
								'conditiontrue' => $truefalse,
								'sourcesendoutobjectuid' => $sourceSendoutObject->uid
									
							));
							if(!$clickcondition->save()){
								$this->flash->error($clickcondition->getMessages());
							}
							
						}
					}
				}
	}
	
	private function notify($sendoutobject){
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$reviewPath='http://'.$_SERVER['SERVER_NAME'].$this->config['application'][$environment]['staticBaseUri'];
		$getConf = Configurationobjects::findFirstByUid($sendoutobject->configurationuid);
		$users = $getConf->getAuthorities();		
		foreach($users as $user){
		
			if($user){
				try{
					
					$userLang=$user->getUserlanguage();
					
					$body=$this->translate('newReviewNotify');
					$bodyFinal=  str_replace(
							array('#name#','#subject#','#senddate#','#reviewLink#'),
							array(
								$user->last_name,
								$sendoutobject->subject,
								date('d.m.Y',$sendoutobject->tstamp),
								'<a href="'.$reviewPath.$userLang->shorttitle.'/review/update/'.$sendoutobject->uid.'">'.$reviewPath.$userLang->shorttitle.'/review/update/'.$sendoutobject->uid.'</a>'
								),
							$body);
					
					 $transport = \Swift_SmtpTransport::newInstance()
							->setHost($this->config['smtp']['host'])
							->setPort($this->config['smtp']['port'])
							->setEncryption($this->config['smtp']['security'])
							->setUsername($this->config['smtp']['username'])
							->setPassword($this->config['smtp']['password']);
					$mailer = \Swift_Mailer::newInstance($transport);
					$mailer->registerPlugin(new \Swift_Plugins_AntiFloodPlugin(100,30));
					$message = \Swift_Message::newInstance($this->translate('newReviewNotifySubject'))
								->setSender(array($this->config['admin']['email'] => $this->config['admin']['name']))
								->setFrom(array($this->config['admin']['email'] => $this->config['admin']['name']))
								->setReplyTo($this->config['admin']['email'])
								->setBcc($this->config['admin']['email'])
								->setReturnPath($this->config['admin']['email']);
					$message->setBody($bodyFinal, 'text/html');
					$message->setTo(array($user->email => $user->first_name.' '.$user->last_name));
					//pull the trigger
					$mailer->send($message, $failures);	
				}catch(\Swift_SwiftException $e){
					echo($e->getMessage());
				}catch(\Exception $e){
					echo($e->getMessage());
				}
			}
		}
		
	}
}