<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Addressfolders extends Model{
	
	 public function initialize()
    {
        $this->hasMany("uid", "nltool\Models\Addresses", "pid",array('alias' => 'addresses'));
		$this->hasManyToMany("uid", "nltool\Models\Distributors_addressfolders_lookup", "uid_foreign", "uid_local", "nltool\Models\Distributors", "uid",array('alias' => 'distributors'));
		$this->hasManyToMany("uid", "nltool\Models\Subscriptionobjects_addressfolders_lookup", "uid_foreign","uid_local","nltool\Models\Subscriptionobjects","uid",array('alias' => 'subscriptionsobjects'));
    }
	
	public function getEmails(){
		$addresses=$this->getAddresses();
		$emails=[];
		foreach($addresses as $address){
			$emails[$address->uid] = $address->email;
		}
		return $emails;
	}
}