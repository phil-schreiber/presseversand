<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Feuserscategories,
	Phalcon\Tag,
	nltool\Forms\FeuserscategoriesForm as FeuserscategoriesForm;
	

/**
 * Class FeuserscategoriesController
 *
 * @package baywa-nltool\Controllers
 */
class FeuserscategoriesController extends ControllerBase
{
	public function indexAction()
	{
		 //$this->flashSession->error('Page not found: ' . $this->escaper->escapeHtml($this->router->getRewriteUri()));
		$feuserscategories=Feuserscategories::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
		
			 
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language.'/feuserscategories/update/';
		
		
		$this->view->setVar('feuserscategories',$feuserscategories);
		$this->view->setVar('path',$path);
		
	}
	
	public function createAction()
	{
		 
	 if($this->request->isPost())
		{
			$time=time();
			$feuserscategories = new Feuserscategories();
			$feuserscategories->assign(array(				
					'pid' =>0,
					'deleted'=>0,
					'hidden' => 0,
					'tstamp' => $time,				
					'crdate' => $time,
					'cruser_id' => $this->session->get('auth')['uid'],
					'usergroup' => $this->session->get('auth')['usergroup'],
					'title' => $this->request->getPost('title','striptags')					
				));


			 if (!$feuserscategories->save()) {
				  $this->flash->error($feuserscategories->getMessages());
			 } 


		}
	
		 
	}
	
	public function updateAction(){
		if(!$this->request->isPost()){
			$feuserscategoriesUid = $this->dispatcher->getParam("uid");
			$feuserscategoriesRecord = Feuserscategories::findFirstByUid($feuserscategoriesUid);
			
		}else{
			$feuserscategoriesUid =$this->request->getPost('uid', 'int');
			
			$feuserscategoriesRecord = Feuserscategories::findFirstByUid($feuserscategoriesUid);
			$time=time();
			
				
			
			
			$feuserscategoriesRecord->assign(array(								
				'tstamp' => $time,												
				'title' => $this->request->getPost('title','striptags')				
			));
			
			
			 if (!$feuserscategoriesRecord->update()) {
                $this->flash->error($feuserscategoriesRecord->getMessages());
            } else {

                $this->flash->success("User was updated successfully");

                Tag::resetInput();
            }
		}
		
		
		
		$this->view->form = new FeuserscategoriesForm($feuserscategoriesRecord, array(
            'edit' => true
        ));
		
	}
	
	public function deleteAction()
	{
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$feuserscategoriesRecord = Feuserscategories::findFirstByUid($this->request->getPost('uid'));
				$feuserscategoriesRecord->assign(array(
					'deleted' => 1,
					'hidden' => 1
				));
				$feuserscategoriesRecord->update();
			}
		}
		
	}
	
}
