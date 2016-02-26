<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Segmentobjects as Segmentobjects,
	nltool\Models\Segmentobjectsconditions,
	nltool\Models\Addressfolders as Addressfolders,
	nltool\Models\Feuserscategories;	

/**
 * Class SegmentobjectsController
 *
 * @package baywa-nltool\Controllers
 */
class SegmentobjectsController extends ControllerBase
{
	function indexAction(){
		if($this->request->isPost()){
			$result=$this->getData();
			$output=json_encode($result,true);			
			die($output);
		}else{
			$segments=  Segmentobjects::find(array(
				'conditions' => 'deleted=0 AND hidden =0 AND usergroup = ?1',
				'bind' => array(
					1 => $this->session->get('auth')['usergroup']
				)
			));
			$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.$this->view->language.'/segmentobjects/update/';
			$this->view->setVar('segments',$segments);
			$this->view->setVar('path',$path);
		}
		
		
	}
	
	function createAction(){
		
		$this->assets->addCss('css/jquery.dataTables.css');
		$this->assets->addJs('js/vendor/segmentobjectsInit.js');
		$addressfolders=Addressfolders::find(array(
			'conditions'=>"deleted=0 AND hidden=0 AND usergroup=?1",
			'bind'=>array(
				1 => $this->session->get('auth')['usergroup']
				
			)
		));
		$feuserscategories = Feuserscategories::find(array(
			'conditions' => "deleted=0 AND hidden=0 AND usergroup=?1",
			'bind' => array(
				1 => $this->session->get('auth')['usergroup']
			)
		));
		
		$this->view->setVar('addressfolders',$addressfolders);
		$this->view->setVar('feuserscategories',$feuserscategories);
				
	}
	
	function updateAction(){
		$this->assets->addCss('css/jquery.dataTables.css');
		$this->assets->addJs('js/vendor/segmentobjectsInit.js');
		$addressfolders=Addressfolders::find(array(
			'conditions'=>"deleted=0 AND hidden=0 AND usergroup=?1",
			'bind'=>array(
				1 => $this->session->get('auth')['usergroup']
				
			)
		));
		$feuserscategories = Feuserscategories::find(array(
			'conditions' => "deleted=0 AND hidden=0 AND usergroup=?1",
			'bind' => array(
				1 => $this->session->get('auth')['usergroup']
			)
		));
		
		$segmentobjectUid=$this->dispatcher->getParam("uid")?$this->dispatcher->getParam("uid"):0;
		
		$segmentobject= Segmentobjects::findFirst(array(
			'conditions' => 'deleted = 0 AND hidden = 0 AND uid = ?1',
			'bind' => array(
				1 => $segmentobjectUid
			)
		));
		$this->view->setVar('segmentobject',$segmentobject);
		$this->view->setVar('addressfolders',$addressfolders);
		$this->view->setVar('feuserscategories',$feuserscategories);
	}
	
