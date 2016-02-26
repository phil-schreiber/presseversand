<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of Distributors
 *
 * @author Philipp-PC
 */
class Distributors extends Model{
	
	 public function initialize()
    {
       $this->hasManyToMany("uid", "nltool\Models\Distributors_segmentobjects_lookup", "uid_local", "uid_foreign", "nltool\Models\Segmentobjects", "uid",array('alias' => 'segments'));
	   $this->hasManyToMany("uid", "nltool\Models\Distributors_addressfolders_lookup", "uid_local", "uid_foreign", "nltool\Models\Addressfolders", "uid",array('alias' => 'addressfolders'));
	   $this->hasMany("uid", "nltool\Models\Addresses", "",array('alias' => 'addresses'));
    }
	
	public function hasMany($fields, $referenceModel, $referencedFields, $options = NULL){
		
	}
	
	public function getAddresses($params= array()){
		  $config =  \Phalcon\DI\FactoryDefault::getDefault()->getShared('config');
		$bindArray=array();
		$fieldMap=array(
			'pid'=>''
		);
		$pids=array();
		$cats=array();
		
		$folders=$this->getAddressfolders();
		
		$modelsManager=$this->getDi()->getShared('modelsManager');		
		$bindCounter=0;
		foreach($folders as $key=> $folder){
			$pids[]=$folder->uid;
		}
		$searchTerms=array();
		$segments=$this->getSegments();
		$where='';
		foreach($segments as $segment){
			$conditions=$segment->getConditions();			
			
			if(count($conditions) > 0){
				
				
				foreach($conditions as $condition){
					
					if($condition->field !== 'searchterm'  && $condition->field !== 'pid' && $condition->field !== 'uid_foreign'){
						$where.=' AND (';
						switch($condition->field){
							case 'firstname':
								$fieldname='first_name';
								break;
							case 'lastname':
								$fieldname='last_name';
								break;
							default:
								$fieldname=$condition->field;
								break;
						}
						$bindArray[$condition->field.$bindCounter]=$condition->searchvalue;
						$where .=$fieldname.' LIKE :'.$condition->field.$bindCounter.':';
						$bindCounter++;
						$where.=')';
					}elseif($condition->field === 'pid'){
						$pids[]=$condition->searchvalue;
					}elseif($condition->field === 'searchterm'){
						$searchTerms[] =$condition->searchvalue;
					}elseif($condition->field === 'uid_foreign'){
						$cats[]=$condition->uid_foreign;
					}
					
				}
				
			}
			
		}
		$count=0;
		if(count($pids)>0){
			$where.= ' AND nltool\Models\Addresses.pid IN (';
			$pidStrng='';
			foreach($pids as $key => $value){
				$pidStrng.='?'.$count.',';
				$bindArray[$key]=$value;
				$count++;
			}
			$where.=substr($pidStrng,0,-1).')';
		}
		if(count($cats)>0){
			$where.= ' AND nltool\Models\Addresses_feuserscategories_lookup.uid_foreign IN (';
			$catStrng='';
			foreach($cats as $key => $value){
				$catStrng.='?'.$count.',';
				$bindArray[$count]=$value;
				$count++;
			}
			$where.=substr($catStrng,0,-1).')';
		}
		
		$aColumnsFilter=array('email', 'last_name', 'first_name', 'salutation', 'title', 'company', 'phone', 'address', 'city', 'zip', 'userlanguage', 'gender' );
		if(count($searchTerms) >0){
			
			$searchStrng='';
			foreach($searchTerms as $key => $searchTerm){
				$where.=' AND (';
				foreach($aColumnsFilter as $filterName){
					$searchStrng .= "".$filterName." LIKE :searchterm".$key.": OR ";
				}
				$bindArray['searchterm'.$key]='%'.$searchTerm.'%';
				$searchStrng = substr($searchStrng, 0, -3 );
				$where .= $searchStrng.')';
			}
			
		}
		if(isset($params['conditions'])){
			$where.=$params['conditions'];
		}
		$joinTables='';
		if(isset($params['clickconditions'])){
			$joinTables=$params['clickconditions'][1];
			$where.=$params['clickconditions'][0];
		}
		$groupBy=$config['application']['dontSendDuplicates'] ? " GROUP BY email" : "";
		
		$queryStrng="SELECT email, last_name AS lastname, first_name AS firstname, salutation, title, company, phone, address, city, zip, userlanguage, gender, nltool\Models\Addresses.uid FROM nltool\Models\Addresses".$joinTables." LEFT JOIN nltool\Models\Addresses_feuserscategories_lookup ON nltool\Models\Addresses.uid = nltool\Models\Addresses_feuserscategories_lookup.uid_local WHERE nltool\Models\Addresses.deleted=0 AND nltool\Models\Addresses.hidden=0 ".$where."".$groupBy;	
		
		$sQuery=$modelsManager->createQuery($queryStrng);								
		
		$rResults = $sQuery->execute($bindArray);		
		
		/*$cleanedArray=array_unique($emailsArray);*/
		return $rResults;
	}
	
	public function countAddresses(){
		$rResults=$this->getAddresses();
		
		
		/*$cleanedArray=array_unique($emailsArray);*/
		return count($rResults);
	}
	
}