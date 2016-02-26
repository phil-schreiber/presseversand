<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of fe_users
 *
 * @author Philipp Schreiber
 */
class Feuserscategories extends \Phalcon\Mvc\Model{
	
	 public function initialize()
    {		      
	  $this->hasManyToMany("uid", "nltool\Models\Addresses_feuserscategories_lookup", "uid_foreign","uid_local","nltool\Models\Addresses","uid",array('alias' => 'addresses'));
    }

}
