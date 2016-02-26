<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Distributors_addressfolders_lookup
 *
 * @author Philipp-PC
 */
class Configurationobjects_feusers_lookup extends Model{
	
	public function initialize(){
		$this->belongsTo('uid_local', 'nltool\Models\Configurationsobjects', 'uid', 
            array('alias' => 'configurationsobjects')
        );
        $this->belongsTo('uid_foreign', 'nltool\Models\Feusers', 'uid', 
            array('alias' => 'feusers')
        );
	}
	
}



