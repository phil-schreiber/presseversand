<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of Addresses
 *
 * @author Philipp-PC
 */
class Addresses extends Model{
	public function initialize(){
		
		$this->belongsTo('pid', 'nltool\Models\Addressfolders', 'uid', 
            array('alias' => 'addressfolder')
        );
		$this->hasManyToMany("uid", "nltool\Models\Segmentobjects_addresses_lookup", "uid_foreign","uid_local","nltool\Models\Segmentobjects","uid",array('alias' => 'segments'));
		$this->hasManyToMany("uid", "nltool\Models\Addresses_feuserscategories_lookup", "uid_local","uid_foreign","nltool\Models\Feuserscategories","uid",array('alias' => 'categories'));
	}
}