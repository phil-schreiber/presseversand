<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use nltool\Models\Addresses as Addresses,
	nltool\Models\Addressfolders as Addressfolders;
	

/**
 * Class AddressfoldersController
 *
 * @package baywa-nltool\Controllers
 */
class AddressfoldersController extends ControllerBase
{
	public $_divider= array(';',',',':','	');
	public $_dataWrap=array(
			0 => false,
			1 => '"',
			2 => "'"
		);
	public function indexAction(){
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
			$path=$baseUri.$this->view->language;
		if($this->request->isPost()){
			$adressfolders = Addressfolders::find(array(
				"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
			));
			$addressfoldersArray = array();
			foreach($adressfolders as $adressfolder){
				$adressfolderAddresses=$adressfolder->countAddresses();
				$addressfoldersArray[]=array(
					'uid'=>$adressfolder->uid,
					'title'=>$adressfolder->title,
					'date' =>date('d.m.Y',$adressfolder->tstamp),
					'addresscount'=>$adressfolderAddresses
				);
						
			}
			$returnJson=json_encode($addressfoldersArray);
			echo($returnJson);
			die();
		}else{
			if($this->dispatcher->getParam('uid') && !$this->request->getQuery('downloadunsubscribes')){
                            
				$this->assets->addCss('css/jquery.dataTables.css');
				$this->assets->addJs('js/vendor/addressfoldersInit.js');
				$addressfolder=Addressfolders::findFirst(array(
					'conditions'=>"deleted=0 AND hidden=0 AND usergroup=?1 AND uid = ?2",
					'bind'=>array(
						1 => $this->session->get('auth')['usergroup'],
						2 => $this->dispatcher->getParam('uid')
					)
				));
				$this->view->setVar('foldertitle',$addressfolder->title);
				$this->view->setVar('folderuid',$addressfolder->uid);
				$this->view->setVar('detail',true);
                                $this->view->setVar('path',$path);
                        }elseif($this->dispatcher->getParam('uid') && $this->request->getQuery('downloadunsubscribes')){
                            $unsubscribeAddresses=  Addresses::find(array(
                               "conditions" => "deleted=1 AND hidden = 1 AND pid = ?1",
                                "bind" => array(
                                    1 => $this->dispatcher->getParam('uid')
                                )
                            ));
                            $csv='';
                            foreach($unsubscribeAddresses as $address){
                                $csv.= date('d.m.Y. H:i:s',$address->tstamp).';'.$address->email.PHP_EOL;
                            }
                            $time=time();
                            $filename=$this->request->getQuery('linkuid').'_' .$time.'.csv';

                            file_put_contents('../public/media/unsubscribes-'.$filename,$csv);			
                            $this->response->redirect($this->request->getScheme().'://'.$this->request->getHttpHost().$baseUri.'public/media/unsubscribes-'.$filename, true);                            
                            $this->view->disable();
                            //unlink('../public/media/unsubscribes-'.$filename);
                        }
                        else{
				$addressfolders = Addressfolders::find(array(
					"conditions"=>"deleted=0 AND hidden=0 AND usergroup =?1",
					"bind" => array(1=>$this->session->get('auth')['usergroup']),
					"order" => "tstamp DESC"
				));

				$this->view->setVar('addressfolders',$addressfolders);
				$this->view->setVar('detail',false);
				$this->view->setVar('path',$path);
			}
		}
	}
	
