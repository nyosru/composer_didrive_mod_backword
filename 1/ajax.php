<?php

use TrueBV\Punycode;

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

date_default_timezone_set("Asia/Yekaterinburg");
define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


/**
 * вкл выкл апи вк
 * 200407 выкл
 */
// $run_api_vk = true;
$run_api_vk = false;


if (
        (!empty($_REQUEST['id']) && !empty($_REQUEST['secret']) && \Nyos\nyos::checkSecret($_REQUEST['secret'], $_REQUEST['id']) === true) ||
        (!empty($_REQUEST['id']) && !empty($_REQUEST['s']) && \Nyos\nyos::checkSecret($_REQUEST['s'], $_REQUEST['id']) === true)
) {
    
}
//
else {

    $e = '';
//    foreach ($_REQUEST as $k => $v) {
//        $e .= '<Br/>' . $k . ' - ' . $v;
//    }

    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору ' . $e // . $_REQUEST['id'] . ' && ' . $_REQUEST['secret']
            , 'error');
}




if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'send_ajax') {

    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/didrive/base/all/ajax.start.php';

    \f\Cash::$cancel_memcash = true;
    
    // \Nyos\nyos::getMenu();
    // \f\pa(\Nyos\nyos::$menu);
    //\f\pa($_REQUEST);

    $er = '';

    if (isset($_REQUEST['run_modul']) && isset(\Nyos\nyos::$menu[$_REQUEST['run_modul']])) {

        if (
                (
                isset($_REQUEST['antispam']{3}) &&
                isset($_SESSION['cash22']) &&
                (
                $_REQUEST['antispam'] == $_SESSION['cash22'] || $_REQUEST['antispam'] == $_SESSION['cash22_old']
                )
                ) ||
                (
                isset($_REQUEST['antispam']{3}) && isset($_REQUEST['s']) && \Nyos\Nyos::checkSecret($_REQUEST['s'], $_REQUEST['antispam']) !== false
                ) ||
                ( isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra'] == 'skip' )
        ) {

// echo '<pre>'; print_r($_REQUEST); echo '</pre>';
// echo '<pre>'; print_r($vv['mnu'][$_REQUEST['run_modul']); echo '</pre>';
// \f\pa(\Nyos\nyos::$menu[$_REQUEST['run_modul']]);

            foreach (\Nyos\nyos::$menu[$_REQUEST['run_modul']] as $k => $v) {

                if (isset($v['ftype']{1}) && ( $v['ftype'] == 'string' || $v['ftype'] == 'textarea' )) {
                    if (isset($v['fobyaz']{1})) {
                        if (isset($_REQUEST['bw'][$k]{1})) {
// $er .= 'заполнено поле <u>'.$v['fname'].'</u><br/>';
                        } else {
                            $er .= 'Не заполнено поле <u>' . $v['fname'] . '</u><br/>';
                        }
                    }
                }
            }


            if (isset($er{3})) {

                // die(json_encode(array('cod' => 1, 'text' => $er)));
                die(\f\end2($er, false));
            } else {

// $sender2 = 'spawn@uralweb.info';
//$status = '';
// require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/mail.2.php' );
                //require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/exe/backword/class.php' );

                $now_mod = $vv['now_level'] = Nyos\nyos::$menu[$_REQUEST['run_modul']];
//f\pa($vv['now_level']);

                $vars = array(
                    'href'      => 'http://' . $_SERVER['HTTP_HOST'] . '/'
                    , 'logo_name' => $_SERVER['HTTP_HOST']
                    , 'head'      => 'Новое сообщение с сайта'
                    , 'text'      => '<table width="100%" border="1" cellpadding="5" >'
                );

                if (stripos($_SERVER['HTTP_HOST'], 'xn--') !== false) {
                    // require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/idna_convert.class.php');
                    // $idn = new idna_convert(array('idn_version' => 2008));
                    //$punycode=isset($_REQUEST['punycode']) ? stripslashes($_REQUEST['punycode']) : '';
                    $Punycode = new Punycode();
                    $vars['logo_name'] = $Punycode->decode($_SERVER['HTTP_HOST']);
                }

                // require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'sql.start.php' );


                /**
                 * параметры для отправки сообщения через вк
                 */
//# оповещение с помощью вк
//send_vk_api = send
//vk_api_token = 2
//vk_api_send_to_id = 5903492
//if (isset(\Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_telega1'])) {
                //$msg_to_telega = 'Пришло новое сообщение с сайта ' . $vars['logo_name'] . ' ' . PHP_EOL . PHP_EOL;
                $msg_to_telega = 'Сообщение с сайта ' . PHP_EOL;
//}



                if ($run_api_vk === true && isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_vk_api']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_vk_api'] == 'send') {

                    require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/exe/api_vk/class.php' );

                    $msg_to_vk = 'Пришло новое сообщение' . PHP_EOL . PHP_EOL;
//$msg_to_vk = 'Пришло новое сообщение'.PHP_EOL.PHP_EOL;
                }

                $now_mod = Nyos\nyos::$menu[$_REQUEST['run_modul']];

                foreach ($_REQUEST['bw'] as $k => $v) {

                    $vars['text'] .= '<tr><td width="30%" >' . ( isset($now_mod[$k]['fname']) ? $now_mod[$k]['fname'] : $k ) . '</td><td>' . $v . '</td></tr>';

                    if (isset($msg_to_telega{5})) {
                        $msg_to_telega .= ( $now_mod[$k]['name_rus'] ?? $now_mod[$k]['fname'] ?? $k ) . ': ' . $v . PHP_EOL;
                    }

                    if (isset($msg_to_vk{5})) {
                        $msg_to_vk .= ' > ' . ( isset($now_mod[$k]['fname']) ? $now_mod[$k]['fname'] : $k )
                                . PHP_EOL . $v . PHP_EOL . PHP_EOL;
                    }
                }

                $vars['text'] .= '</table>

                    <br/>
                    <br/>

                    <p>С наилучшими пожеланиями, <a href="http://uralweb.info" >uralweb</a></p>
                </body></html>';

                /* отправка сообщения в вк

                  if (isset($msg_to_vk{5})) {
                  \Nyos\mod\vk_api::addTaskSendMessage($db, Nyos\nyos::$menu[$_REQUEST['run_modul']]['vk_api_token'], Nyos\nyos::$menu[$_REQUEST['run_modul']]['vk_api_send_to_id'], $msg_to_vk, 'backword5');

                  if (isset($vv['now_level']['vk_api_send_to_id2']{1})) {

                  \Nyos\mod\vk_api::addTaskSendMessage($db, Nyos\nyos::$menu[$_REQUEST['run_modul']]['vk_api_token'], $vv['now_level']['vk_api_send_to_id2'], $msg_to_vk, 'backword5');
                  }
                  }

                 */

                if (class_exists('\\Nyos\\Msg')) {
                    // if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/nyos/msg/msg.php')) {
                    // require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/nyos/msg/msg.php';

                    \Nyos\Msg::sendTelegramm($msg_to_telega, null, 2);

                    for ($e = 1; $e <= 10; $e++) {
                        if (isset($vv['now_level']['send_telega' . $e]{5})) {
                            \Nyos\Msg::sendTelegramm($msg_to_telega, $vv['now_level']['send_telega' . $e]);
                        }
                    }
                }

                if (!empty($vv['now_level']['mailto'])) {
                    $e = \Nyos\mod\backword::sendMailSuper(
                                    ( $vv['now_level']['mailot'] ?? '2@uralweb.info')
                                    , $vv['now_level']['mailto']
                                    , 'default', 'Новое письмо с сайта', $vars);
                }

                \Nyos\mod\items::addNewSimple($db, 'backword', $_REQUEST);

////$status = '';
//                f\db\db2_insert($db, 'gm_mail', array(
//                    'folder' => $now['folder'],
//                    'domain' => $_SERVER['HTTP_HOST'],
//                    'from' => isset($vv['now_level']['mailto']) ? $vv['now_level']['mailto'] : 'spawn@uralweb.info',
//                    'to' => $vv['now_level']['mailto'],
//                    'head' => $head,
//                    'message' => $body,
//                    'array_var' => serialize($_REQUEST),
//                    'd' => 'NOW',
//                    't' => 'NOW'
//                ));
//// $status;

                \f\end2('<br/><br/><br/><br/><br/>Спасибо, данные отправлены');

// die(json_encode(array('cod' => 0, 'text' => 'Спасибо, данные отправлены')));
//die(json_encode(array('cod' => 0, 'text' => 'ОК, отправка прошла успешно' ) ));
            }
        }
        //
        else {
            \f\end2('Неверно указаны цифры', false);
        }
        \f\end2('Что то пошло не так, позвоните пожалуйста', false);
    }
    //
    else {
        \f\end2(__LINE__ . ' Повторите отправку формы пожалуйста', false);
    }
}

\f\end2('Повторите отправку формы пожалуйста', false);

die('the end');
