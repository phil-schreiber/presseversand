<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Distributors
 *
 * @author Philipp-PC
 */
class Segmentobjects_addresses_lookup extends Model{
	
	public function initialize(){		
        $this->belongsTo('uid_local', 'nltool\Models\Segmentobjects', 'uid', 
            array('alias' => 'segmentobject')
        );
		
		$this->belongsTo('uid_foreign', 'nltool\Models\Addresses', 'uid', 
            array('alias' => 'addresses')
        );
	}
	
}