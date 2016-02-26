<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Distributors
 *
 * @author Philipp-PC
 */
class Distributors_segmentobjects_lookup extends Model{
	
	public function initialize(){
		$this->belongsTo('uid_local', 'nltool\Models\Distributors', 'uid', 
            array('alias' => 'distributor')
        );
        $this->belongsTo('uid_foreign', 'nltool\Models\Segmentobjects', 'uid', 
            array('alias' => 'segmentobject')
        );
	}
	
}