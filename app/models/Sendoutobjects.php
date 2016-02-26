<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Sendoutobjects extends Model{

	public function initialize()
    {
        $this->hasOne("mailobjectuid", "nltool\Models\Mailobjects", "uid",array('alias' => 'mailobject'));		
		$this->hasOne("configurationuid", "nltool\Models\Configurationobjects", "uid",array('alias' => 'configuration'));
		$this->hasOne("distributoruid", "nltool\Models\Distributors", "uid",array('alias' => 'distributor'));
		$this->hasOne("eventuid", "nltool\Models\Triggerevents", "uid",array('alias' => 'triggerevent'));
		$this->hasMany("uid", "nltool\Models\Addressconditions", "pid",array('alias' => 'addressconditions'));
		$this->hasMany("uid", "nltool\Models\Clickconditions", "pid",array('alias' => 'clickconditions'));
		$this->hasMany("uid", "nltool\Models\Mailqueue", "sendoutobjectuid",array('alias' => 'mailqueue'));
		$this->belongsTo("campaignuid", "nltool\Models\Campaignobjects", "uid", array('alias' => 'campaign'));
		$this->hasMany("uid", "nltool\Models\Review", "pid",array('alias' => 'review'));
		$this->hasMany("uid", "nltool\Models\Triggerreview", "pid",array('alias' => 'triggerreview'));
		$this->hasMany("uid", "nltool\Models\Linkclicks", "sendoutobjectuid",array('alias' => 'linkclicks'));
		$this->hasMany("uid", "nltool\Models\Openclicks", "sendoutobjectuid",array('alias' => 'openclicks'));
    }
	
	public function getLinkclicks(){				
		
		$modelsManager=$this->getDi()->getShared('modelsManager');		
				
		$queryStrng="SELECT nltool\Models\Linklookup.linknumber,nltool\Models\Linkclicks.uid, nltool\Models\Linkclicks.pid, nltool\Models\Linkclicks.tstamp, nltool\Models\Linkclicks.crdate, nltool\Models\Linkclicks.deleted, nltool\Models\Linkclicks.hidden, nltool\Models\Linkclicks.campaignuid, nltool\Models\Linkclicks.mailobjectuid, nltool\Models\Linkclicks.sendoutobjectuid, nltool\Models\Linkclicks.url, nltool\Models\Linkclicks.linkuid, nltool\Models\Linkclicks.addressuid FROM nltool\Models\Linkclicks LEFT JOIN nltool\Models\Linklookup ON nltool\Models\Linkclicks.linkuid = nltool\Models\Linklookup.uid WHERE nltool\Models\Linkclicks.sendoutobjectuid = ?1 GROUP BY nltool\Models\Linklookup.linknumber";	
		
		$sQuery=$modelsManager->createQuery($queryStrng);								
		
		$rResults = $sQuery->execute(array(
			1 => $this->uid
		));		
		
		/*$cleanedArray=array_unique($emailsArray);*/
		return $rResults;
	}
	
	public function countLinkclicks(){				
		
		$modelsManager=$this->getDi()->getShared('modelsManager');		
				
		$queryStrng="SELECT nltool\Models\Linkclicks.linkuid, COUNT(*) AS rowcount FROM nltool\Models\Linkclicks LEFT JOIN nltool\Models\Linklookup ON nltool\Models\Linkclicks.linkuid = nltool\Models\Linklookup.uid WHERE nltool\Models\Linkclicks.sendoutobjectuid = ?1 GROUP BY nltool\Models\Linklookup.linknumber";	
		
		$sQuery=$modelsManager->createQuery($queryStrng);								
		
		$rResults = $sQuery->execute(array(
			1 => $this->uid
		));		
		
		/*$cleanedArray=array_unique($emailsArray);*/
		return $rResults;
	}
	
}