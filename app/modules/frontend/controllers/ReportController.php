<?php

namespace nltool\Modules\Modules\Frontend\Controllers;

use nltool\Models\Campaignobjects,
	nltool\Models\Sendoutobjects,
	nltool\Models\Mailqueue,
	nltool\Models\Linkclicks,
	nltool\Models\Addresses;

/**
 * Class IndexController
 *
 * @package baywa-nltool\Controllers
 */
class ReportController extends ControllerBase
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
	{
		$action='index';
		if(!$this->dispatcher->getParam("uid")){
			
			$campaigns=  Campaignobjects::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
                        
			$reportableCampaigns=array();
			foreach($campaigns as $campaign){
				if($campaign->hasReportableSendoutobjects()){
					$reportableCampaigns[]=$campaign;
				}
			}
			
                        $sendoutobjects=  Sendoutobjects::find(array(
				'conditions' => 'deleted=0 AND hidden=0 AND campaignuid=0 AND (inprogress=1 OR sent = 1) AND eventuid > 0'				
			));
			
                        $this->view->setVar('eventobjects',$sendoutobjects);
			$this->view->setVar('campaignobjects',$reportableCampaigns);
			$this->view->setVar('list',true);
		}else{
			$campaignobject=  Campaignobjects::findFirstByUid($this->dispatcher->getParam("uid"));
			$sendoutobjects=  Sendoutobjects::find(array(
				'conditions' => 'deleted=0 AND hidden=0 AND campaignuid=?1 AND (inprogress=1 OR sent = 1)',
				'bind'=>array(
					1=>$this->dispatcher->getParam("uid")
				)
			));
			$this->view->setVar('sendoutobjects',$sendoutobjects);
			$this->view->setVar('campaignobject',$campaignobject);
			$this->view->setVar('list',false);	
			$action='create';
		}
		
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language.'/report/'.$action;
		
		$this->view->setVar('path',$path);
	}
	
	public function createAction(){
		$this->assets->addJs('js/vendor/reportsInit.js');
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$baseUri=$this->config['application'][$environment]['staticBaseUri'];
		$path=$baseUri.$this->view->language;
		
		if(!$this->request->getQuery('linknumber') && !$this->request->getQuery('sendoutobjectuid')){
		$this->assets->addCss('css/jquery.dataTables.css');
		$sendoutobject=  Sendoutobjects::findFirst(array(
			'conditions'=>'uid=?1',
			'bind'=>array(
				1=>$this->dispatcher->getParam("uid")
			)
		));
		$mailqueue=Mailqueue::find(array(
			'conditions' => 'sendoutobjectuid=?1 AND deleted=0 AND hidden=0',
			'bind' => array(
				1=>$sendoutobject->uid
			)
		));
		$sent=0;
		foreach($mailqueue as $mailqueueEl){
			if($mailqueueEl->sent==1){
				$sent++;
			}
		}
		$opened=$sendoutobject->countOpenclicks(array('group'=>'addressuid'));
		$clicked=$sendoutobject->countLinkclicks();
		$linkClickCounts=$sendoutobject->countLinkclicks();
		$clicks=$sendoutobject->getLinkclicks();
		$clickArray=array();
		foreach($linkClickCounts as $linkClickCount){
			$clickArray[$linkClickCount->linkuid]=$linkClickCount->rowcount;
		}
		//arsort($clickArray);
		$totalclicks=0;
		foreach($clicked as $clickRow){
			$totalclicks+=$clickRow->rowcount;
		}
		
		$this->view->setVar('clickcounts',$clickArray);
		$this->view->setVar('opened',count($opened));
		$this->view->setVar('clicked',$totalclicks);
		$this->view->setVar('clicks',$clicks);
		$this->view->setVar('sendoutobject',$sendoutobject);
		$this->view->setVar('sent',$sent);
		$this->view->setVar('complete',count($mailqueue));
		
		
		
		$this->view->setVar('path',$path);
		}else{
			$csv="clickdate;email;lastname;firstname;salutation;title;city;zip;gender;uid".PHP_EOL;
			$modelsManager=$this->getDi()->getShared('modelsManager');		
				
		$queryStrng="SELECT nltool\Models\Linklookup.linknumber,nltool\Models\Linkclicks.uid, nltool\Models\Linkclicks.pid, nltool\Models\Linkclicks.tstamp, nltool\Models\Linkclicks.crdate, nltool\Models\Linkclicks.deleted, nltool\Models\Linkclicks.hidden, nltool\Models\Linkclicks.campaignuid, nltool\Models\Linkclicks.mailobjectuid, nltool\Models\Linkclicks.sendoutobjectuid, nltool\Models\Linkclicks.url, nltool\Models\Linkclicks.linkuid, nltool\Models\Linkclicks.addressuid FROM nltool\Models\Linkclicks LEFT JOIN nltool\Models\Linklookup ON nltool\Models\Linkclicks.linkuid = nltool\Models\Linklookup.uid WHERE nltool\Models\Linkclicks.sendoutobjectuid = ?1 AND nltool\Models\Linklookup.linknumber = ?2";	
		
		$sQuery=$modelsManager->createQuery($queryStrng);								
		
		$linkClicks = $sQuery->execute(array(
			1 => $this->request->getQuery('sendoutobjectuid'),
			2 => $this->request->getQuery('linknumber')
		));		
		
		
		
			
			//$linkClicks=Linkclicks::find($this->request->getQuery('linkuid'));			
			
			foreach($linkClicks as $linkClick){	
                            
				$clickAddress=Addresses::findFirstByUid($linkClick->addressuid);						
				$csvArray=array(
					date('d.m.Y. H:i:s',$linkClick->crdate),
					$clickAddress->email,
					$clickAddress->last_name,
					$clickAddress->first_name,
					$clickAddress->salutation,
					$clickAddress->title,
					$clickAddress->city,
					$clickAddress->zip,
					$clickAddress->gender,
                                        $clickAddress->uid
				);
				$csv .= implode(';',$csvArray);
				$csv.=PHP_EOL;
			}
			$time=time();
			$filename=$this->request->getQuery('linkuid').'_' .$time.'.csv';
			
			file_put_contents('../public/media/report-'.$filename,$csv);			
			$this->response->redirect($this->request->getScheme().'://'.$this->request->getHttpHost().$baseUri.'public/media/report-'.$filename, true);
			$this->view->disable();
			
		}
	}
}