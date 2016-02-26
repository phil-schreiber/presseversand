<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Configurationobjects extends Model{
	
	public function initialize(){
		$this->hasManyToMany("uid", "nltool\Models\Configurationobjects_feusers_lookup", "uid_local", "uid_foreign", "nltool\Models\Feusers", "uid",array('alias' => 'authorities'));
	}
	
}