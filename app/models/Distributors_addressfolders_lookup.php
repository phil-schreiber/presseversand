<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Distributors_addressfolders_lookup
 *
 * @author Philipp-PC
 */
class Distributors_addressfolders_lookup extends Model{
	
	public function initialize(){
		$this->belongsTo('uid_local', 'nltool\Models\Distributors', 'uid', 
            array('alias' => 'distributor')
        );
        $this->belongsTo('uid_foreign', 'nltool\Models\Addressfolders', 'uid', 
            array('alias' => 'addressfolder')
        );
	}
	
}