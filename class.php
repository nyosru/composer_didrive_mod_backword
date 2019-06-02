<?php

/**
класс модуля
**/

namespace Nyos\mod;

if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых ха+1керов, обратитесь к администрратору');

class backword {

    //public static $dir_img_server = false;

    public static function putInLog($db, $folder, $domain, $from, $to, $head, $message, $vars = array()) {

        \f\db\db2_insert($db, 'gm_mail', array(
            'folder' => $folder,
            'domain' => $domain,
            'from' => $from,
            'to' => $to,
            'head' => $head,
            'message' => $messsage,
            'array_var' => serialize($vars),
            'd' => 'NOW',
            't' => 'NOW'
        ));
        
        
        
    }
    
}