	public function createAction(){
		$time=time();
		$this->assets->addJs('js/vendor/addressesInit.js');
		$addressfoldersRecords=Addressfolders::find(array(
			"conditions" => "deleted=0 AND hidden=0 AND usergroup = ?1",
				"bind" => array(1 => $this->session->get('auth')['usergroup']),
				"order" => "tstamp DESC"
		));
		$this->view->setVar('addressfolders',$addressfoldersRecords);
		$this->view->setVar('filehideshow','');
		$this->view->setVar('maphideshow','hidden');
		if($this->request->isPost()){			
			
			$this->view->setVar('filehideshow','hidden');
			$this->view->setVar('maphideshow','');
			
			
				if ($this->request->hasFiles() == true){
					
					$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/octet-stream');

					$fileArray=$this->request->getUploadedFiles();
					$file=$fileArray[0];
					
					//if(in_array($file->getType(), $mimes)){
						$nameArray=explode('.',$file->getName());
						$filetype=$nameArray[(count($nameArray)-1)];
						$tmpFile='../app/cache/tmp/'.$time.'_'.$file->getName();
						$file->moveTo($tmpFile);
						$row=0;
						
						if (($handle = fopen($tmpFile, "r")) !== FALSE) {
							$fileRowField=array();
							
							
							
							if($this->request->hasPost('firstRowFieldNames')){								
								$data[$row] = $this->getCsvWrapper($handle, 1000, $this->_divider[$this->request->getPost('divider')],$this->_dataWrap[$this->request->getPost('dataFieldWrap')]);								
								$fileRowField=array_values($data[$row]);
							}else{
								
								while($row < 3){
									$data[$row] = $this->getCsvWrapper($handle, 1000, $this->_divider[$this->request->getPost('divider')],$this->_dataWrap[$this->request->getPost('dataFieldWrap')]);																	
									$row++;		
								}
								
								for($i=0; $i<count($data[0]); $i++){
									
									$fileRowField[]=$data[0][$i].'<br>'.$data[1][$i].'<br>'.$data[2][$i];
								}
								
								
							}
							fclose($handle);
							
							
						}else{
							die('Failed');
						}					
					//}
					$this->view->setVar('divider',$this->request->getPost('divider'));
					$this->view->setVar('dataFieldWrap',$this->request->getPost('dataFieldWrap'));
					$this->view->setVar('tstamp',$time);
					$this->view->setVar('firstRowFieldNames', ($this->request->hasPost('firstRowFieldNames') ? 1 :0));
					$this->view->setVar('filename',$file->getName());
					$this->view->setVar('uploadfields',$fileRowField);
				}else{
					$time=time();
					if($this->request->getPost('addressfolderCreate') != '' && $this->request->getPost('addressFoldersUid') ==0){
						/*create the segment*/
						$addressfolder=new Addressfolders();
						$addressfolder->assign(array(
							'pid'=>0,
							'deleted'=>0,
							'hidden'=>0,
							'tstamp'=>$time,
							'crdate'=>$time,
							'cruser_id' => $this->session->get('auth')['uid'],
							'usergroup' => $this->session->get('auth')['usergroup'],
							'title'=>$this->request->getPost('addressfolderCreate','striptags'),
							'hashtags'=>'' //TODO
						));
						if (!$addressfolder->save()) {
							$this->flash->error($addressfolder->getMessages());
						}
					}else{
						$addressfolder=  Addressfolders::findFirst(array(
							'conditions'=>'uid=?1',
							'bind' => array(
								1=>$this->request->getPost('addressFoldersUid')
							)
						));
                                                if($this->request->hasPost('deleteallexisting')){
                                                    $this->eraseFoldAdd($addressfolder->uid);
                                                }
						$addressfolder->assign(array(
								'tstamp'=>$time
								));
						$addressfolder->update();
					}
						
						

						$row=0;
						$insStr='';
						$addressesDBFielMap=array(
							1=>'first_name',
							2=>'last_name',
							3=>'title',
							4=>'salutation',
							5=>'email',
							6=>'company',
							7=>'phone',
							8=>'address',
							9=>'city',
							10=>'zip',
							11=>'userlanguage',
							12=>'gender'
						);
						//Using Address Segment n:1 relation; lookup is there, but no mass insert possible 
						$insField='(pid,tstamp,crdate,cruser_id,usergroup';
						$indexArray=array();
						foreach($this->request->getPost('adressFieldsMap') as $addressFieldIndex=> $addressField){
							if(intval($addressField) !=0 && !is_nan(intval($addressField))){
								$insField.=','.$addressesDBFielMap[intval($addressField)];
								array_push($indexArray,$addressFieldIndex);
							}
						}
						$insField.=')';
						$basicInsVals=$addressfolder->uid.','.$time.','.$time.','.$this->session->get('auth')['uid'].','.$this->session->get('auth')['usergroup'];
						$tmpFile='../app/cache/tmp/'.$this->request->getPost('time').'_'.$this->request->getPost('filename');
						if (($handle = fopen($tmpFile, "r")) !== FALSE) {
							/*pretty nasty code redundancy*/
							if($this->request->getPost('firstRowFieldNames')==1){
								$data=$this->getCsvWrapper($handle, 1000, $this->_divider[$this->request->getPost('divider')],$this->_dataWrap[$this->request->getPost('dataFieldWrap')]);
								
							}
							
							while(($data = $this->getCsvWrapper($handle, 1000, $this->_divider[$this->request->getPost('divider')],$this->_dataWrap[$this->request->getPost('dataFieldWrap')])) !== FALSE){
									$insStr.='('.$basicInsVals;
									foreach($data as $valueindex=> $value){
										if(in_array($valueindex, $indexArray)){
											if(is_numeric($value)){
												$insStr.=','.$value;
											}else{
												$insStr.=',"'.$value.'"';
											}

										}
									}

									$insStr.='),';									
									if($row>0 && $row%500==0){
										$insStr=substr($insStr,0,-1);
										$this->di->get('db')->query("INSERT INTO addresses ".$insField." VALUES ".$insStr);
										$insStr='';
									}							

								$row++;
							}
							
							if($data==false && $insStr!=''){

									$insStr=substr($insStr,0,-1);
									
									$this->di->get('db')->query("INSERT INTO addresses ".$insField." VALUES ".$insStr);

							}

							fclose($handle);
							unlink($tmpFile);
							
						}
						$this->response->redirect($this->view->language.'/addressfolders/update/'.$addressfolder->uid.'/'); 
					$this->view->disable(); 


				}
		}else{
			$this->view->setVar('divider','');
			$this->view->setVar('dataFieldWrap','');
			$this->view->setVar('tstamp','');
			$this->view->setVar('filename','');
			$this->view->setVar('firstRowFieldNames','');
			$this->view->setVar('uploadfields',array());
		}
	}
	
