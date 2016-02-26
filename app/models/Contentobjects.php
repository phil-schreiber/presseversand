<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;


/**
 * Description of Contentobjects
 *
 * @author Philipp-PC
 */
class Contentobjects extends Model{
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
    public $templateuid=0;
	
	/**
     *
     * @var integer
     */
    public $contenttype;
	
	/**
     *
     * @var string
     */
    public $sourcecode;
	
	/**
     *
     * @var integer
     */
    public $templateposition=0;
	
	
	/**
     *
     * @var integer
     */
    public $positionsorting=0;
	
	/**
     *
     * @var integer
     */
    public $mailobjectuid=0;
	
}