	public function deleteAction(){
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$object=  Segmentobjects::findFirstByUid($this->request->getPost('uid'));
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
	
	private function getData(){
		$bindArray=array();
		$filterFieldsArray=array();
		$aColumns=array('email','lastname',	'firstname','salutation','title','company','phone','address','city','zip','userlanguage','gender');
        
        $aColumnsSelect=array('email', 'last_name AS lastname', 'first_name AS firstname', 'salutation', 'title', 'company', 'phone', 'address', 'city', 'zip', 'userlanguage', 'gender' );
        $aColumnsFilter=array('email', 'last_name', 'first_name', 'salutation', 'title', 'company', 'phone', 'address', 'city', 'zip', 'userlanguage', 'gender' );
		$time=time();
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "addresses.uid";

		/* DB table to use */
		$sTable = "nltool\Models\Addresses AS addresses LEFT JOIN nltool\Models\Addresses_feuserscategories_lookup AS cats ON addresses.uid = cats.uid_local";
			/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_POST['iDisplayStart'] ).", ".
				intval( $_POST['iDisplayLength'] );
		}


		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_POST['iSortCol_0'] ) )
		{
			$dateSort=false;
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_POST['iSortingCols'] ) ; $i++ )
			{
				if ( $_POST[ 'bSortable_'.intval($_POST['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_POST['iSortCol_'.$i] ) ]." ".
						($_POST['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
					if('date'==$aColumns[ intval( $_POST['iSortCol_'.$i] ) ]){
						$dateSort=true;
					}

				}
			}

			$sOrder=  substr($sOrder, 0,-2).' ';
			
		}


		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		
		
		if(is_array($this->request->getPost('folderuid'))){
			$insStrng=" AND pid IN (";
			foreach($this->request->getPost('folderuid') as $key => $value){
				$bindArray[$key]=$value;
				$insStrng.='?'.$key.',';
				$filterFieldsArray['pid'][]=$value;
			}
		$insStrng=substr($insStrng,0,-1).")";
		}else{
			$insStrng="";
		}
		
		if(is_array($this->request->getPost('feuserscategories'))){
			$insStrng=" AND uid_foreign IN (";
			foreach($this->request->getPost('feuserscategories') as $key => $value){
				$bindArray[$key]=$value;
				$insStrng.='?'.$key.',';
				$filterFieldsArray['uid_foreign'][]=$value;
			}
		$insStrng=substr($insStrng,0,-1).")";
		}else{
			$insStrng="";
		}
		
		$filters='';
		foreach($aColumns as $filterKey => $filtername){
			
			if(null!==$this->request->getPost($filtername)){
				
			$filterArray=explode(',',$this->request->getPost($filtername));
			
			$filters.=' AND (';
			
			foreach($filterArray as $key => $value){
				$filterFieldsArray[$filtername][]=$value;
				$filters.=$aColumnsFilter[$filterKey].' LIKE :'.$filtername.$key.': OR ';
				$bindArray[$filtername.$key]=$value;
			}
			$filters=substr($filters,0,-4).')';
			
			
		}
		}
		
		$usergroup=$this->session->get('auth');
		$sWhere = "WHERE addresses.deleted=0 AND hidden=0 AND usergroup = ".$usergroup['usergroup'].$insStrng.$filters;
		
		if ( isset($_POST['sSearch']) && $_POST['sSearch'] != "" )
		{
			$filterFieldsArray['searchterm']=$_POST['sSearch'];
			$sWhere .= " AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= "".$aColumnsFilter[$i]." LIKE :searchterm: OR "; //$_POST['sSearch']
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}

		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}

				$sWhere .= "".$aColumnsFilter[$i]." LIKE '%:".$aColumnsFilter[$i].'_'.$i."%' "; //$_POST['sSearch_'.$i]
				$bindArray[$aColumnsFilter[$i].'_'.$i]=$this->request->getPost('sSearch_'.$i);
			}
		}

		
		

		/*
		 * SQL queries
		 * Get data to display
		 */
		
		$phql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumnsSelect)).", addresses.uid as userid FROM $sTable ".$sWhere." GROUP BY email ".$sOrder." ".$sLimit;
		
		
		
		if($this->request->getPost('sSearch')!=''){
			$bindArray['searchterm']='%'.$this->request->getPost('sSearch').'%';			
		}
		
		$sQuery=$this->modelsManager->createQuery($phql);
		
		
		$resultSet=array();
		$rResults = $sQuery->execute($bindArray);		
		
		/*For convinience Saving is located here, where the Query assembly is located*/
		if($this->request->getPost('save')==1){
			$time=time();
			$segment=new Segmentobjects();
			$segment->assign(array(
				"pid" => 0,
				"tstamp" => $time,
				"crdate" => $time,
				"cruser_id"=>$this->session->get('auth')['uid'],
				"usergroup" => $this->session->get('auth')['usergroup'],
				"deleted" => 0,
				"hidden" => 0,
				"title"	=> $this->request->getPost('segmenttitle')?:'no name',
				"hashtags" => '',
				"querystring" => "SELECT ".str_replace(" , ", " ", implode(", ", $aColumnsSelect)).", addresses.uid as userid FROM $sTable ".$sWhere." GROUP BY email ".$sOrder,
				"wherestatement" => $sWhere,
				"stateobject" => $this->request->getPost('stateObject'),
				"bindarray" => json_encode($bindArray)
			));
			
			if(!$segment->save()){
			$this->flash->error($segment->getMessages());	
			}			
			foreach($filterFieldsArray as $field =>$vals){
				if(is_array($vals)){
					foreach($vals as $val){
						$segmentcondition=new Segmentobjectsconditions();
						$segmentcondition->assign(array(
							"pid" => $segment->uid,
							"tstamp" => $time,
							"crdate" => $time,
							"cruser_id"=>$this->session->get('auth')['uid'],
							"usergroup" => $this->session->get('auth')['usergroup'],
							"deleted" => 0,
							"hidden" => 0,							
							"field" => $field,
							"searchvalue" => $val
						));
						$segmentcondition->save();
					}
				}else{
					$segmentcondition=new Segmentobjectsconditions();
					$segmentcondition->assign(array(
						"pid" => $segment->uid,
						"tstamp" => $time,
						"crdate" => $time,
						"cruser_id"=>$this->session->get('auth')['uid'],
						"usergroup" => $this->session->get('auth')['usergroup'],
						"deleted" => 0,
						"hidden" => 0,
						"field" => $field,
						"searchvalue" => $vals
					));
					$segmentcondition->save();
				}
			}
		}
		/*For convinience Updating is located here, where the Query assembly is located*/
		if($this->request->getPost('update')==1){
			$segmentRecord = Segmentobjects::findFirst(array(
				'conditions' => 'deleted = 0 AND hidden = 0 AND uid = ?1',
				'bind' => array(
					1 => $this->request->getPost('segmentobjectUid')
				)
			));
			$segmentRecord->assign(array(
				'time' => time(),
				"title"	=> $this->request->getPost('segmenttitle')?:'no name',
				'querystring' => "SELECT ".str_replace(" , ", " ", implode(", ", $aColumnsSelect)).", addresses.uid as userid FROM $sTable ".$sWhere." GROUP BY email ".$sOrder,
				'wherestatement' => $sWhere,
				'stateobject' => $this->request->getPost('stateObject'),
				'bindarray' => json_encode($bindArray)
			));			
			$segmentRecord->update();
			$conditions=$segmentRecord->getConditions();
			foreach($conditions as $condition){
				$condition->delete();
			}
			
			foreach($filterFieldsArray as $field =>$vals){
				if(is_array($vals)){
					foreach($vals as $val){
						$segmentcondition=new Segmentobjectsconditions();
						$segmentcondition->assign(array(
							"pid" => $segmentRecord->uid,
							"tstamp" => $time,
							"crdate" => $time,
							"cruser_id"=>$this->session->get('auth')['uid'],
							"usergroup" => $this->session->get('auth')['usergroup'],
							"deleted" => 0,
							"hidden" => 0,
							"field" => $field,
							"searchvalue" => $val
						));
						$segmentcondition->save();
					}
				}else{
					$segmentcondition=new Segmentobjectsconditions();
					$segmentcondition->assign(array(
						"pid" => $segmentRecord->uid,
						"tstamp" => $time,
						"crdate" => $time,
						"cruser_id"=>$this->session->get('auth')['uid'],
						"usergroup" => $this->session->get('auth')['usergroup'],
						"deleted" => 0,
						"hidden" => 0,
						"field" => $field,
						"searchvalue" => $vals
					));
					$segmentcondition->save();
				}
			}
		}
			
		foreach ( $rResults as $aRow )
		{	
			$row = array();
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
			
				
					/* General output */
					$rowArray=(array)$aRow;
					$row[] = $rowArray[ $aColumns[$i] ];
				
			
					
			}
			$resultSet[] = $row;
		}
		
		/* Data set length after filtering */		
		
		$rResultFilterTotal = count($resultSet);
		
		/* Total data set length */
		$lphql = "SELECT COUNT(".$sIndexColumn.") AS countids FROM $sTable	".$sWhere;
		//echo($sWhere);
		$lQuery=$this->modelsManager->createQuery($lphql);
		$rResultTotal = $lQuery->execute($bindArray);        
		foreach ( $rResultTotal as $aRow )
		{
			$iTotal = (array)$aRow;
		}
		$iTotal=$iTotal['countids'];
	//$GLOBALS['TYPO3_DB']->sql_fetch_assoc($rResultTotal);
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_POST['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iTotal,
			"aaData" => $resultSet
		);
		
		
		
		
		
	return  $output;
        
    }
}