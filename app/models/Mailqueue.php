<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Mailqueue extends Model{
	 public function initialize()
    {
        $this->belongsTo('addressuid', 'nltool\Models\Addresses', 'uid', array(
            'alias' => 'address'
        ));
		
		$this->belongsTo('sendoutobjectuid', 'nltool\Models\Sendoutobjects', 'uid', array(
            'alias' => 'sendoutobject'
        ));
		
    }
}