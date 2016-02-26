<?php
namespace nltool\Models;

use Phalcon\Mvc\Model AS Model;

/**
 * SuccessLogins
 * This model registers successfull logins registered users have made
 */
class SuccessLogins extends Model
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
     * @var integer
     */
    public $endsession=0;

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

    public function initialize()
    {
        $this->belongsTo('userid', 'nltool\Models\Feusers', 'uid', array(
            'alias' => 'user'
        ));
    }
}