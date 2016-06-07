<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Campaignobjects extends Model{
	public function hasReportableSendoutobjects(){
		$sendoutObjects=$this->getSendoutobjects(array(
			'conditions'=>'deleted=0 AND hidden=0 AND (inprogress=1 OR sent=1)'
		));
		$returnval=count($sendoutObjects)==0 ? false:true;
		
		return $returnval;
	}
	public function initialize(){
		$this->hasMany("uid", "nltool\Models\Sendoutobjects", "campaignuid",array('alias' => 'sendoutobjects'));
                $this->hasOne('cruser_id', 'nltool\Models\Feusers', 'uid', array(
                    'alias' => 'cruser'
                ));
	}
	
}