        private function eraseFoldAdd($folderUid){
            Addresses::findByPid($folderUid)->delete();
        }
        
	private function getCsvWrapper($handle, $length, $divider,$wrap){
		if($wrap){
			return fgetcsv($handle, $length, $divider,$wrap);
		}else{
			return fgetcsv($handle, $length, $divider);
		}
	}
	
	
	public function updateAction()
	{
		
		
		if($this->request->isPost()){
			

			$result=$this->getData();
			$output=json_encode($result,true);			
			die($output);
			
		}else{
			$folderuid=$this->dispatcher->getParam("uid")?$this->dispatcher->getParam("uid"):0;
			$this->assets->addJs('js/vendor/addressfoldersInit.js');
			$this->assets->addCss('css/jquery.dataTables.css');
			$addressfolderrecord=  Addressfolders::findFirst(array(
				"conditions" => "uid = ?1",
				"bind" => array(1 => $folderuid)
				));
			$this->view->setVar('foldertitle',$addressfolderrecord->title);
			$this->view->setVar('folderuid',$folderuid);
			$this->view->setVar('addressfolder',$addressfolderrecord);
		}
	}
	public function deleteAction(){
		if($this->request->isPost()){
			if($this->request->hasPost('uid')){
				$object= Addressfolders::findFirstByUid($this->request->getPost('uid'));
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
		$aColumns=array('email','lastname',	'firstname','salutation','title','company','phone','address','city','zip','userlanguage','gender');
        
        $aColumnsSelect=array('email', 'last_name AS lastname', 'first_name AS firstname', 'salutation', 'title', 'company', 'phone', 'address', 'city', 'zip', 'userlanguage', 'gender' );
        $aColumnsFilter=array('email', 'last_name', 'first_name', 'salutation', 'title', 'company', 'phone', 'address', 'city', 'zip', 'userlanguage', 'gender' );
		$time=time();
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "uid";

		/* DB table to use */
		$sTable = "nltool\Models\Addresses";
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
			

		$sWhere = "WHERE deleted=0 AND hidden=0 AND pid = :pid: ";
		if ( isset($_POST['sSearch']) && $_POST['sSearch'] != "" )
		{
			$sWhere .= " AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= "".$aColumnsFilter[$i]." LIKE :searchTerm: OR "; //$_POST['sSearch']
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
		$phql = "SELECT ".str_replace(" , ", " ", implode(", ", $aColumnsSelect)).", uid FROM $sTable ".$sWhere." ".$sOrder." ".$sLimit;
		
		
		
		$bindArray['pid']=$this->request->getPost('folderuid');
		if($this->request->getPost('sSearch') != ''){
			$bindArray['searchTerm']='%'.$this->request->getPost('sSearch').'%';
		}
		
		$sQuery=$this->modelsManager->createQuery($phql);
		$rResults = $sQuery->execute($bindArray);		
		$resultSet=array();
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