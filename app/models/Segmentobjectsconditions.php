<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of Addresses
 *
 * @author Philipp-PC
 */
class Segmentobjectsconditions extends Model{
	public function initialize(){
		
		$this->belongsTo('pid', 'nltool\Models\Segmentobjects', 'uid', 
            array('alias' => 'segmentobjects')
        );
		
	}
}