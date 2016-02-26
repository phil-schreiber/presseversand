<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Triggerreview extends Model{
	
	public function initialize(){
		$this->belongsTo("pid", "nltool\Models\Triggerevent", "uid", array('alias' => 'triggerevent'));
		$this->belongsTo("cruser_id", "nltool\Models\Feuser", "uid", array('alias' => 'authority'));
	}
	
}