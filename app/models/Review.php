<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Review extends Model{
	
	public function initialize(){
		$this->belongsTo("pid", "nltool\Models\Sendoutobject", "uid", array('alias' => 'sendoutobject'));
		$this->belongsTo("cruser_id", "nltool\Models\Feuser", "uid", array('alias' => 'authority'));
	}
	
}