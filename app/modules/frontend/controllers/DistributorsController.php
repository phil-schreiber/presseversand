<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Distributors as Distributors,
	nltool\Models\Addresses as Addresses,
	nltool\Models\Addressfolders as Addressfolders,
	nltool\Models\Segmentobjects,
	nltool\Models\Distributors_addressfolders_lookup,
	nltool\Models\Distributors_segmentobjects_lookup;
	

/**
 * Class DistributorsController
 *
 * @package baywa-nltool\Controllers
 */
class DistributorsController extends ControllerBase
{
	public function indexAction(){
		if($this->request->isPost()){
			$distributors = Distributors::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "cruser_id <> ".$this->session->get('auth')['uid'].",cruser_id,tstamp DESC"
			));
			$distributorsArray = array();
                         $userCounter=-1;
                        $olduser=0;
			foreach($distributors as $distributor){
                             if( $olduser !== $distributor->cruser_id){
                               $olduser= $distributor->cruser_id;
                               $userCounter++;
                            }
				$distributorAddresses=$distributor->countAddresses();
				
				
				$distributorsArray[$userCounter][]=array(
					'uid'=>$distributor->uid,
					'title'=>$distributor->title,
					'date' =>date('d.m.Y H:i',$distributor->tstamp),
					'addresscount'=>$distributorAddresses,
                                        'cruser' => $distributor->getCruser()->username,
				);
						
			}
			$returnJson=json_encode($distributorsArray);
			echo($returnJson);
			die();
		}else{
			$distributors = Distributors::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.$this->view->language.'/distributors/update/';
			$this->view->setVar('path',$path);
			$this->view->setVar('distributors',$distributors);
		}
	}

	public function createAction(){
		if($this->request->isPost()){
			$time=time();
			$distributor=new Distributors();
			$distributor->assign(array(
				'pid'=>0,				
				'tstamp' => $time,
				'crdate' => $time,
				'cruser_id' => $this->session->get('auth')['uid'],
				'deleted' => 0,
				'hidden' => 0,
				'usergroup' => $this->session->get('auth')['usergroup'],
				'title' => $this->request->getPost('title'),
				'hashtags'=>' ',
				
			));
			$relations=$this->createRelations();
			
			$distributor->segments=$relations['segments'];
			$distributor->addressfolders=$relations['folders'];
			
			if(!$distributor->save()){
				$this->flash->error($distributor->getMessages());
			}else{
				$this->flash->success("Distributor was created successfully");
			}
		}
		$addressfolders=Addressfolders::find(array(
			'conditions'=>'deleted=0 AND hidden=0 AND usergroup=?1',
			'bind'=>array(1=>$this->session->get('auth')['usergroup'])
		));
		
		
		$segmentobjects=  Segmentobjects::find(array(
			'conditions'=>'deleted=0 AND hidden=0 AND usergroup=?1',
			'bind'=>array(1=>$this->session->get('auth')['usergroup'])
		));
		
		$this->view->setVar('addressfolders',$addressfolders);
		$this->view->setVar('segmentobjects',$segmentobjects);
		
	}
	
	public function updateAction(){
		if($this->request->isPost()){
			
			$distributor=Distributors::findFirst(array(
				'conditions' => 'uid = ?1',
				'bind' => array(
					1=>$this->request->getPost('uid')
				)
			));
			
			$distributor->title = $this->request->getPost('title');
			
			
			$this->removeSegmentRelations($this->request->getPost('uid'));
			$this->removeFolderRelations($this->request->getPost('uid'));
			$relations=$this->createRelations();
			
			$distributor->segments=$relations['segments'];
			$distributor->addressfolders=$relations['folders'];
			if(!$distributor->update()){
				$this->flash->error($distributor->getMessages());
			}else{
				$this->flash->success("Distributor was updated successfully");
			}
		}else{
			$distributorUid=$this->dispatcher->getParam("uid")?$this->dispatcher->getParam("uid"):0;
			
			$distributor=Distributors::findFirst(array(
				'conditions' => 'uid = ?1',
				'bind' => array(
					1=>$distributorUid
				)
			));
		}	
			$distributorSegments=$distributor->getSegments();
			$distributorSegmentsArray=array();
			foreach($distributorSegments as $distributorSegment){
				$distributorSegmentsArray[]=$distributorSegment->uid;
			}
			$distributorFolders=$distributor->getAddressfolders();
			$distributorFoldersArray=array();
			foreach($distributorFolders as $distributorFolder){
				$distributorFoldersArray[]=$distributorFolder->uid;
			}
			
			
			$addressfolders=Addressfolders::find(array(
				'conditions'=>'deleted=0 AND hidden=0 AND usergroup=?1',
				'bind'=>array(1=>$this->session->get('auth')['usergroup'])
			));


			$segmentobjects=  Segmentobjects::find(array(
				'conditions'=>'deleted=0 AND hidden=0 AND usergroup=?1',
				'bind'=>array(1=>$this->session->get('auth')['usergroup'])
			));
			
			$this->view->setVar('distributorFoldersArray',$distributorFoldersArray);
			$this->view->setVar('distributorSegmentsArray',$distributorSegmentsArray);
			$this->view->setVar('addressfolders',$addressfolders);
			$this->view->setVar('segmentobjects',$segmentobjects);
			$this->view->setVar('distributor',$distributor);
		
		
	}
	
	public function deleteAction(){
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$object=  Distributors::findFirstByUid($this->request->getPost('uid'));
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
	
	
	private function createRelations(){
			$addressfolderArr=array();
			$segmentsArr=array();
			$folderBindArray=array();
			$folderInStrng='';
			if($this->request->getPost('addressfolders')){
				foreach($this->request->getPost('addressfolders') as $key=>$value){
					$folderInStrng.='?'.$key.',';
					$folderBindArray[$key]=$value;
				}
				$addressfolders=  Addressfolders::find(array(
					'conditions' => 'uid IN ('.substr($folderInStrng,0,-1).')',
					'bind' => $folderBindArray

				));


				foreach ($addressfolders as $addressfolder){								
					$addressfolderArr[]=$addressfolder;				
				}
			}
			if($this->request->getPost('segmentobjects')){
				$segmentBindArray=array();
				$segmentInStrng='';
				foreach($this->request->getPost('segmentobjects') as $key => $value){
					$segmentInStrng.='?'.$key.',';
					$segmentBindArray[$key]=$value;
				}
				$segments=  Segmentobjects::find(array(
					'conditions' => 'uid IN ('.substr($segmentInStrng,0,-1).')',
					'bind' => $segmentBindArray
				));

				foreach($segments as $segment){
					$segmentsArr[]=$segment;
				}
			}
		return array('folders'=>$addressfolderArr,'segments'=>$segmentsArr);
	}
	
	private function removeSegmentRelations($uid){
		$relations=Distributors_segmentobjects_lookup::find(array(
			"conditions" => "uid_local = ?1",
			"bind" => array(
				1 => $uid
			)
		));
		
		foreach($relations as $relation){
			$relation->delete();
		}
	}
	
	private function removeFolderRelations($uid){
		$relations=Distributors_addressfolders_lookup::find(array(
			"conditions" => "uid_local = ?1",
			"bind" => array(
				1 => $uid
			)
		));
		
		foreach($relations as $relation){
			$relation->delete();
		}
	}
	
}