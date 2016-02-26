<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;

/**
 * Class SubscriptionController
 *
 * @package baywa-nltool\Controllers
 */

class PolleventsController extends Triggerauth implements EventsAwareInterface
{
	protected $_eventsManager;
	private $eventFuncs=array(
		/*1 => 'dateEvents',
		2 => 'recursiveEvents',*/
		3 => 'birthdayEvents'
	);
	
    public function setEventsManager(Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
	
	public function pollAction(){				
		foreach($this->eventFuncs as $key => $eventtype){
			$func=$this->eventFuncs[$key];			
			$this->$func();
		}
	}
	
	private function dateEvents(){
		
	}
	
	
	private function recursiveEvents(){
		$daysofweek=array(
			0 => 7,
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
			5 => 5,
			6 => 6			
		);
		
		$currentdayofweek=date('w');
		$events = \nltool\Models\Triggerevents::find(array(
			'conditions' => 'deleted = 0 AND hidden = 0 AND cleared = 1 AND reviewed = 1 AND eventtype = 2 AND (dayofweek = ?1 OR repeatcycle = 1)',
			'bind' => array(
				1 => $daysofweek[$currentdayofweek]
			)			
		));
	}
	
	private function birthdayEvents(){
		$date = date('m-d');
		$events=  \nltool\Models\Triggerevents::find(array(
			'conditions' => 'deleted = 0 AND hidden = 0 AND cleared = 1 AND reviewed = 1 AND eventtype = 3 AND DATE_FORMAT(birthday, "%m-%d") LIKE ?1',
			'bind' => array(
				1 => $date
			)
		));
		
		foreach($events as $event){
			
			$addresses = \nltool\Models\Addresses::find(array(
				'conditions' => 'deleted=0 AND hidden=0 AND birthday LIKE ?1 AND pid = ?2',
				'bind' => array(
					1 => $date,
					2 => $event->addressfolder
				)
			));
			foreach($addresses as $address){
				$this->triggerevents->fire("PolleventsController:birthdayEventHandler", $event,$address);
			}
		}
		
	}
}