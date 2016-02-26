<?php


namespace nltool\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;

/**
 * Class EventNotifications
 *
 * @property \nltool\Models\Feusers        user
 * @property \nltool\Models\Mailobjects    mailobjects
 *   
 */
class Notifications extends Model
{

    public $uid;

    public $userid;

    public $type;

    public $posts_id;

    public $posts_replies_id;

    public $created_at;

    public $was_read;

    public function beforeValidationOnCreate()
    {
        $this->was_read = false;
    }

    public function initialize()
    {
        $this->belongsTo(
            'userid',
            'nltool\Models\Feusers',
            'uid',
            array(
                'alias' => 'feusers'
            )
        );

        $this->belongsTo(
            'users_origin_id',
            'nltool\Models\Feusers',
            'uid',
            array(
                'alias' => 'userOrigin'
            )
        );

        $this->belongsTo(
            'object_id',
            'nltool\Models\Mailobjects',
            'uid',
            array(
                'alias' => 'mailobjects'
            )
        );

       

        $this->addBehavior(
            new Timestampable(array(
                'beforeCreate' => array(
                    'field' => 'crdate'
                )
            ))
        );
    }

    public function markAsRead()
    {
        if ($this->was_read == false) {
            $this->was_read = true;
            $this->save();
        }
    }

    /**
     * @return bool|string
     */
    public function getHumanCreatedAt()
    {
        $diff = time() - $this->crdate;
        if ($diff > (86400 * 30)) {
            return date('M \'y', $this->crdate);
        } else {
            if ($diff > 86400) {
                return ((int)($diff / 86400)) . 'd ago';
            } else {
                if ($diff > 3600) {
                    return ((int)($diff / 3600)) . 'h ago';
                } else {
                    return ((int)($diff / 60)) . 'm ago';
                }
            }
        }
    }
}