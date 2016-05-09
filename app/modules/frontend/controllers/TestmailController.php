<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Addresses,
	nltool\Models\Campaignobjects,
	nltool\Models\Sendoutobjects;	
require_once '../app/library/Swiftmailer/swift_required.php';
require_once '../app/library/Html2Plain/Html2Text.php';
/**
 * Class TestmailController
 *
 * @package baywa-nltool\Controllers
 */
class TestmailController extends ControllerBase
{
	
	function createAction(){
		if($this->request->isPost()){
			$sendoutobjectuid=$this->request->getPost("sendoutobjectuid");			
			
			$address=new Addresses();
			$address->assign(array(
				'email'=>$this->request->getPost("email", "email", "some@example.com"),
				'salutation'=>'Sehr geehrter/Sehr geehrte',
				'last_name'=>'Tester',
				'uid'=>$this->session->get('auth')['uid'],
				'first_name'=>'Test',
				'title' => 'Dr.',
				'company' => 'Testcompany'				
			));
			
			
			
			$mailing=  Sendoutobjects::findFirst(array(
				'conditions'=>'uid=?1',
				'bind'=>array(
					1=>$sendoutobjectuid
				)
			));
			$configuration=$mailing->getConfiguration();
			
			$bodyRaw=file_get_contents('../public/mails/mailobject_'.$mailing->mailobjectuid.'.html');
			
			$this->mailrenderer->writeClicktrackingLinks($bodyRaw,$mailing);			

			$bodyFinal=$this->mailrenderer->renderVars($bodyRaw,$address);
			

			 $transport = \Swift_SmtpTransport::newInstance()
							->setHost($this->config['smtp']['host'])
							->setPort($this->config['smtp']['port'])
							->setEncryption($this->config['smtp']['security'])
							->setUsername($this->config['smtp']['username'])
							->setPassword($this->config['smtp']['password']);
			$mailer = \Swift_Mailer::newInstance($transport);
			$mailer->registerPlugin(new \Swift_Plugins_AntiFloodPlugin(100,30));
			$message = \Swift_Message::newInstance($mailing->subject)
						->setSender(array($configuration->sendermail => $configuration->sendername))
						->setFrom(array($configuration->sendermail => $configuration->sendername))
						->setReplyTo($configuration->answermail)
						->setReturnPath($configuration->returnpath);
			$message->setBody($bodyFinal, 'text/html');
			$message->setTo(array($address->email => 'Test Test'));
			 $h2t = new \Html2Text\Html2Text($bodyFinal);
                                $message->addPart($h2t->getText(), 'text/plain');

			//pull the trigger
			$mailer->send($message, $failures);	
			die(json_encode($failures));
		}
		
	}
	
	
}	