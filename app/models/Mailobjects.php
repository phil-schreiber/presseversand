<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mailobjects
 *
 * @author Philipp-PC
 */
class Mailobjects extends Model{
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
    public $deleted=0;
	
	/**
     *
     * @var integer
     */
    public $hidden=0;
	
	/**
     *
     * @var integer
     */
    public $crdate;
	
	/**
     *
     * @var integer
     */
    public $tstamp;
	
	/**
     *
     * @var integer
     */
    public $cruser_id;
	
	/**
     *
     * @var integer
     */
    public $usergroup;
	
	/**
     *
     * @var integer
     */
    public $campaign=0;
	
	/**
     *
     * @var integer
     */
    public $origuid=0;
	
	/**
     *
     * @var integer
     */
    public $templateuid;
	
	/**
     *
     * @var integer
     */
    public $templatetype;
	
	 public function initialize()
    {
        $this->hasMany("uid", "nltool\Models\Contentobjects", "mailobjectuid",array('alias' => 'contentobjects'));
        $this->hasOne('cruser_id', 'nltool\Models\Feusers', 'uid', array(
                    'alias' => 'cruser'
                ));
    }
	
}


