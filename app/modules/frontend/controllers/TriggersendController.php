<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use Phalcon\Mvc\Controller as Controller,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Mvc\Model\Resultset;
use nltool\Models\Sendoutobjects,
	nltool\Models\Mailqueue,
	nltool\Models\Linklookup;
require_once '../app/library/Swiftmailer/swift_required.php';
require_once '../app/library/Html2Plain/Html2Text.php';
/**
 * Class TriggersendController
 *
 * @package baywa-nltool\Controllers
 */

class TriggersendController extends Triggerauth

{
	
	


    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function generateAction()
    {
		
		
		if(!$this->request->isPost()){						
			
			$checktime=microtime(true);
		
			$this->mailrenderer->addressuid=1;
			
			$time=time();
			//find mailings which are due
			$mailing= Sendoutobjects::findFirst(array(
				"conditions" => "deleted=0 AND hidden=0 AND inprogress=0 AND reviewed=1 AND cleared=1  AND sent=0 AND tstamp <= ?1",
				"bind" => array(1 => $time),
				"order" => "tstamp ASC"
			));
			
			if($mailing){
				$addressConditions=$mailing->getAddressconditions(array(
					'conditions' => 'deleted = 0 AND hidden = 0'
				));
				
				$condStrng='';
				if($addressConditions){
					foreach($addressConditions AS $condition){
						switch($condition->junctor){
							case 1:
								$condStrng.='AND ( ';
								break;
							case 2: 
								$condStrng.='OR ( ';
								break;
							default:
								$condStrng=' AND (';
								break;
						}

						$condition->conditionaloperator;
						switch($condition->argument){
							case 1:
								$condStrng.='gender ';
								break;
							case 2: 
								$condStrng.='first_name ';
								break;
							case 3: 
								$condStrng.='last_name ';
								break;
							case 4: 
								$condStrng.='email ';
								break;
							case 5: 
								$condStrng.='zip ';
								break;
							case 2: 
								$condStrng.='region ';
								break;
							case 2: 
								$condStrng.='city ';
								break;
							case 2: 
								$condStrng.='province ';
								break;						

						}

						switch($condition->operator){
							case 1:
								if($condition->conditionaloperator == 1){
									$condStrng.= '=';
								}else if($condition->conditionaloperator == 2){
									$condStrng.= '<>';
								}						 
							break;
							case 2:
								if($condition->conditionaloperator == 1){
									$condStrng.= 'LIKE ';
								}else if($condition->conditionaloperator == 2){
									$condStrng.= 'NOT LIKE ';
								}						 
							break;
							case 3:
								if($condition->conditionaloperator == 1){
									$condStrng.= '>';
								}else if($condition->conditionaloperator == 2){
									$condStrng.= '<=';
								}						 
							break;
							case 4:
								if($condition->conditionaloperator == 1){
									$condStrng.= '>=';
								}else if($condition->conditionaloperator == 2){
									$condStrng.= '<';
								}						 
							break;
							case 5:
								if($condition->conditionaloperator == 1){
									$condStrng.= '<';
								}else if($condition->conditionaloperator == 2){
									$condStrng.= '>=';
								}						 
							break;
							case 6:
								if($condition->conditionaloperator == 1){
									$condStrng.= '<=';
								}else if($condition->conditionaloperator == 2){
									$condStrng.= '>';
								}						 
							break;
						}

						if($condition->operator==2 ){
							$condStrng.='"%'.$condition->argumentcondition.'%") ';

						}else{
							$condStrng.=$condition->argumentcondition.') ';
						}

					}
				}
				$clickconditions=$mailing->getClickconditions(array(
					'conditions' => 'deleted =0 AND hidden = 0'
				));
				$clickcondstrng='';
				$joinTables='';
				if($clickconditions){
					foreach($clickconditions as $clickcondition){
						switch($clickcondition->junctor){
							case 1:
								$clickcondstrng.=' AND ( ';
								break;
							case 2: 
								$clickcondstrng.=' OR ( ';
								break;
							default:
								$clickcondstrng=' AND (';
								break;
						}
						
						switch($clickcondition->conditionaloperator){
							case 1:
								$joinTables=' LEFT JOIN nltool\Models\Linkclicks ON (nltool\Models\Addresses.uid=nltool\Models\Linkclicks.addressuid AND nltool\Models\Linkclicks.sendoutobjectuid='.$clickcondition->sourcesendoutobjectuid.')';
								$bracePos=strpos($clickcondition->argumentcondition,'{{');
								$likeNotLike=$clickcondition->conditiontrue==1 ?  'LIKE':'NOT LIKE';
								if($bracePos){
									$clickcondstrng.='url '.$likeNotLike.' "'.substr($clickcondition->argumentcondition,0,($bracePos-1)).'%"';
								}else{
									$clickcondstrng.='url '.$likeNotLike.' "'.$clickcondition->argumentcondition.'"';
								}
								
								break;
							
						}
						$clickcondstrng.=') ';
					}
				}
				
				
				$distributor=$mailing->getDistributor();
				$addressTimeStart=microtime(true);
				$addresses=$distributor->getAddresses(array(
					'conditions'=>$condStrng,
					'clickconditions'=>array(
						0=>$clickcondstrng,
						1=>$joinTables
						)
				));
				$configuration=$mailing->getConfiguration();					
				$getAddressTime=  microtime(true)-$addressTimeStart;
				//$bodyRaw=file_get_contents('../public/mails/mailobject_'.$mailing->mailobjectuid.'.html');
				
				

				// First build up Mailqueue, then Mail
				$mailing->inprogress=1;
				$mailing->sendstart=$time;
				$mailing->update();
				$insField='(crdate,distributoruid,addressuid,campaignuid,sendoutobjectuid,mailobjectuid,configurationuid,email,subject,sendermail,sendername,answermail,answername,returnpath,organisation)';
				
				$insStr=array();
				$counter=0;
				foreach($addresses as $address){						
					if(filter_var($address->email, FILTER_VALIDATE_EMAIL)){
						if($mailing->abtest==0){
							$insStr[]='('.$time.','.$distributor->uid.','.$address->uid.','.$mailing->campaignuid.','.$mailing->uid.','.$mailing->mailobjectuid.','.$mailing->configurationuid.',"'.$address->email.'","'.$mailing->subject.'","'.$configuration->sendermail.'","'.$configuration->sendername.'","'.$configuration->answermail.'","'.$configuration->answername.'","'.$configuration->returnpath.'","'.$configuration->organisation.'")';						
						}else{
							if($mailing->pid==0 && ($counter+1)%2!=0){
								$insStr[]='('.$time.','.$distributor->uid.','.$address->uid.','.$mailing->campaignuid.','.$mailing->uid.','.$mailing->mailobjectuid.','.$mailing->configurationuid.',"'.$address->email.'","'.$mailing->subject.'","'.$configuration->sendermail.'","'.$configuration->sendername.'","'.$configuration->answermail.'","'.$configuration->answername.'","'.$configuration->returnpath.'","'.$configuration->organisation.'")';						
							}elseif($mailing->pid!=0 && ($counter+1)%2==0){
								$insStr[]='('.$time.','.$distributor->uid.','.$address->uid.','.$mailing->campaignuid.','.$mailing->uid.','.$mailing->mailobjectuid.','.$mailing->configurationuid.',"'.$address->email.'","'.$mailing->subject.'","'.$configuration->sendermail.'","'.$configuration->sendername.'","'.$configuration->answermail.'","'.$configuration->answername.'","'.$configuration->returnpath.'","'.$configuration->organisation.'")';						
							}
						}

						if($counter%200==0 && $counter >0){

								$this->di->get('db')->query("INSERT INTO mailqueue ".$insField." VALUES ".implode(',',$insStr));
								$insStr=array();
						}

						$counter++;
					}

				}
				
				if(count($insStr)>0){
						$this->di->get('db')->query("INSERT INTO mailqueue ".$insField." VALUES ".implode(',',$insStr));
						$insStr=array();
				}
				


				
			
			
				
			//generate mails as they are handed over to the smtp mailqueue
			//hand them over to smtp in chunks of X (Backend configuration) numbers
			
			$overallTime=microtime(true)-$checktime;
			echo('$getAddressTime: '.$getAddressTime.'<br>');
			echo('$overallTime: '.$overallTime.'<br>');
			}
		}else{
			die('<img src="images/cowboy-shaking-head.gif" style="position:absolute;top:40%;left:40%;">');
		}
	}
	
