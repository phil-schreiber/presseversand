<?php
namespace nltool\Notifications;

use nltool\Models\Notifications;
use Phalcon\Di\Injectable;

class Checker extends Injectable
{

    /**
     * Check for Notifications
     *
     * @return boolean
     */
    public function has()
    {
        $userid = $this->session->get('auth');
        if (!$userid) {
            return false;
        }

        $number = Notifications::count(array(
            'userid = ?0 AND read = 0',
            'bind' => array($userid)
        ));

        return $number > 0;
    }     

}