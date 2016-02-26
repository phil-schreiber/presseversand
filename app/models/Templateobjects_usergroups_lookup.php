<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Templateobjects_usergroups_lookup
 *
 * @author Philipp-PC
 */
class Templateobjects_usergroups_lookup extends Model{
	
	public function initialize(){
		$this->belongsTo('uid_local', 'nltool\Models\Templateobjects', 'uid', 
            array('alias' => 'templateobjects')
        );
        $this->belongsTo('uid_foreign', 'nltool\Models\Usergroups', 'uid', 
            array('alias' => 'usergroups')
        );
	}
	
}