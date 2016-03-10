<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Templateobjects as Templateobjects,
	nltool\Models\Usergroups as Usergroups,
	nltool\Forms\TemplateobjectsForm as TemplateobjectsForm,			
	Phalcon\Tag,
	Phalcon\Image,
	Phalcon\Image\Adapter\GD as GDAdapter,		
	DOMDocument as DOMDocument;


class TemplateobjectsController extends ControllerBase
{
	 /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
    {
        
		$usergroup=Usergroups::findFirstByUid($this->session->get('auth')['usergroup']);
        $pagetemplateobjects=$usergroup->getTemplateobjects(array(
				"conditions" => "hidden=0 AND deleted=0 AND templatetype = 0",				
				"group" => "nltool\Models\Templateobjects.uid",
				"order" => "tstamp DESC"
				));
		
		$contenttemplateobjects= $usergroup->getTemplateobjects(array(
				"conditions" => "hidden=0 AND deleted=0 AND templatetype = 1",				
				"group" => "nltool\Models\Templateobjects.uid",
				"order" => "tstamp DESC"
				));
		$dynamictemplateobjects=$usergroup->getTemplateobjects(array(
				"conditions" => "hidden=0 AND deleted=0 AND templatetype = 2",				
				"group" => "nltool\Models\Templateobjects.uid",
				"order" => "tstamp DESC"
				));
		
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language.'/templateobjects/update/';
		
		$this->view->setVar('dynamictemplateobjects',$dynamictemplateobjects);
		$this->view->setVar('contenttemplateobjects',$contenttemplateobjects);
		$this->view->setVar('pagetemplateobjects',$pagetemplateobjects);
		$this->view->setVar('path',$path);				
    }
	
	public function createAction(){
		
		/*$file=  file_get_contents('../app/modules/frontend/templates/newsletterMainTemplate.volt');
		$compiled= $compiler->parse($file);*/
		//echo('<iframe src="http://localhost/baywa-nltool/public/templates/newsletterMainTemplate.volt.php"></iframe>');
		$this->host='http://'.$this->request->getServer('HTTP_HOST');
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$this->baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$this->baseUri.$this->view->language;
		//$this->view->setVar('source',$path.$mailObjectUid.'.html');						
		$this->view->setVar('path',$path);	
		if($this->request->isPost()){
			$time=time();
			
		
			
			
			
			$templateObject=new Templateobjects();
			//$templatefilepath=$_POST['templatefilepath']=='' ? ' ' : $_POST['templatefilepath'];
			$templateObject->assign(array(				
				'tstamp' => $time,				
				'crdate' => $time,
				'cruser_id' => $this->session->get('auth')['uid'],
				'usergroup' => $this->session->get('auth')['usergroup'],
				'title' => $_POST['title'],
				'sourcecode' => ' ',
				'templatefilepath' => ' ',
				'templatetype' => $_POST['templatetype'],
			));
			
			$usergroupObj=Usergroups::findFirstByUid($this->session->get('auth')['usergroup']);
			$templateObject->usergroups=$usergroupObj;
			
			 if (!$templateObject->save()) {
                $this->flash->error($templateObject->getMessages());
            } else {				
				
				if ($this->request->hasFiles() == true) {                    
					$saveFilename=$this->processImage($templateObject->uid,$time);                    																	
					$templateObject->templatefilepath=$saveFilename;
					
				}
				$processedSourceCode=$this->processSourcecode($templateObject->uid);
				$templateObject->sourcecode=$processedSourceCode;
				$templateObject->update();
				
                $this->flash->success("Template was created successfully: ");
            }

            Tag::resetInput();
		}
		
	}
	
	public function updateAction(){
		$time=time();
		$this->host='http://'.$this->request->getServer('HTTP_HOST');
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$this->baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$this->baseUri.$this->view->language;
			//$this->view->setVar('source',$path.$mailObjectUid.'.html');						
			
		if(!$this->request->isPost()){
			$templateobjectUid = $this->dispatcher->getParam("uid");
			$templateObject = Templateobjects::findFirstByUid($templateobjectUid);
			
		}else{
			$templateobjectUid = $this->request->getPost("uid","int");
			$templateObject = Templateobjects::findFirstByUid($templateobjectUid);
			$templateObject->assign(array(				
				'tstamp' => $time,								
				'cruser_id' => $this->session->get('auth')['uid'],
				'usergroup' => $this->session->get('auth')['usergroup'],
				'title' => $_POST['title'],				
				'templatetype' => $_POST['templatetype']
			));
			if ($this->request->hasFiles() == true) {                    
					$saveFilename=$this->processImage($templateObject->uid,$time);                    																	
					$templateObject->templatefilepath=$saveFilename;
					
			}
			$processedSourceCode=$this->processSourcecode($templateObject->uid);
			$templateObject->sourcecode=$processedSourceCode;
                         if (!$templateObject->update()) {
                             $this->flash->success("Template was updated successfully: ");
                         }
				
            
			
		}
		$this->view->setVar('templateobject',$templateObject);
		$this->view->setVar('path',$path);	
		
		
	}
	
