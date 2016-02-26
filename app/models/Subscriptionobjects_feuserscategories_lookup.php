<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Distributors
 *
 * @author Philipp-PC
 */
class Subscriptionobjects_feuserscategories_lookup  extends Model{
	
	public function initialize(){		
        $this->belongsTo('uid_local', 'nltool\Models\Subscriptionobjects', 'uid', 
            array('alias' => 'subscriptionobject')
        );
		
		$this->belongsTo('uid_foreign', 'nltool\Models\Feuserscategories', 'uid', 
            array('alias' => 'feuserscategories')
        );
	}
	
}