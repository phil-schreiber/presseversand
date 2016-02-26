<?php
namespace nltool\Models;

use Phalcon\Mvc\Model as Model;

/**
 * FailedLogins
 * This model registers unsuccessfull logins registered and non-registered users have made
 */
class FailedLogins extends Model
{

	
    /**
     *
     * @var integer
     */
    public $uid;

	/**
     *
     * @var integer
     */
    public $pid=0;
	
	/**
     *
     * @var integer
     */
    public $tstamp=0;
	
	/**
     *
     * @var integer
     */
    public $crdate=0;
	
	/**
     *
     * @var integer
     */
    public $cruser_id=0;
	
	
	
    /**
     *
     * @var integer
     */
    public $userid;

    /**
     *
     * @var string
     */
    public $ipaddress;

	/**
     *
     * @var string
     */
    public $useragent='';
	
    /**
     *
     * @var integer
     */
    public $attempted;

    public function initialize()
    {
		Model::setup(['exceptionOnFailedSave' => true]);
        $this->belongsTo('userid', 'nltool\Models\Feusers', 'uid', array(
            'alias' => 'user'
        ));
    }
}