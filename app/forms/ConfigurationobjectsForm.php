<?php
namespace nltool\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use nltool\Modules\Modules\Frontend\Controllers\ControllerBase as ControllerBase;
use nltool\Models\Feusers;


class ConfigurationobjectsForm extends Form
{

    public function initialize($entity = null, $options = null)
    {

        // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $uid = new Hidden('uid');
        } else {
            $uid = new Text('uid');
        }

        $this->add($uid);

        $title = new Text('title', array(
            
        ));

        $title->addValidators(array(
            new PresenceOf(array(
                'message' => 'The name is required'
            ))
        ));

        $this->add($title);

        $sendermail = new Text('sendermail', array(
            
        ));

        $sendermail->addValidators(array(
            new PresenceOf(array(
                'message' => 'The e-mail is required'
            )),
            new Email(array(
                'message' => 'The e-mail is not valid'
            ))
        ));

        $this->add($sendermail);
		
		$sendername = new Text('sendername', array(
            
        ));

        $sendername->addValidators(array(
            new PresenceOf(array(
                'message' => 'The sendername is required'
            ))
        ));

        $this->add($sendername);
		
		 $answermail = new Text('answermail', array(
            
        ));

        $answermail->addValidators(array(
            new PresenceOf(array(
                'message' => 'The answermail is required'
            )),
            new Email(array(
                'message' => 'The answermail is not valid'
            ))
        ));

        $this->add($answermail);
		
		$answername = new Text('answername', array(
            
        ));

        $answername->addValidators(array(
            new PresenceOf(array(
                'message' => 'The answername is required'
            ))
        ));

        $this->add($answername);
		
		 $returnpath = new Text('returnpath', array(
            
        ));

        $returnpath->addValidators(array(
            new PresenceOf(array(
                'message' => 'The returnpath is required'
            )),
            new Email(array(
                'message' => 'The returnpath is not valid'
            ))
        ));

        $this->add($returnpath);

		$organisation = new Text('organisation', array(
            
        ));

        $organisation->addValidators(array(
            new PresenceOf(array(
                'message' => 'The organisation is required'
            ))
        ));

        $this->add($organisation);
		$authorities=new Select("authorities[]", Feusers::find(array('conditions'=>'deleted=0 AND hidden=0')), array(
            'using' => array('uid', 'email'),			 
			'multiple'=>'multiple'		
        ));
		
		 $selectedOptions = [];
        foreach ($entity->getAuthorities() as $authority) {
            $selectedOptions[]  = $authority->uid;
        }
		$authorities->setDefault($selectedOptions);
		$this->add($authorities);
        $this->add(new Select('htmlplain', array(
            '0' => ControllerBase::translate('html'),
            '1' => ControllerBase::translate('plain'),
			'2' => ControllerBase::translate('both')
        )));
		
		$this->add(new Select('clicktracking', array(
            '1' => ControllerBase::translate('active'),
            '0' => ControllerBase::translate('inactive')
			
        )));

        

        
    }
}