	public function sendAction(){
		$overallStart=  microtime(true);
		$lockFile='../app/logs/sendLock.lock';
		if(!$this->request->isPost()){
			if(file_exists($lockFile) ){
				
				die('locked');
			}

			file_put_contents($lockFile,'');
			$mailing= Sendoutobjects::findFirst(array(
				"conditions" => "deleted=0 AND hidden=0 AND inprogress=1 AND reviewed=1 AND cleared=1 AND sent=0",				
				"order" => "tstamp ASC"
			));
			
			if($mailing){
			$configuration=$mailing->getConfiguration();
			$modelsManager=$this->getDi()->getShared('modelsManager');					
			$mailqueueQueryStrng="SELECT m.*, a.* FROM nltool\Models\Mailqueue AS m LEFT JOIN nltool\Models\Addresses AS a ON m.addressuid=a.uid WHERE m.sent=0 AND m.deleted=0 AND m.hidden=0 AND m.sendoutobjectuid=".$mailing->uid." LIMIT ".$this->config['smtp']['mailcycle'];			
			$mailqueue=$modelsManager->executeQuery($mailqueueQueryStrng);																			
			
			$bodyRaw=file_get_contents('../public/mails/mailobject_'.$mailing->mailobjectuid.'.html');												
			
			if($configuration->clicktracking==1){
				
				$this->mailrenderer->writeClicktrackingLinks($bodyRaw,$mailing);
				$links=Linklookup::find(array(
					'conditions'=>'deleted=0 AND hidden=0 AND sendoutobjectuid = ?1',
					'bind'=> array(
						1=>$mailing->uid
					),
					'order'=>'linknumber ASC'
				));
				$linkKeyMap=array();
				foreach($links as $link){
					$linkKeyMap[$link->linknumber]=$link->uid;
				}
			}
			
							
			//$mailer->registerPlugin(new \Swift_Plugins_AntiFloodPlugin(100,10));	
			$counter=0;
			$numSent=0;
			$sentArray=array();
			foreach($mailqueue as $mailqueueElement){
				$checktime=microtime(true);
				if($counter==0 || $counter%100==0){
					//Mailqueue abarbeiten
					$transport = \Swift_SmtpTransport::newInstance()
							->setHost($this->config['smtp']['host'])
							->setPort($this->config['smtp']['port'])
							->setEncryption($this->config['smtp']['security'])
							->setUsername($this->config['smtp']['username'])
							->setPassword($this->config['smtp']['password']);

					$mailer = \Swift_Mailer::newInstance($transport);
					if($counter>0){						
						sleep(5);
					}
				}
				
				

				$body=$this->mailrenderer->renderVars($bodyRaw,$mailqueueElement->a);
				/*
				 * Für die geplanten volldynamische Inhalte entstehen an dieser Stelle neue Links, 
				 * diese müssen ins Linklookup eingefügt und eine neue individuelle Linkmap erstellt,
				 * da der Renderer einfach $match[n] mit $linkKeyMap[n] in Verein bringt.
				 */
				if($configuration->clicktracking==1){
					
					
					$bodyFinal=$this->mailrenderer->renderFinal($body,$mailqueueElement->a->uid,$mailing->uid,$linkKeyMap);								
				}else{
					$bodyFinal=$body;
				}
				
				 $message = \Swift_Message::newInstance($mailing->subject)
							->setSender(array($configuration->sendermail => $configuration->sendername))
							->setFrom(array($configuration->sendermail => $configuration->sendername))
							->setReplyTo($configuration->answermail)
							->setReturnPath($configuration->returnpath);
				$headers = $message->getHeaders();
				$headers->addIdHeader('Int-ID', $mailqueueElement->m->uid.'.' .  uniqid().'@'. $_SERVER['SERVER_NAME']);
				
				 				 
				$message->setBody($bodyFinal, 'text/html');
                                 $h2t = new \Html2Text\Html2Text($bodyFinal);
                                $message->addPart($h2t->getText(), 'text/plain');
                                //$message->addPart($this->config['defaults']['plaintextFallbackText'], 'text/plain');
				$to=array($mailqueueElement->m->email => $mailqueueElement->a->first_name.' '.$mailqueueElement->a->last_name);
				$message->setTo($to);
				
				if($mailqueueElement->m->sent==0){
					
					/*$mailqueueElement->assign(array(
						"mailbody"=>$bodyFinal,
						"sent"=>1
					));							
					$mailqueueElement->update();*/
					
					array_push($sentArray,$mailqueueElement->m->uid);
					if(!$this->config['application']['dontSendReally']){
						try{
							$numSent+=$mailer->send($message, $failures);
						}catch(\Swift_TransportException $e){
							echo($e->getMessage());
						}
						
					}	
					//usleep(10000);
				
					
					$endtime=  microtime(true);
					$timeused=$endtime-$checktime;
					
					//echo('pid: '.getmypid().' numsent: '.$numSent.' : counter'.$counter.' : '.$address->uid.' <-> '.$timeused.'<br>');
					file_put_contents('../app/logs/debuggerSend.csv',getmypid().' <--PID '.$timeused.' <-> '.$counter.$mailqueueElement->a->uid.PHP_EOL,FILE_APPEND);
				}
				if($counter>0 && count($sentArray)>0 && $counter%20==0){
					$queryStrng="UPDATE mailqueue SET tstamp=".time().",sent=1 WHERE uid IN(".implode(',',$sentArray).")";			
					$this->db->query($queryStrng);
					$sentArray=array();
				}
				$counter++;
			}
			
			
		if(count($sentArray)>0){
			$queryStrng="UPDATE mailqueue SET tstamp=".time().",sent=1 WHERE uid IN(".implode(',',$sentArray).")";			
			$this->db->query($queryStrng);
		}
		
		
			$restQueue=$mailing->countMailqueue(array(
				"conditions" => "deleted=0 AND hidden=0 AND sent=0"								
			));
			
			if(!$restQueue){
				$mailing->assign(array(
					"inprogress"=>0,
					"sent" => 1,
					"sendend" => time()
				));
				
				$mailing->update();
			}
			}
			unlink($lockFile);
			$overallEnd=  microtime(1)-$overallStart;
			echo($overallEnd);
		}else{
			die('<img src="images/cowboy-shaking-head.gif" style="position:absolute;top:40%;left:40%;">');
		}
	}
	
	
	
	
	
}