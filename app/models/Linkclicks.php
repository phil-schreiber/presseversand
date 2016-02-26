<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
	


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Linkclicks extends Model{
	
	public function initialize(){
		$this->belongsTo('linkuid', 'nltool\Models\Linklookup', 'uid', 
            array('alias' => 'link')
        );
		$this->belongsTo('addressuid', 'nltool\Models\Addresses', 'uid', 
            array('alias' => 'address')
        );
	}
	
}