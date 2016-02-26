<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of fe_users
 *
 * @author Philipp Schreiber
 */
class Addresses_feuserscategories_lookup extends \Phalcon\Mvc\Model{
	
	public function initialize(){		
        $this->belongsTo('uid_local', 'nltool\Models\Addresses', 'uid', 
            array('alias' => 'feusers')
        );
		
		$this->belongsTo('uid_foreign', 'nltool\Models\Feuserscategories', 'uid', 
            array('alias' => 'categories')
        );
	}

}
