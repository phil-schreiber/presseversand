<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Segmentobjects extends Model{
	 public function initialize()
    {
		//$this->hasManyToMany("uid", "nltool\Models\Segmentobjects_addresses_lookup", "uid_local","uid_foreign","nltool\Models\Addresses","uid",array('alias' => 'addresses'));
		$this->hasMany("uid", "nltool\Models\Addresses", "",array('alias' => 'addresses'));
		$this->hasMany("uid", "nltool\Models\Segmentobjectsconditions", "pid",array('alias' => 'conditions'));
        $this->hasManyToMany("uid", "nltool\Models\Distributors_segmentobjects_lookup", "uid_foreign","uid_local","nltool\Models\Distributors","uid",array('alias' => 'distributors'));
    }
	
	
	
	public function getAddresses(){
		$modelsManager=$this->getDi()->getShared('modelsManager');		
		$sQuery=$modelsManager->createQuery($this->querystring);								
		$bindArray=json_decode($this->bindarray,true);				
		$rResults = $sQuery->execute($bindArray);		
		
		return $rResults;
	}
	
	public function getEmails(){
		$modelsManager=$this->getDi()->getShared('modelsManager');		
		$sQuery=$modelsManager->createQuery($this->querystring);								
		$bindArray=json_decode($this->bindarray,true);				
		$rResults = $sQuery->execute($bindArray);		
		$emails=[];
		foreach($rResults as $row){
			$emails[$row->uid]=$row->email;
		}
		return $emails;
	}
	

	public function countAddresses(){			
		$modelsManager=$this->getDi()->getShared('modelsManager');				
		$sQuery=$modelsManager->createQuery($this->querystring);								
		$bindArray=json_decode($this->bindarray,true);				
		$rResults = $sQuery->execute($bindArray);		
		return count($rResults);
	}
}