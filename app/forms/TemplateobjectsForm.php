<?php
namespace nltool\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use nltool\Modules\Modules\Frontend\Controllers\ControllerBase as ControllerBase;
use nltool\Models\Feusers;


class TemplateobjectsForm extends Form
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

        		
		$sourcecode = new TextArea('sourcecode', array(
            
        ));

        $sourcecode->addValidators(array(
            new PresenceOf(array(
                'message' => 'The sourcecode is required'
            ))
        ));

        $this->add($sourcecode);
		
        
        $this->add(new Select('templatetype', array(
            '0' => ControllerBase::translate('templateTypeMail'),
            '1' => ControllerBase::translate('templateTypeContent'),
			
        )));
		
		

        

        
    }
}