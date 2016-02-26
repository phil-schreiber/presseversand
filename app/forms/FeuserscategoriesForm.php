<?php
namespace nltool\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;





class FeuserscategoriesForm extends Form
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

       

        

        
    }
}