	public function deleteAction(){
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$templateobject=Templateobjects::findFirstByUid($this->request->getPost('uid'));
				$templateobject->assign(array(
					'tstamp' => time(),
					'deleted' =>1,
					'hidden' =>1
				));
				$templateobject->update();
			}
			die();
		}
	}
	
	private function processSourcecode($uid){
		
		if($_POST['templatetype'] == 1){
			$generatedTemplateFileName='../app/modules/frontend/templates/template_content_'.$uid.'.volt';
		}else{
			$generatedTemplateFileName='../app/modules/frontend/templates/template_mail_'.$uid.'.volt';
		}
		$dummyImage=$this->host.$this->baseUri.'/public/images/dummy-image.jpg';
		/*Updating the body to make images work*/
				$dom = new DOMDocument('1.0', 'utf-8');
				$postCode = mb_convert_encoding($_POST['sourcecode'], 'HTML-ENTITIES', "UTF-8"); 
				@$dom->loadHTML($postCode);
				$images = $dom->getElementsByTagName('img');
				$counter=0;
				foreach ($images as $image) {
						$src=$image->getAttribute('src');
						if(substr($src,0,4)=='http'){
							$path='../public/images/templates/template_mail_'.$uid;
							if (!is_dir($path)) {
								// dir doesn't exist, make it
								mkdir($path);
							  }							
							$file=file_get_contents($src);
							$nameArray=explode('.',$src);
							$extension=$nameArray[(count($nameArray)-1)];
							$filename=$path.'/image_'.$counter.'.'.$extension;
							$image->setAttribute('src',$this->host.$this->baseUri.substr($filename,3));
							file_put_contents($filename,$file);
							chmod($filename, 0755);  
						}else{
							$image->setAttribute('src',$dummyImage);
						}
						$counter++;
				}
				
				
				if($_POST['templatetype'] > 0){
					$html=preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML($dom->documentElement));
					if($_POST['templatetype'] > 1){
						$html=preg_replace('/{{content}}/', '<div class="dyContentPlaceholder"></div>', $html);
					}
					$html='<div class="cElement">'.$html.'</div>';
				}else{
					$html=preg_replace('~<(?:!DOCTYPE|/?(?:html))[^>]*>\s*~i', '', $dom->saveHTML($dom->documentElement));
				}
				
				file_put_contents($generatedTemplateFileName,urldecode(html_entity_decode($html,ENT_QUOTES,'UTF-8')));
				
				return urldecode(html_entity_decode($html,ENT_QUOTES,'UTF-8'));
		
	}
	private function processImage($uid,$time){
		foreach ($this->request->getUploadedFiles() as $file){
						$nameArray=explode('.',$file->getName());
						$filetype=$nameArray[(count($nameArray)-1)];
						$tmpFile='../public/images/templateThumbnails/template_'.$uid.'_S.'.$filetype;
                                                $saveFilename='public/images/templateThumbnails/template_'.$uid.'_S.'.$filetype;
						$file->moveTo($tmpFile);
						
						/*$thumbFilenameS='../public/images/templateThumbnails/template_'.$uid.'_S.'.$filetype;
						$thumbFilenameL='../public/images/templateThumbnails/template_'.$uid.'_L.'.$filetype;
						$saveFilename='public/images/templateThumbnails/template_'.$uid.'_S.'.$filetype;
						
						$imageS = new GDAdapter($tmpFile);
						$imageS->resize(300,1000);
						$imageS->save($thumbFilenameS);
						$imageL = new GDAdapter($tmpFile);
						$imageL->resize(600,1000);
						$imageL->save($thumbFilenameL);*/
                      
						 //unlink($tmpFile);
              }
			     
		return $saveFilename;
	}
}