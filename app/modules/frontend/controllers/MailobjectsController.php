<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use Phalcon\Tag,
	nltool\Models\Usergroups as Usergroups,
	nltool\Models\Templateobjects as Templateobjects,
	nltool\Models\Mailobjects as Mailobjects,
	nltool\Models\Contentobjects as Contentobjects,
	Phalcon\Mvc\View\Engine\Volt\Compiler as Compiler;

/**
 * Class MailobjectsController
 *
 * @package baywa-nltool\Controllers
 */
class MailobjectsController extends ControllerBase
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
    {
		if($this->request->isPost()){
			$mailobjects=Mailobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "cruser_id <> ".$this->session->get('auth')['uid'].",cruser_id,tstamp DESC"
			));
			$mailobjectsArray=array();
                        $userCounter=-1;
                        $olduser=0;
			foreach($mailobjects as $mailobject){
                            if( $olduser !== $mailobject->cruser_id){
                               $olduser= $mailobject->cruser_id;
                               $userCounter++;
                            }
				$mailobjctsArray[$userCounter][]=array(
					'uid'=>$mailobject->uid,
					'title'=>$mailobject->title,
					'date' =>date('d.m.Y H:i',$mailobject->tstamp),
                                        'cruser' => $mailobject->getCruser()->username,
				);
                                
						
			}
			$returnJson=json_encode($mailobjctsArray);
			echo($returnJson);
			die();
		}else{
        $environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language.'/mailobjects/update/';
		
		$mailobjects=Mailobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
		$this->view->setVar('mailobjects',$mailobjects);
		$this->view->setVar('path',$path);
		
		}
		
		
    }
	
	/**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function createAction()
    {		
		
        if($this->request->isPost()){
			$time=time();
			$templateUid=$_POST['templateobject'];
			
			$mailObject=new Mailobjects();
			
			$mailObject->assign(array(				
				'tstamp' => $time,				
				'crdate' => $time,
				'cruser_id' => $this->session->get('auth')['uid'],
				'usergroup' => $this->session->get('auth')['usergroup'],
				'title' => $_POST['title'],
				'templateuid' =>$_POST['templateobject']
			));
			
			
			 if (!$mailObject->save()) {
                $this->flash->error($mailObject->getMessages());
            } else {
				
				if($templateUid !=0){
				//$this->flash->success("successfully created");
                                $mainTemplate='../app/modules/frontend/templates/main.volt';
                                if(file_exists('../app/modules/frontend/templates/main_'.$this->session->get('auth')['usergroup'].'.volt')){
                                    $mainTemplate='../app/modules/frontend/templates/main_'.$this->session->get('auth')['usergroup'].'.volt';
                                }
				
				$templateFile=  '../app/modules/frontend/templates/template_mail_'.$templateUid.'.volt';
				$generatedMailFile='../public/mails/mailobject_'.$mailObject->uid.'.html';
				$bodyRaw=file_get_contents($templateFile);
				
				$basicContentElements=$this->getBasicContentElementsFromTemplate($bodyRaw);
				foreach($basicContentElements as $cElCount => $basicContentElement){
					$cElement=new Contentobjects();
					
					$cElement->assign(array(
						'crdate' => $time,
						'tstamp' =>$time,
						'cruser_id' =>$this->session->get('auth')['uid'],
						'usergroup' =>$this->session->get('auth')['usergroup'],
						'contenttype' =>0,
						'sourcecode'=> '<div class="cElement">'.$basicContentElement.'</div>',
						'templateposition'=> $cElCount,
						'positionsorting'=> 0,
						'mailobjectuid' => $mailObject->uid,
						'title'=> 'Basic Content Element '.$cElCount
					));
					if (!$cElement->save()) {
						$this->flash->error($cElement->getMessages());
					}
					
					
				}
				$bodyNice=$this->editRenderMailVars($bodyRaw);
				
				file_put_contents($generatedMailFile, $bodyNice);
				
				$this->response->redirect($this->view->language.'/mailobjects/update/'.$mailObject->uid.'/'); 
				
				}
				
            }
			
			$this->view->disable(); 
			
			
		}else{
			$usergroup=Usergroups::findFirstByUid($this->session->get('auth')['usergroup']);
		
			$templateobjects = $usergroup->getTemplateobjects(array(
				"conditions" => "hidden=0 AND deleted=0 AND templatetype = 0",				
				"group" => "nltool\Models\Templateobjects.uid"
				));
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$this->view->setVar('httphost','http://'.$this->request->getHttpHost());
			$thumbnailSm=array();
			foreach($templateobjects as $templateobject){
				
				$thumbnailSmArray=explode('_',$templateobject->templatefilepath);
				$fileType=explode('.',$thumbnailSmArray[2]);
				
				$thumbnailSm[$templateobject->uid]=$baseUri.$thumbnailSmArray[0].'_'.$thumbnailSmArray[1].'_'.'S.'.$fileType[1];
			}

			$this->view->templateobjects = $templateobjects;  		
			$this->view->templateobjectsthumbs = $thumbnailSm;  		
			$path=$baseUri.$this->view->language;								
			$this->view->setVar('path',$path);			
		
		}
		
    }
	
	function updateAction()
	{
            
            
		$this->assets            
            ->addJs('js/vendor/mailobjectsInit.js');
		
		//$this->view->setVar('language',);
		if($this->request->isPost() && $this->dispatcher->getParam("uid")){
			
			
			$time=time();
                        if($this->request->getPost('newTitle') !== ''){
                            $oldMailObjectUid = $this->dispatcher->getParam("uid");
                            $oldMailobjectRecord = Mailobjects::findFirst(array(
                                "conditions" => "uid = ?1",
                                "bind" => array(1 => $oldMailObjectUid)
                             ));
                            $mailobjectRecord=new Mailobjects();
                            $mailobjectRecord->assign(array(				
                                    'tstamp' => $time,				
                                    'crdate' => $time,
                                    'cruser_id' => $this->session->get('auth')['uid'],
                                    'usergroup' => $this->session->get('auth')['usergroup'],
                                    'title' => $this->request->getPost('newTitle'),
                                    'templateuid' =>$oldMailobjectRecord->templateuid
                            ));
                            if ($mailobjectRecord->save()) {
                                $mailObjectUid = $mailobjectRecord->uid;
                            }                                                        
                        }else{
                            $mailObjectUid = $this->dispatcher->getParam("uid");
                            $mailobjectRecord = Mailobjects::findFirst(array(
                                "conditions" => "uid = ?1",
                                "bind" => array(1 => $mailObjectUid)
                             ));
                        }
			$contentElements=$this->request->hasPost('contentElements') ? $this->request->getPost('contentElements') : 0;
			
			$generatedMailformFile='../public/mails/mailobject_'.$mailObjectUid.'.html';
			$templateFile=  '../app/modules/frontend/templates/template_mail_'.$mailobjectRecord->templateuid.'.volt';
			$mainTemplateFile='../app/modules/frontend/templates/main.volt';                         
                        if(file_exists('../app/modules/frontend/templates/main_'.$this->session->get('auth')['usergroup'].'.volt')){
                             $mainTemplateFile='../app/modules/frontend/templates/main_'.$this->session->get('auth')['usergroup'].'.volt';
                         }
                         
			$bodyRaw=file_get_contents($templateFile);
			
			if(is_array($contentElements)){
				$updateTime=time();
				foreach($contentElements as $position => $positionContents){
					foreach($positionContents as $sorting => $cElement){
						$contentobjectRecord=  Contentobjects::findFirst(array(
							"conditions" => "deleted = 0 AND hidden =0 AND templateposition = ?1 AND positionsorting = ?2 AND mailobjectuid = ?3",
							"bind" => array(
								1 => $position,
								2 => $sorting,
								3 => $mailObjectUid
								
								)
						));
						
						if($contentobjectRecord){
							$versionContentobjectRecord=new Contentobjects();
							$versionContentobjectRecord->assign(array(
								'pid' =>$contentobjectRecord->uid,
								'crdate' => $contentobjectRecord->crdate,
								'tstamp' => $updateTime,
								'hidden' => 1,
								'cruser_id' =>$contentobjectRecord->cruser_id,
								'usergroup' =>$contentobjectRecord->usergroup,
								'contenttype' =>$contentobjectRecord->contenttype,
								'sourcecode'=> $contentobjectRecord->sourcecode,
								'templateposition'=> $contentobjectRecord->templateposition,
								'positionsorting'=> $contentobjectRecord->positionsorting,
								'mailobjectuid' => $contentobjectRecord->mailobjectuid,
								'title'=> $contentobjectRecord->title
							));
							$versionContentobjectRecord->save();
								
							$contentobjectRecord->sourcecode=$cElement;
							$contentobjectRecord->tstamp=$updateTime;
							$contentobjectRecord->update();
						}else{
							$contentobjectRecord=new Contentobjects();
							$contentobjectRecord->assign(array(
								'crdate' => $updateTime,
								'tstamp' => $updateTime,
								'cruser_id' =>$this->session->get('auth')['uid'],
								'usergroup' =>$this->session->get('auth')['usergroup'],
								'contenttype' =>0,
								'sourcecode'=> $cElement,
								'templateposition'=> $position,
								'positionsorting'=> $sorting,
								'mailobjectuid' => $mailObjectUid,
								'title'=> 'Basic Content Element '.$position.'.'.$sorting
							));
							if (!$contentobjectRecord->save()) {
								$this->flash->error($cElement->getMessages());
							}
						}
						
					}
				}
				
				/* Deleting all entries, which are not current */
				 $query=$this->modelsManager->createQuery( "UPDATE nltool\Models\Contentobjects SET deleted=1, hidden=1 WHERE tstamp < :updateTime: AND mailobjectuid = :mailobjectuid:");
				
				  $query->execute(array(
					 'updateTime' => $updateTime,
					  'mailobjectuid' => $mailObjectUid
				   ));
			}
			
			$contentObjects=Contentobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND mailobjectuid = ?1",
				"bind" => array(1 => $mailObjectUid),
				"order" => "templateposition ASC, positionsorting ASC"
			));
			$mailBody=$this->writeContentElements($bodyRaw, $contentObjects);
			$mainTemplate=  file_get_contents($mainTemplateFile);
			$mail=$this->renderMain($mainTemplate,$mailBody,$mailObjectUid);			
			file_put_contents($generatedMailformFile, $mail);
			$this->view->setVar('compiledTemplatebodyRaw',$bodyRaw);
			$this->view->setVar('mailobjectuid',$mailObjectUid);
                        $this->view->setVar('mailobjecttitle',$mailobjectRecord->title);
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.$this->view->language;
			$this->view->setVar('source',$baseUri.'mails/mailobject_'.$mailObjectUid.'.html');
                        
                        if($mailObjectUid != $this->dispatcher->getParam("uid")){
                           echo($path.'/mailobjects/update/'.$mailObjectUid.'/'); 
                        }
			$this->view->disable();
                        
		}elseif($this->request->isPost() && !$this->dispatcher->getParam("uid") && $this->request->hasPost('dycont')){
			$this->dycontAction();
		}else{			
			$mailObjectUid = $this->dispatcher->getParam("uid");
			
			$mailobjectRecord = Mailobjects::findFirst(array(
			"conditions" => "uid = ?1",
			"bind" => array(1 => $mailObjectUid)
			));
			
			$contentObjects=$mailobjectRecord->getContentobjects(array(
				"conditions" => "deleted = 0 AND hidden =0",
				"order" => "templateposition ASC, positionsorting ASC"
				
			));
		
			//$usergroup=Usergroups::findFirstByUid($this->session->get('auth')['usergroup']);
			
			$templatedContentObjects = Templateobjects::find(array(
				"conditions" => "hidden=0 AND deleted=0 AND templatetype = ?2 AND usergroup = ?1",				
				"bind" => array(
					1 => $this->session->get('auth')['usergroup'],
					2 => 1
				),
				"group" => "nltool\Models\Templateobjects.uid"
				));
			
			$templatedContentDynamicObjects = Templateobjects::find(array(
				"conditions" => "hidden=0 AND deleted=0 AND templatetype = ?2 AND usergroup = ?1",				
				"bind" => array(
					1 => $this->session->get('auth')['usergroup'],
					2 => 2
				),
				"group" => "nltool\Models\Templateobjects.uid"
				));
			
			$availableContentObject=  Contentobjects::find(array(
				"conditions" => "contenttype=0 AND usergroup=?1 AND crdate > ?2 AND mailobjectuid = ?3",
				"bind" => array(1 => $this->session->get('auth')['usergroup'],2 => time()-2592000,3 => $mailobjectRecord->uid)
				
			));
			
			$fieldSubstituteContentObjects=Contentobjects::find(array(
				"conditions" => "deleted = 0 AND hidden=0 AND contenttype=1 AND usergroup=?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup'])
				
			));
			
			
			$templateFile=  '../app/modules/frontend/templates/template_mail_'.$mailobjectRecord->templateuid.'.volt';
			$bodyRaw=file_get_contents($templateFile);
			$body=$this->writeContentElements($bodyRaw, $contentObjects);
			$this->view->templatedCElements =$templatedContentObjects;
			$this->view->templatedDyElements=	$templatedContentDynamicObjects;
			$this->view->cElements=$availableContentObject;
			$this->view->setVar('compiledTemplatebodyRaw',$body);				
			$this->view->setVar('mailobjectuid',$mailObjectUid);
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.$this->view->language;
                        $this->view->setVar('mailobjecttitle',$mailobjectRecord->title);
			$this->view->setVar('source',$baseUri.'mails/mailobject_'.$mailObjectUid.'.html');
			$this->view->setVar('path',$path.'/mailobjects/update/');			
		}
		
		
		
	}
	
	public function deleteAction(){
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$object= Mailobjects::findFirstByUid($this->request->getPost('uid'));
				$object->assign(array(
					'tstamp' => time(),
					'deleted' =>1,
					'hidden' =>1
				));
				$object->update();
			}
			die();
		}
	}
	
	private function dycontAction(){
            switch($this->request->getPost('dycont')){
                case 1:
                    $this->dyTecparts();
                    break;
                case 2:
                    $this->dyTrackingtool();
                    break;
            }
            
		
	}
                       
        private function dyTecparts(){
            $chlead = curl_init();
		curl_setopt($chlead, CURLOPT_URL, 'https://www.tecparts.com/api/rest/article/getArticle?code='.$this->request->getPost('code'));
		curl_setopt($chlead, CURLOPT_PUT, true);		
		curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
		$chleadresult = curl_exec($chlead);		
		curl_close($chlead);
		echo('{"article":'.$chleadresult.',"code":"'.$this->request->getPost('code').'"}');
		$this->view->disable(); 
		die();
        }
	
         private function dyTrackingtool(){
            /* TODO ans Trackingtool anpassen */
            $chlead = curl_init();
		curl_setopt($chlead, CURLOPT_URL, 'https://www.tecparts.com/api/rest/article/getArticle?code='.$this->request->getPost('code'));
		curl_setopt($chlead, CURLOPT_PUT, true);		
		curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
		$chleadresult = curl_exec($chlead);		
		curl_close($chlead);
		echo('{"article":'.$chleadresult.',"code":"'.$this->request->getPost('code').'"}');
		$this->view->disable(); 
		die();
        }
        
	function writeContentElements($bodyRaw,$contentObjects){
		$contentPerPosition=array();
		foreach($contentObjects as $contentObject){
			if(isset($contentPerPosition[$contentObject->templateposition])){
			$contentPerPosition[$contentObject->templateposition].=$contentObject->sourcecode;
			}else{
				$contentPerPosition[$contentObject->templateposition]=$contentObject->sourcecode;
			}
		}
		foreach($contentPerPosition as $content){
			$bodyRaw=preg_replace('/({{editable begin}})(.*)({{editable end}})/siU', '<div class="editable">' .$content.'</div>', $bodyRaw, 1, $count);
		}
		
		$bodyRaw=preg_replace('/({{editable begin}})(.*)({{editable end}})/siU', '<div class="editable"></div>	', $bodyRaw);
		return $bodyRaw;
	}
	
	function editRenderMailVars($subject){
		$search=array("{{editable begin}}","{{editable end}}");
		$imageText=$this->translate("dropImageText");
		$textText=$this->translate("inputTextText");
		$htmlText=$this->translate("inputHtmlText");
		$contentText=$this->translate("dropContentElementsText");
		$replace=array(			
			'',
			''
			
		);
		$renderMailVars=str_replace($search, $replace, $subject);
		return $renderMailVars;
	}
	
	function getBasicContentElementsFromTemplate($body){
		
		$matches=array();
		preg_match_all('/{{editable begin}}(.*){{editable end}}/siU', $body, $matches);
		return $matches[1];
	}
	
	function renderMailVars($subject,$contentObjects){		
		$count=0;
		
		
		foreach($inputs as $text){		
			if($text != ''){
				//$regex = "/()(.*)({{editable end}})/is";
				$subject=preg_replace('/({{editable begin}})(.*)({{editable end}})/siU', $text, $subject, 1, $count);
				
			}
		}
		
		
		
		
		return $subject;
	}
	
	function renderMain($subject, $body, $mailobjectuid){
                $search=array('{{compiledTemplatebody}}','{{mailobjectuid}}');
		$replace=array($body,$mailobjectuid);
		$renderMain=str_replace($search, $replace, $subject);
		return $renderMain;
	}
	
	function getCompiler(){
		$compiler = new Compiler();
		$compiler->addFunction('headerdata', function($resolvedArgs, $exprArgs) {
			return '"<div class=\"editarea droparea headerdata\"></div>"';
		});
		$compiler->addFunction('image', function($resolvedArgs, $exprArgs) {
			$text=$this->translate("dropImageText");
			return '"<div class=\"editarea droparea image\">'.$text.'</div>"';
		});
		$compiler->addFunction('text', function($resolvedArgs, $exprArgs) {
			$text=$this->translate("inputTextText");
			return '"<textarea class=\"editarea text\">'.$text.'</textarea>"';
		});
		$compiler->addFunction('html', function($resolvedArgs, $exprArgs) {
			$text=$this->translate("inputHtmlText");
			return '"<textarea class=\"editarea html\">'.$text.'</textarea>"';
		});
		$compiler->addFunction('contentElements', function($resolvedArgs, $exprArgs) {
			$text=$this->translate("dropContentElementsText");
			return '"<div class=\"editarea contentElements\">'.$text.'</div>"';
		});		
		$compiler->addFunction('divider', function($resolvedArgs, $exprArgs) {
			return '"<div style=\"height:15px\">&nbsp;</div><!-- spacer -->"';
		});
		
		
		return $compiler;
	}
}