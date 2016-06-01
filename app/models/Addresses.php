<?php
namespace nltool\Models;
use Phalcon\Mvc\Model;
Model::setup(['notNullValidations' => false]);

/**
 * Description of Addresses
 *
 * @author Philipp-PC
 */
class Addresses extends Model{
    
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
    public $usergroup='';
	
    /**
     *
     * @var string
     */
    public $first_name='';
    
    /**
     *
     * @var string
     */
    public $last_name='';
    
    /**
     *
     * @var string
     */
    public $salutation='';
    
    /**
     *
     * @var string
     */
    public $title='';
    
    /**
     *
     * @var string
     */
    public $email='';
    
    /**
     *
     * @var string
     */
    public $phone='';
    
    /**
     *
     * @var string
     */
    public $address='';
    
    /**
     *
     * @var string
     */
    public $city='';
    
    /**
     *
     * @var string
     */
    public $company='';
    
    /**
     *
     * @var integer
     */
    public $zip=0;
    
    /**
     *
     * @var integer
     */
    public $region=0;
    
    /**
     *
     * @var string
     */
    public $province='';
    
    /**
     *
     * @var integer
     */
    public $userlanguage=0;
    
    /**
     *
     * @var integer
     */
    public $gender='';
    
    /**
     *
     * @var integer
     */
    public $formal='';
    
    /**
     *
     * @var string
     */
    public $hashtags='';
    
    /**
     *
     * @var string
     */
    public $itemsource='';
    
    /**
     *
     * @var integer
     */
    public $hasprofile=0;
    
    /**
     *
     * @var date
     */
    public $birthday='0000-00-00';
    
    /**
     *
     * @var integer
     */
    public $dataprotection=0;
    
    
    
    
    
    
    
    
    
	public function initialize(){
		
		$this->belongsTo('pid', 'nltool\Models\Addressfolders', 'uid', 
            array('alias' => 'addressfolder')
        );
		$this->hasManyToMany("uid", "nltool\Models\Segmentobjects_addresses_lookup", "uid_foreign","uid_local","nltool\Models\Segmentobjects","uid",array('alias' => 'segments'));
		$this->hasManyToMany("uid", "nltool\Models\Addresses_feuserscategories_lookup", "uid_local","uid_foreign","nltool\Models\Feuserscategories","uid",array('alias' => 'categories'));
	}
}