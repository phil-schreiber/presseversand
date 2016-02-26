<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Subscriptions
 *
 * @author Philipp-PC
 */
class Subscriptionobjects extends Model{

	 public function initialize()
    {
		
        $this->hasManyToMany("uid", "nltool\Models\Subscriptionobjects_feuserscategories_lookup", "uid_local","uid_foreign","nltool\Models\Feuserscategories","uid",array('alias' => 'feuserscategories'));
    }
	
}