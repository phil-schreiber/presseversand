<?php
namespace nltool\Models;

use Phalcon\Mvc\Model;

/**
 * nltool\Models\Triggerevents
 * All the profile levels in the application. Used in conjenction with ACL lists
 */
class Triggerevents extends Model
{
	public function initialize(){
		$this->hasOne("mailobjectuid", "nltool\Models\Mailobjects", "uid",array('alias' => 'mailobject'));		
		$this->hasOne("configurationuid", "nltool\Models\Configurationobjects", "uid",array('alias' => 'configuration'));
		$this->hasOne("distributoruid", "nltool\Models\Distributors", "uid",array('alias' => 'distributor'));
		$this->hasMany("uid", "nltool\Models\Triggerreview", "pid",array('alias' => 'review'));
	}
	
}