<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Sendoutobjects,
	nltool\Models\Addressconditions;

/**
 * Class IndexController
 *
 * @package baywa-nltool\Controllers
 */
class AddressconditionsController extends ControllerBase
{
	public function indexAction(){
		
	}
	public function deleteAction(){
		if($this->request->isPost() && $this->request->hasPost('domid')){
			$domid=$this->request->getPost('domid');
			$parentSendoutObj=  Sendoutobjects::findFirst(array(
					'conditions'=>'deleted=0 AND hidden=0 AND domid=?1 AND campaignuid=?2',
					'bind' => array(
						1=>$domid,
						2=>$this->request->getPost('campaignobjectuid')
					)
				));
				if($parentSendoutObj){
					$clickCond= Addressconditions::findFirstByPid($parentSendoutObj->uid);
					if($clickCond){
						$clickCond->assign(array(
							'deleted' => 1,
							'hidden' => 1
						));
						$clickCond->update();
					}
				}
				die();
		}
	}
}