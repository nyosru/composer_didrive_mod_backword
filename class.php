<?php

/**
класс модуля
**/

namespace Nyos\mod;



if (!defined('IN_NYOS_PROJECT'))
    throw new \Exception('Сработала защита от розовых ха+1керов, обратитесь к администрратору');

class backword2 {

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



class backword {

    // если тру то шлём копию на support@uralweb.info
    public static $send_teh_mail = false;
    public static $now_page = 1;
    public static $city = null;

    /**
     * запись в лог об отправке письма
     * @param type $db
     * @param type $folder
     * @param type $domain
     * @param type $from
     * @param type $to
     * @param type $head
     * @param type $message
     * @param type $vars
     */
    public static function putInLog($db, $folder, $domain, $from, $to, $head, $message, $vars = array()) {

        //global $status;

        $status = '';
        f\db\db2_insert($db, 'gm_mail', array(
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

    public static function getTemplateMail($tpl = 'default', $vars = array()) {



        $mail_tpl = ( file_exists($_SERVER['DOCUMENT_ROOT'] . '/template-mail/' . $tpl . '.htm') ) ? $tpl : 'default';

        // echo '<hr>'.$mail_tpl.'<hr>';

        $htm_mail = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/template-mail/' . $mail_tpl . '.htm');

        $a1 = $a2 = array();

        if (sizeof($vars) > 0)
            foreach ($vars as $k => $v) {
                $a1[] = '{$' . $k . '}';
                $a2[] = $v;
            }

        return str_replace($a1, $a2, $htm_mail);
    }

    public static function saveMessageInBd($db, $folder, $post) {

        //global $status;

        if (isset($post['bw'])) {
            foreach ($post['bw'] as $k => $v) {
                if (!isset($post[$k]))
                    $post[$k] = $v;
            }
        }

        if (isset($post['bw']))
            unset($post['bw']);

        $post['array'] = serialize($post);

        if (isset($post['id']{0}))
            unset($post['id']);

        if (!isset($post['from']) && isset($post['fio']{0}))
            $post['from'] = $post['fio'];

        if (!isset($post['from']) && isset($post['name']{0}))
            $post['from'] = $post['name'];

        if (!isset($post['phone']) && isset($post['tel']{0}))
            $post['phone'] = $post['tel'];

        if (!isset($post['phone']) && isset($post['gsm']{0}))
            $post['phone'] = $post['gsm'];

        if (!isset($post['mail']) && isset($post['email']{0}))
            $post['mail'] = $post['email'];

        if (!isset($post['message']) && isset($post['body']{0}))
            $post['message'] = $post['body'];

        if (!isset($post['message']) && isset($post['text']{0}))
            $post['message'] = $post['text'];

        $post['d'] = $post['t'] = 'NOW';
        $post['folder'] = $folder;

        $status = '';

        \f\db\db2_insert($db, 'gm_message', $post, 'da');

        //echo $status;

        return true;
    }

    /**
     * получение списка сообщений
     * @param type $db
     * @param type $folder
     * @return boolean
     */
    public static function getMessages($db, $folder = null) {

        //global $status;
        // f\pa($return2);
        $status = '';
        $sql2 = $db->sql_query('SELECT * FROM `gm_message` WHERE '
                . ' `status` != \'delete\' '
                . ( $folder !== null ? ' AND `folder` = \'' . addslashes($folder) . '\' ' : '' )
                . ' ORDER BY ' . ( isset($sql_sort{0}) ? ' ' . $sql_sort : ' `d` DESC, `t` DESC' )
                . ' ;');
        echo $status;
        // echo '<hr>'.$db->sql_numrows($sql).'<hr>';

        $return = array();

        while ($res = $db->sql_fr($sql2)) {
            //$res['s'] = nyos::creatSecret($res['id']);
            $return[$res['id']] = $res;
        }


        //echo $status;
        return $return;
    }

    /**
     * отправка письма через сендмайл сформированного смарти шаблона
     * @param type $from
     * @param type $to
     * @param type $tpl
     * @param string $head
     * @param type $vars
     * @return boolean
     */
    public static function sendMailServer($from, $to, $tpl = 'default', $head = null, $vars = array()) {

        // echo '11<pre>'; print_r($from); echo '</pre>';
        // echo '22<pre>'; print_r($to); echo '</pre>';
        // global $status;

        if ($head === null)
            $head = 'Письмо с сайта ' . $_SERVER['HTTP_HOST'];

        //$status = '';
        // require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/mail.pop3.php' );
        require_once( dirname(__FILE__).DS.'mail.pop3.php' );

        $mail2 = self::getTemplateMail($tpl, $vars);

        mails::ns_new($from, $to);
        mails::ns_send($head, $mail2);

        //$emailer->ns_send($head, $mail2);
        //$emailer->ns_new($from, $to);

        if (self::$send_teh_mail === true) {
            mails::ns_new($from, 'support@uralweb.info');
            mails::ns_send($head, $mail2 );
        }
        return true;
    }

    /**
     * посыл письма ( через сендпульс или сендмайл )
     * @param type $from
     * @param type $to
     * @param type $tpl
     * @param type $head
     * @param type $vars
     * ( sp_id sp_secret )
     * @return boolean
     */
    public static function sendMailSuper($from, $to, $tpl = 'default', $head = null, $vars = array(
        'sp_id' => null,
        'sp_secret' => null
    )) {

        $sendsp = false;

        // отправка через сендпульс если есть логин с паролем к АПИ
        if (isset($vars['sp_id']{1}) && isset($vars['sp_secret']{1})) {

            $s_id = $vars['sp_id'];
            unset($vars['sp_id']);

            $s_secret = $vars['sp_secret'];
            unset($vars['sp_secret']);

            $sendsp = true;
            $r = self::sendMailSendpulse($s_id, $s_secret, $from, $to, $tpl = 'default', $head = null, $vars);

            return f\end2('нормуль отправили через сендпульс', 'ok', array(
                'file' => __FILE__
                , 'line' => __LINE__
                , 'res' => $r
                    ), 'array');
        }

        // отправка через сендмайл если не отправили через сенд пульс
        if ($sendsp === false) {

            if (isset($vars['sp_id']{1}))
                unset($vars['sp_id']);

            if (isset($vars['sp_secret']{1}))
                unset($vars['sp_secret']);

//            echo __FILE__.'['.__LINE__.']<br/>';
//            echo $from.'<Br/>';
//            echo $to.'<Br/>';
//            echo $tpl.'<Br/>';

            $res = self::sendMailServer($from, $to, $tpl, $head, $vars);

            return \f\end2('нормуль отправили через сервер', 'ok', array(
                'file' => __FILE__
                , 'line' => __LINE__
                    ), 'array');
        }

        return true;
    }

    /**
     * посыл письма через сендпульс шаблна смарти
     * @param type $sp_id
     * @param type $sp_secret
     * @param type $from
     * @param type $to
     * @param type $tpl
     * @param type $head
     * @param type $vars
     * @return boolean
     */
    public static function sendMailSendpulse($sp_id, $sp_secret, $from, $to, $tpl = 'default', $head = null, $vars = array()) {

        // echo '<pre>'; print_r( $vars ); echo '</pre>';
        // https://login.sendpulse.com/settings/#api
        define('API_USER_ID', $sp_id);
        define('API_SECRET', $sp_secret);

        // define('TOKEN_STORAGE', 'file');
        // $SPApiProxy = new SendpulseApi(API_USER_ID, API_SECRET, TOKEN_STORAGE);

        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/ApiInterface.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/ApiClient.php' );

        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/TokenStorageInterface.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/FileStorage.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/MemcacheStorage.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/MemcachedStorage.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/SessionStorage.php' );

        $SPApiClient = new Sendpulse\RestApi\ApiClient(API_USER_ID, API_SECRET, new Sendpulse\RestApi\storage\SessionStorage());

        // Send mail using SMTP
        $email = array(
            'html' => self::getTemplateMail($tpl, $vars),
            'text' => $vars['text'],
            'subject' => $head
                //,
//                'answer_to' => array(
//                    'name' => 'Управление гос. экспертизы проектной документации',
//                    'email' => 'support@expertiza72.ru'
//                ),
//                'bcc' => array(
//                    array(
//                        'name' => 'Тех. поддержка',
//                        'email' => 'support@uralweb.info'
//                    )
//                )
                //,
                // 'attachments' => array(
                // 'file.txt' => file_get_contents(PATH_TO_ATTACH_FILE)
                // )
        );


        if (isset($from['email']{1})) {
            $email['from'] = array(
                'name' => $from['name'],
                'email' => $from['email']
            );
        } else {
            $email['from'] = array(
                'name' => $from,
                'email' => $from
            );
        }

        // $to['email'] = 'domape@rambler.ru';

        $email['to'] = array($to);
        /*
          if( isset( $to['email']{1}) ){
          $email['to'] = array(
          array(
          'name' => $to['name'],
          'email' => $to['email']
          )
          );
          }else{
          $email['to'] = array(
          array(
          'name' => '',
          'email' => $to
          )
          );
          }
         */

        //f\pa($email);

        $ee = $SPApiClient->smtpSendMail($email);

        // f\pa($email);
        // f\pa($ee);

        if ($ee->result == true) {
            return true;
        } else {
            return $ee;
        }
    }

    /**
     * посыл не шаблона письма а сразу тела письма
     * @param type $sp_id
     * @param type $sp_secret
     * @param type $from
     * @param type $to
     * @param type $tpl
     * @param type $head
     * @param type $vars
     * @return boolean
     */
    public static function sendMailSendpulse2($sp_id, $sp_secret, $from, $to, $tpl = 'default', $head = null, $vars = array()) {

        // echo '<pre>'; print_r( $vars ); echo '</pre>';
        // https://login.sendpulse.com/settings/#api
        define('API_USER_ID', $sp_id);
        define('API_SECRET', $sp_secret);

        // define('TOKEN_STORAGE', 'file');
        // $SPApiProxy = new SendpulseApi(API_USER_ID, API_SECRET, TOKEN_STORAGE);

        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/ApiInterface.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/ApiClient.php' );

        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/TokenStorageInterface.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/FileStorage.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/MemcacheStorage.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/MemcachedStorage.php' );
        require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/sendpulse/Storage/SessionStorage.php' );

        $SPApiClient = new Sendpulse\RestApi\ApiClient(API_USER_ID, API_SECRET, new Sendpulse\RestApi\storage\SessionStorage());

        // Send mail using SMTP
        $email = array(
            'html' => $tpl,
            'text' => $vars['text'],
            'subject' => $head
                //,
//                'answer_to' => array(
//                    'name' => 'Управление гос. экспертизы проектной документации',
//                    'email' => 'support@expertiza72.ru'
//                ),
//                'bcc' => array(
//                    array(
//                        'name' => 'Тех. поддержка',
//                        'email' => 'support@uralweb.info'
//                    )
//                )
                //,
                // 'attachments' => array(
                // 'file.txt' => file_get_contents(PATH_TO_ATTACH_FILE)
                // )
        );


        if (isset($from['email']{1})) {
            $email['from'] = array(
                'name' => $from['name'],
                'email' => $from['email']
            );
        } else {
            $email['from'] = array(
                'name' => $from,
                'email' => $from
            );
        }

        // $to['email'] = 'domape@rambler.ru';

        $email['to'] = array($to);
        /*
          if( isset( $to['email']{1}) ){
          $email['to'] = array(
          array(
          'name' => $to['name'],
          'email' => $to['email']
          )
          );
          }else{
          $email['to'] = array(
          array(
          'name' => '',
          'email' => $to
          )
          );
          }
         */

        //f\pa($email);

        $ee = $SPApiClient->smtpSendMail($email);

        // f\pa($email);
        // f\pa($ee);

        if ($ee->result == true) {
            return true;
        } else {
            return $ee;
        }
    }

}
