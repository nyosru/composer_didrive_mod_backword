<?php

use TrueBV\Punycode;

ini_set('display_errors', 'On'); // сообщения с ошибками будут показываться
error_reporting(E_ALL); // E_ALL - отображаем ВСЕ ошибки

date_default_timezone_set("Asia/Yekaterinburg");
define('IN_NYOS_PROJECT', true);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';




if (!empty($_REQUEST['id']) && !empty($_REQUEST['secret']) && \Nyos\nyos::checkSecret($_REQUEST['secret'], $_REQUEST['id']) === true) {
    
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

    require $_SERVER['DOCUMENT_ROOT'] . '/all/ajax.start.php';

    // \Nyos\nyos::getMenu();
    // \f\pa(\Nyos\nyos::$menu);
    //\f\pa($_REQUEST);

    $er = '';

    if (isset($_REQUEST['run_modul']) && isset(\Nyos\nyos::$menu[$_REQUEST['run_modul']])) {

        if (
                ( isset($_REQUEST['antispam']{3}) && ( $_REQUEST['antispam'] == $_SESSION['cash22'] || $_REQUEST['antispam'] == $_SESSION['cash22_old'] ) ) ||
                ( isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra'] == 'skip' )
        ) {

//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
//echo '<pre>'; print_r($vv['mnu'][$_REQUEST['run_modul']); echo '</pre>';
//            \f\pa(\Nyos\nyos::$menu[$_REQUEST['run_modul']]);

            foreach (\Nyos\nyos::$menu[$_REQUEST['run_modul']] as $k => $v) {

                if (isset($v['ftype']{1}) && (
                        $v['ftype'] == 'string' || $v['ftype'] == 'textarea'
                        )
                ) {

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
                    'href' => 'http://' . $_SERVER['HTTP_HOST'] . '/'
                    , 'logo_name' => $_SERVER['HTTP_HOST']
                    , 'head' => 'Новое сообщение с сайта'
                    , 'text' => '<table width="100%" border="1" cellpadding="5" >'
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
                $msg_to_telega = 'Пришло новое сообщение с сайта ' . PHP_EOL;
//}

                if (isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_vk_api']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_vk_api'] == 'send') {

                    require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/exe/api_vk/class.php' );

                    $msg_to_vk = 'Пришло новое сообщение' . PHP_EOL . PHP_EOL;
//$msg_to_vk = 'Пришло новое сообщение'.PHP_EOL.PHP_EOL;
                }

                foreach ($_REQUEST['bw'] as $k => $v) {

                    $vars['text'] .= '<tr><td width="30%" >' . ( isset($now_mod[$k]['fname']) ? $now_mod[$k]['fname'] : $k ) . '</td><td>' . $v . '</td></tr>';

                    if (isset($msg_to_telega{5})) {
                        $msg_to_telega .= ( $now_mod[$k]['name_rus'] ?? $k ) . ': ' . $v . PHP_EOL;
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

                    \Nyos\Msg::sendTelegramm($msg_to_telega, null, 1);

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

                \f\end2('Спасибо, данные отправлены');

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

//sleep(1);

require( $_SERVER['DOCUMENT_ROOT'] . '/index.session_start.php' );
require($_SERVER['DOCUMENT_ROOT'] . '/0.site/0.start.php');

// require( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/nyos.2.php' );
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.all/f/ajax.php' );
// проверяем секрет
if (isset($_REQUEST['id']{0}) && isset($_REQUEST['secret']{5}) &&
        Nyos\nyos::checkSecret($_REQUEST['secret'], $_REQUEST['id']) === true) {
    
} else {
    f\end2('Произошла неописуемая ситуация #' . __LINE__ . ' обратитесь к администратору', 'error');
}

// require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php');
//require( $_SERVER['DOCUMENT_ROOT'] . '/0.site/exe/peticii/class.php');

require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
require_once ( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );



if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'send_ajax') {


    require($_SERVER['DOCUMENT_ROOT'] . '/0.site/0.start.php');

    $vv['mnu'] = Nyos\nyos::creat_menu($now['folder']);

//echo '<pre>'; print_r($nyos->menu); echo '</pre>';
//echo '<pre>'; print_r($vv['mnu']); echo '</pre>';
//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
// name="run_modul" value="090.order.creat.site"

    $er = '';

    if (isset($_REQUEST['run_modul']) && isset(Nyos\nyos::$menu[$_REQUEST['run_modul']])) {
        if (
                (
                isset($_REQUEST['antispam']{3}) &&
                (
                $_REQUEST['antispam'] == $_SESSION['cash22'] ||
                $_REQUEST['antispam'] == $_SESSION['cash22_old']
                )
                ) ||
                (
                isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['chek_cifra']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['chek_cifra'] == 'skip'
                ) ||
                (
                isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra'] == 'skip'
                )
        ) {

//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
//echo '<pre>'; print_r($vv['mnu'][$_REQUEST['run_modul']); echo '</pre>';

            foreach (Nyos\nyos::$menu[$_REQUEST['run_modul']] as $k => $v) {

                if (isset($v['ftype']{1}) && (
                        $v['ftype'] == 'string' || $v['ftype'] == 'textarea'
                        )
                ) {

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
                die(json_encode(array('cod' => 1, 'text' => $er)));
            } else {





// $sender2 = 'spawn@uralweb.info';
//$status = '';
// require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/mail.2.php' );
                require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/exe/backword/class.php' );

                $now_mod = $vv['now_level'] = Nyos\nyos::$menu[$_REQUEST['run_modul']];
//f\pa($vv['now_level']);

                $vars = array(
                    'href' => 'http://' . $_SERVER['HTTP_HOST'] . '/'
                    , 'logo_name' => $_SERVER['HTTP_HOST']
                    , 'head' => 'Новое сообщение с сайта'
                    , 'text' => '<table width="100%" border="1" cellpadding="5" >'
                );

                if (stripos($_SERVER['HTTP_HOST'], 'xn--') !== false) {
                    require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/idna_convert.class.php');
                    $idn = new idna_convert(array('idn_version' => 2008));
//$punycode=isset($_REQUEST['punycode']) ? stripslashes($_REQUEST['punycode']) : '';
                    $vars['logo_name'] = $idn->decode($_SERVER['HTTP_HOST']);
                }

                require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'sql.start.php' );


                /**
                 * параметры для отправки сообщения через вк
                 */
//# оповещение с помощью вк
//send_vk_api = send
//vk_api_token = 2
//vk_api_send_to_id = 5903492
//if (isset(\Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_telega1'])) {
                $msg_to_telega = 'Пришло новое сообщение с сайта ' . $vars['logo_name'] . ' ' . PHP_EOL . PHP_EOL;
//}

                if (isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_vk_api']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['send_vk_api'] == 'send') {
                    require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/exe/api_vk/class.php' );
                    $msg_to_vk = 'Пришло новое сообщение с сайта ' . $vars['logo_name'] . ' ' . PHP_EOL . PHP_EOL;
//$msg_to_vk = 'Пришло новое сообщение'.PHP_EOL.PHP_EOL;
                }

                foreach ($_REQUEST['bw'] as $k => $v) {
                    $vars['text'] .= '<tr><td width="30%" >' . ( isset($now_mod[$k]['fname']) ? $now_mod[$k]['fname'] : $k ) . '</td><td>' . $v . '</td></tr>';

                    if (isset($msg_to_telega{5})) {
                        $msg_to_telega .= ' > ' . ( isset($now_mod[$k]['fname']) ? $now_mod[$k]['fname'] : $k )
                                . PHP_EOL . $v . PHP_EOL . PHP_EOL;
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

                if (isset($msg_to_vk{5})) {
                    \Nyos\mod\vk_api::addTaskSendMessage($db, Nyos\nyos::$menu[$_REQUEST['run_modul']]['vk_api_token'], Nyos\nyos::$menu[$_REQUEST['run_modul']]['vk_api_send_to_id'], $msg_to_vk, 'backword5');

                    if (isset($vv['now_level']['vk_api_send_to_id2']{1})) {

                        \Nyos\mod\vk_api::addTaskSendMessage($db, Nyos\nyos::$menu[$_REQUEST['run_modul']]['vk_api_token'], $vv['now_level']['vk_api_send_to_id2'], $msg_to_vk, 'backword5');
                    }
                }

                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/nyos/msg/msg.php')) {

                    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/nyos/msg/msg.php';

                    \Nyos\Msg::sendTelegramm($msg_to_telega, null, 1);

                    for ($e = 1; $e <= 10; $e++) {
                        if (isset($vv['now_level']['send_telega' . $e]{5})) {
                            \Nyos\Msg::sendTelegramm($msg_to_telega, $vv['now_level']['send_telega' . $e]);
// \nyos\Msg::sendTelegramm($msg_to_telega);
// $msg_to_telega
                        }
                    }
                }

                $e = Nyos\backword::sendMailSuper(
                                isset($vv['now_level']['mailot']{1}) ? $vv['now_level']['mailot'] : '2@uralweb.info'
                                , $vv['now_level']['mailto']
                                , 'default', 'Новое письмо с сайта', $vars);
// sendNow($db, $from, $to, $head = 'Сообщение', $tpl = 'default', $dop = null) {
//f\pa($e);


                if (1 == 2) {

                    $tt = '';

                    for ($r = 1; $r <= 10; $r++) {
                        if (isset($vv['now_level']['form']['pole' . $r]) && isset($_POST[$vv['now_level']['form']['pole' . $r]])) {
                            $tt .= '<tr><td>' . $vv['now_level']['form']['pole' . $r . 'name'] . '</td><td>' . $_POST[$vv['now_level']['form']['pole' . $r]] . '</td></tr>';
                        }
                    }

                    $emailer->ns_new(isset($vv['now_level']['mailot']{1}) ? $vv['now_level']['mailot'] : '2@uralweb.info', $vv['now_level']['mailto'] . ',support@uralweb.info');
                    $head = ' > Сообщение с сайта ' . $_SERVER['HTTP_HOST'];
                    $body = '<html><body>

                <h2>Сообщение с сайта</h2>

                <table border="1" cellpadding="5" >' . $tt . '</table>

                <p>С наилучшими пожеланиями, <a href="http://uralweb.info" >uralweb</a></p>

                <br/>
                <br/>
                <br/>
                <center style="color:gray" >
                <small>Вы можете отписаться, напишите на сайте <a href="http://uralweb.info/' . rand(1000, 9999) . '/" >uralweb.info</a> или отправьте просьбу на email <u>support@uralweb.info</u></small>
                </center>
                </body></html>';

                    $emailer->ns_send($head, $body);
//echo $status;
//echo '<pre>'.htmlspecialchars($ClassTemplate->tpl_files['bw.mail.body']).'</pre>';
                }


                /*
                  require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php' );
                  require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
                  require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );
                  require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'db.2.php' );
                 */

//$status = '';
                f\db\db2_insert($db, 'gm_mail', array(
                    'folder' => $now['folder'],
                    'domain' => $_SERVER['HTTP_HOST'],
                    'from' => isset($vv['now_level']['mailto']) ? $vv['now_level']['mailto'] : 'spawn@uralweb.info',
                    'to' => $vv['now_level']['mailto'],
                    'head' => $head,
                    'message' => $body,
                    'array_var' => serialize($_REQUEST),
                    'd' => 'NOW',
                    't' => 'NOW'
                ));
// $status;

                f\end2('Спасибо, данные отправлены');

// die(json_encode(array('cod' => 0, 'text' => 'Спасибо, данные отправлены')));
//die(json_encode(array('cod' => 0, 'text' => 'ОК, отправка прошла успешно' ) ));
            }
        } else {
            f\end2('Неверно указаны цифры', false);
        }
    }

    f\end2('Повторите отправку формы пожалуйста', false);
}


// удаление записи
elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'action-item' && isset($_REQUEST['go']) && $_REQUEST['go'] == 'delete') {

    $db->sql_query('UPDATE `gm_message` SET `status` = \'delete\' WHERE `id` = \'' . $_REQUEST['id'] . '\' LIMIT 1 ;');
    f\end2('запись удалена');
}

f\end2('Повторите отправку формы пожалуйста', false);


if (1 == 2) {

//die(json_encode(array('cod' => 1, 'text' => 'Повторите отправку формы пожалуйста' ) ));

    /*

      <form method="post" action="#" id="ajaxform" >
      <div>
      <div class="row">
      <div class="6u 12u(mobile)">
      <input type="text" name="fio" id="name" placeholder="Имя Отчество" rel="Имя Отчество"  />
      </div>
      <div class="6u 12u(mobile)">
      <input type="text" name="tel" id="gsm" placeholder="Телефон" rel="Телефон" />
      </div>
      </div>

      {*
      <div class="row">
      <div class="12u">
      <input type="text" name="subject" id="subject" placeholder="Subject" />
      </div>
      </div>
     * }

      <div class="row">
      <div class="12u">
      <textarea name="message" id="message" placeholder="Заявка, сообщение" rel="Заявка, сообщение" ></textarea>
      </div>
      </div>

      <div class="row 200%">
      <div class="12u">
      <ul class="actions">
      <li><input type="submit" value="Отправить" /></li>
      {*
      <li><input type="reset" value="Clear Form" class="alt" /></li>
     * }
      </ul>
      </div>
      </div>

      </div>
      <input type="hidden" name="run_modul" value="090.order.creat.site" />
      </form>

      <div class="modal-body" id="form1res" style="display:none" >
      <p>Сообщение отправлено.</p>
      </div>
     */

    /*

      $("#ajaxform").submit( function(){ // пeрeхвaтывaeм всe при сoбытии oтпрaвки

      var form = $(this); // зaпишeм фoрму, чтoбы пoтoм нe былo прoблeм с this
      var error = false; // прeдвaритeльнo oшибoк нeт

      form.find('input, textarea').each( function(){ // прoбeжим пo кaждoму пoлю в фoрмe
      if( $(this).val() == '' )
      { // eсли нaхoдим пустoe
      alert('Зaпoлнитe пoлe "'+$(this).attr('rel')+'" !'); // гoвoрим зaпoлняй!
      error = true; // oшибкa
      }
      });

      if (!error) { // eсли oшибки нeт

      var data = form.serialize(); // пoдгoтaвливaeм дaнныe
      $.ajax({ // инициaлизируeм ajax зaпрoс

      type: 'POST', // oтпрaвляeм в POST фoрмaтe, мoжнo GET
      url: '/0.site/exe/backword/5/ajax.php', // путь дo oбрaбoтчикa, у нaс oн лeжит в тoй жe пaпкe
      dataType: 'json', // oтвeт ждeм в json фoрмaтe
      data: data, // дaнныe для oтпрaвки

      beforeSend: function(data) { // сoбытиe дo oтпрaвки
      form.find('input[type="submit"]').attr('disabled', 'disabled'); // нaпримeр, oтключим кнoпку, чтoбы нe жaли пo 100 рaз
      },
      success: function(data){ // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
      if( data['error'] )
      { // eсли oбрaбoтчик вeрнул oшибку
      alert( data['error'] ); // пoкaжeм eё тeкст
      }
      else
      { // eсли всe прoшлo oк
      // alert('Письмo oтврaвлeнo! Чeкaйтe пoчту! =)'); // пишeм чтo всe oк
      $( form ).hide();
      $( '#form1btn' ).hide();
      $('#form1res').show('slow');
      }
      },

      {/literal}{*
      error: function (xhr, ajaxOptions, thrownError) { // в случae нeудaчнoгo зaвeршeния зaпрoсa к сeрвeру
      alert( xhr.status ); // пoкaжeм oтвeт сeрвeрa
      alert( thrownError ); // и тeкст oшибки
      },
     * }{literal}

      complete: function( data ){ // сoбытиe пoслe любoгo исхoдa
      form.find('input[type="submit"]').prop('disabled', false); // в любoм случae включим кнoпку oбрaтнo
      }

      });
      }

      return false; // вырубaeм стaндaртную oтпрaвку фoрмы
      });
      });

     */

    date_default_timezone_set("Asia/Yekaterinburg");

    require($_SERVER['DOCUMENT_ROOT'] . '/0.site/0.start.php');

    $vv['mnu'] = Nyos\nyos::creat_menu($now['folder']);

//echo '<pre>'; print_r($nyos->menu); echo '</pre>';
//echo '<pre>'; print_r($vv['mnu']); echo '</pre>';
//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
// name="run_modul" value="090.order.creat.site"

    $er = '';

    if (isset($_REQUEST['run_modul']) && isset(Nyos\nyos::$menu[$_REQUEST['run_modul']])) {
        if (
                (
                isset($_REQUEST['antispam']{3}) &&
                (
                $_REQUEST['antispam'] == $_SESSION['cash22'] ||
                $_REQUEST['antispam'] == $_SESSION['cash22_old']
                )
                ) ||
                (
                isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['chek_cifra']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['chek_cifra'] == 'skip'
                ) ||
                (
                isset(Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra']) && Nyos\nyos::$menu[$_REQUEST['run_modul']]['check_cifra'] == 'skip'
                )
        ) {

//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
//echo '<pre>'; print_r($vv['mnu'][$_REQUEST['run_modul']); echo '</pre>';

            foreach (Nyos\nyos::$menu[$_REQUEST['run_modul']] as $k => $v) {

                if (isset($v['ftype']{1}) && (
                        $v['ftype'] == 'string' || $v['ftype'] == 'textarea'
                        )
                ) {

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
                die(json_encode(array('cod' => 1, 'text' => $er)));
            } else {




                $sender2 = 'spawn@uralweb.info';
//$status = '';

                require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/mail.php' );

                $vv['now_level'] = Nyos\nyos::$menu[$_REQUEST['run_modul']];
//f\pa($vv['now_level']);

                $tt = '';

                for ($r = 1; $r <= 10; $r++) {
                    if (isset($vv['now_level']['form']['pole' . $r]) && isset($_POST[$vv['now_level']['form']['pole' . $r]])) {
                        $tt .= '<tr><td>' . $vv['now_level']['form']['pole' . $r . 'name'] . '</td><td>' . $_POST[$vv['now_level']['form']['pole' . $r]] . '</td></tr>';
                    }
                }

                $emailer->ns_new(isset($vv['now_level']['mailot']{1}) ? $vv['now_level']['mailot'] : '2@uralweb.info', $vv['now_level']['mailto'] . ',support@uralweb.info');
                $head = ' > Сообщение с сайта ' . $_SERVER['HTTP_HOST'];
                $body = '<html><body>

                <h2>Сообщение с сайта</h2>

                <table border="1" cellpadding="5" >' . $tt . '</table>

                <p>С наилучшими пожеланиями, <a href="http://uralweb.info" >uralweb</a></p>

                <br/>
                <br/>
                <br/>
                <center style="color:gray" >
                <small>Вы можете отписаться, напишите на сайте <a href="http://uralweb.info/' . rand(1000, 9999) . '/" >uralweb.info</a> или отправьте просьбу на email <u>support@uralweb.info</u></small>
                </center>
                </body></html>';

                $emailer->ns_send($head, $body);
//echo $status;
//echo '<pre>'.htmlspecialchars($ClassTemplate->tpl_files['bw.mail.body']).'</pre>';

                require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.site/0.cfg.start.php' );
                require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'class' . DS . 'mysql.php' );
                require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'db.connector.php' );
                require_once( $_SERVER['DOCUMENT_ROOT'] . DS . '0.all' . DS . 'f' . DS . 'db.2.php' );

                $status = '';
                f\db\db2_insert($db, 'gm_mail', array(
                    'folder' => $now['folder'],
                    'domain' => $_SERVER['HTTP_HOST'],
                    'from' => $sender2,
                    'to' => $vv['now_level']['mailto'] . ' ',
                    'head' => $head,
                    'message' => $body,
                    'array_var' => serialize($_REQUEST),
                    'd' => 'NOW',
                    't' => 'NOW'
                ));

                die(json_encode(array('cod' => 0, 'text' => 'Спасибо, данные отправлены' . $status)));
//die(json_encode(array('cod' => 0, 'text' => 'ОК, отправка прошла успешно' ) ));
            }
        } else {
            die(json_encode(array('cod' => 1, 'text' => 'Неверно указаны цифры')));
        }
    }

    die(json_encode(array('cod' => 1, 'text' => 'Произошёл технический сбой, обновите страницу и отправте данные повторно пожалуйста')));

    exit;











    if (isset($_REQUEST['run_modul']) && isset(Nyos\nyos::$menu[$_REQUEST['run_modul']])) {

//echo '<pre>'; print_r($nyos->menu[$_REQUEST['run_modul']]); echo '</pre>';

        $m = $er = '';
        $c = Nyos\nyos::$menu[$_REQUEST['run_modul']];

        for ($i = 1; $i <= 20; $i++) {

            if (isset($c['form']['pole' . $i]{0})) {
                $m .= ( isset($m{2}) ? '<br/>' : '' )
                        . ( isset($c['form']['pole' . $i . 'name']{0}) ? $c['form']['pole' . $i . 'name'] : $c['form']['pole' . $i] ) . ': ' . htmlspecialchars($_REQUEST[$c['form']['pole' . $i]]);
            }

            if (isset($c['form']['pole' . $i]{0}) && isset($c['form']['pole' . $i . 'ob']{0}) && !isset($_REQUEST[$c['form']['pole' . $i]]{0})
            ) {
                $er .= ( isset($er{0}) ? '<br/>' : '' )
                        . 'Заполните поле "' . ( isset($c['form']['pole' . $i . 'name']{0}) ? $c['form']['pole' . $i . 'name'] : $c['form']['pole' . $i] ) . '" ';
            }
        }

        if (!isset($er{2})) {
            require( DirAll . 'class' . DS . 'mail.php' );


            if (stripos(domain, 'xn--') !== false) {
                require( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/idna_convert.class.php');
                $idn = new idna_convert(array('idn_version' => 2008));
//$punycode=isset($_REQUEST['punycode']) ? stripslashes($_REQUEST['punycode']) : '';
                $dd = $idn->decode(domain);
            } else {
                $dd = domain;
            }

//$status = '';
            $emailer->ns_new(( isset($c['mailot']{1}) ? $c['mailot'] : 'support@uralweb.info')
                    , ( isset($c['mailto']{1}) ? $c['mailto'] : 'support@uralweb.info'));
            $emailer->ns_send($dd . ' > ' . ( isset($c['mail_subject']{1}) ? $c['mail_subject'] : 'новое сообщение' ), '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"></head><body>'
                    . '<h2>Новое письмо с сайта</h2>'
                    . '<p>' . $m . '</p>'
                    . '<hr>'
                    . '<small>Система сайтов <a href="http://uralweb.info" >Uralweb</a></small>'
                    . '</body></html>');
//echo $status;

            die(json_encode(array('cod' => 0, 'text' => 'Спасибо, отправлено успешно.')));
        } else {
            die(json_encode(array('error' => $er)));
        }
    } else {
        die(json_encode(array('error' => 'Что то пошло не так (ошибка №' . __LINE__ . ')')));
    }


    exit;






//session_start();
    require ( $_SERVER['DOCUMENT_ROOT'] . '/index.session_start.php' );

//echo '<pre>'; print_r($_REQUEST); echo '</pre>'; echo '<hr>';
//echo '<pre>'; print_r($_SESSION); echo '</pre>'; echo '<hr>';

    if (isset($_REQUEST['s']{1}) && isset($_REQUEST['s2']{1}) && (
            md5(date('h', $_SERVER['REQUEST_TIME']) . '-' . $_REQUEST['s2']) || md5(date('h', $_SERVER['REQUEST_TIME'] - 3600) . '-' . $_REQUEST['s2']) || md5(date('h', $_SERVER['REQUEST_TIME'] - 7200) . '-' . $_REQUEST['s2'])
            )
    ) {

        $msg = '';

        if (!isset($_REQUEST['fio']{2})) {
            $msg .= ( isset($msg{1}) ? '<br/>' : '' ) . ' Не указано как Вас зовут';
        }
        if (isset($_REQUEST['gsm']) && !isset($_REQUEST['gsm']{5})) {
            $msg .= ( isset($msg{1}) ? '<br/>' : '' ) . ' Не указан телефон';
        }
        if (isset($_REQUEST['email']) && !isset($_REQUEST['email']{4})) {
            $msg .= ( isset($msg{1}) ? '<br/>' : '' ) . ' Не указан E-mail';
        }

        if (!isset($msg{1})) {

//define('IN_NYOS_PROJECT',true);
            require( $_SERVER['DOCUMENT_ROOT'] . '/index.cfg.start.php' );

//echo DirSite;
            $cfg = unserialize(file_get_contents(DirSite . '/module/' . strtolower(str_replace('www.', '', $_SERVER['HTTP_HOST']) . '_cash.cfg.php')));

//echo '<pre>'; print_r( $cfg ); echo '</pre>';

            if (isset($cfg[$_REQUEST['level']])) {
                $ccfg = $cfg[$_REQUEST['level']];
            }

// если локаль то смс-ки не обрабатываем
            if (strpos($_SERVER['DOCUMENT_ROOT'], 'W:') === false &&
                    (
                    ( isset($ccfg['smstoken']{1}) ) ||
                    ( isset($ccfg['sms_login']{1}) && isset($ccfg['sms_pass']{1}) )
                    ) && isset($ccfg['gsm']{3})
            ) {
                $ttxt = '';

                foreach ($_REQUEST as $k => $v) {
                    if (isset($ccfg[$k . '_sendsms']))
                        $ttxt .= ( isset($ttxt{2}) ? "
" : '' ) . $v;
                }

                if (isset($ttxt{3})) {

                    $xml = '<?xml version="1.0" encoding="utf-8" ?>
                    <request>
                        <message type="sms">
                            <sender>' . ( isset($ccfg['smssender']{1}) ? $ccfg['smssender'] : 'msg-site' ) . '</sender>
                            <text>' . $ttxt . '</text>
                            <abonent phone="' . $ccfg['gsm'] . '" />
                        </message>';

                    if (isset($ccfg['smstoken']{1})) {
                        $xml .= '<security>
                            <token value="' . $ccfg['smstoken'] . '" />
                        </security>';
                    } elseif (isset($ccfg['sms_login']{1}) && isset($ccfg['sms_pass']{1})) {
                        $xml .= '<security>
                            <login value="' . $ccfg['sms_login'] . '" />
                            <password value="' . $ccfg['sms_pass'] . '" />
                        </security>';
                    }

                    $xml .= '</request>';
                    $urltopost = 'http://xml.sms16.ru/xml/';

                    /**
                     * Initialize handle and set options
                     */
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CRLF, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                    curl_setopt($ch, CURLOPT_URL, $urltopost);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

                    /**
                     * Execute the request
                     */
                    $result = curl_exec($ch);

                    /**
                     * Check for errors
                     */
                    if (curl_errno($ch)) {
                        $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
                    } else {
                        $returnCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

                        if ($returnCode == 404) {
                            $result = 'ERROR -> 404 Not Found';
                        }
                    }

                    /**
                     * Close the handle
                     */
                    curl_close($ch);
                }
            }

            if (isset($_REQUEST['fio'])) {
//        $InVarB = array();
//        $InVarB['host'] = $_SERVER['HTTP_HOST'];
//        $InVarB['ip'] = $_SERVER['REMOTE_ADDR'];
//        $InVarB['date'] = date('d m Y',time());
//        $InVarB['time'] = date('H:i:s',time());
//$ctpl -> ins_page('bw.mail.body', 'input var', $_t_tfo1);

                $sender2 = 'spawn@uralweb.info';
//$status = '';

                $ttxt = '';

                foreach ($_REQUEST as $k => $v) {
                    if (isset($ccfg[$k . '_sendmail']))
                        $ttxt .= '<tr><td>' . ( isset($ccfg[$k . '_name']{0}) ? $ccfg[$k . '_name'] : $k ) . '</td><td>' . $v . '</td></tr>';
                }

                $d = array(
                    'Дата' => date('d.m.Y', $_SERVER['REQUEST_TIME'])
                    , 'Время' => date('H:i', $_SERVER['REQUEST_TIME'])
                );

                foreach ($d as $k => $v) {
                    $ttxt .= '<tr><td>' . $k . '</td><td>' . $v . '</td></tr>';
                }

                require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/mail.php' );

                $emailer->ns_new($sender2, $ccfg['email']);
                $emailer->ns_send('сайт: Новая заявка', '<html><body><h2>Новая заявка ' . $_SERVER['HTTP_HOST'] . '</h2>
                <table cellpadding="10" >' . $ttxt . '</table>
                </body></html>');
//echo $status;
//echo '<pre>'.htmlspecialchars($ClassTemplate->tpl_files['bw.mail.body']).'</pre>';
            }

            if (isset($_REQUEST['gsm'])) {
                $txte = ' позвоним';
            } elseif (isset($_REQUEST['email'])) {
                $txte = ' пришлём e-mail письмо';
            }

            die(json_encode(array('text' => 'Данные отправлены. В&nbsp;ближайшее время ' . $txte)));
        } else {
            die(json_encode(array('cod' => 'error', 'text' => $msg)));
        }

        die(json_encode(array('cod' => 'error', 'text' => 'Произошла непредвиденная ситуация, обновите страницу и отправьте заново пожалуйста')));
    } elseif (isset($_REQUEST['cash']{3}) &&
            (
            $_REQUEST['cash'] == $_SESSION['cash22'] ||
            $_REQUEST['cash'] == $_SESSION['cash22_old']
            )
    ) {
// обработка формы - старт

        $polya = $_REQUEST;

        $error_form = false;

        if (!isset($_REQUEST['fio']{2})) {
            $error_form = true;
            $msg .= ( isset($msg{1}) ? '<br/>' : '' ) . ' Не указано как Вас зовут';
        }
        if (!isset($_REQUEST['tel']{5})) {
            $error_form = true;
            $msg .= ( isset($msg{1}) ? '<br/>' : '' ) . ' Не указан телефон';
        }

// отправка мыла старт

        if ($error_form === false) {

// sms отправка
            if (1 == 1 && strpos($_SERVER['HTTP_HOST'], 'uralweb.info') !== FALSE) {

                $xml = '<?xml version="1.0" encoding="utf-8" ?>
                <request>
                    <message type="sms">
                        <sender>msg-site</sender>
                        <text>' . $_REQUEST['fio'] . '/' . $_REQUEST['tel'] . '/' . htmlspecialchars($_REQUEST['opis']) . '</text>
                        <abonent phone="79222622289"/>
                    </message>
                    <security>
                        <login value="nyos2" />
                        <password value="123nyos123" />
                    </security>
                </request>';
                $urltopost = 'http://xml.sms16.ru/xml/';

                /**
                 * Initialize handle and set options
                 */
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CRLF, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                curl_setopt($ch, CURLOPT_URL, $urltopost);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

                /**
                 * Execute the request
                 */
                $result = curl_exec($ch);

                /**
                 * Check for errors
                 */
                if (curl_errno($ch)) {
                    $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
                } else {
                    $returnCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($returnCode == 404) {
                        $result = 'ERROR -> 404 Not Found';
                    }
                }

                /**
                 * Close the handle
                 */
                curl_close($ch);

                /**
                 * Output the results
                 */
//echo $result;
            }

            $InVarB = array();
            $InVarB['host'] = $_SERVER['HTTP_HOST'];
            $InVarB['ip'] = $_SERVER['REMOTE_ADDR'];
            $InVarB['date'] = date('d m Y', time());
            $InVarB['time'] = date('H:i:s', time());

//$ctpl -> ins_page('bw.mail.body', 'input var', $_t_tfo1);

            $sender2 = 'spawn@uralweb.info';
//$status = '';

            require_once( $_SERVER['DOCUMENT_ROOT'] . '/0.all/class/mail.php' );

            $emailer->ns_new($sender2, 'nyos@me.com,support@uralweb.info');
            $emailer->ns_send('uralweb_info > Новая заявка (создание сайта)', '<html><body><h2>Новая заявка (создание сайта)</h2>
            <p>ФИО: ' . htmlspecialchars($_REQUEST['fio']) . '</p>
            <p>Тел: ' . htmlspecialchars($_REQUEST['tel']) . '</p>
            <p>Меседж: ' . nl2br(htmlspecialchars($_REQUEST['opis'])) . '</p>
            </body></html>');
//echo $status;
//echo '<pre>'.htmlspecialchars($ClassTemplate->tpl_files['bw.mail.body']).'</pre>';

            die(json_encode(array('cod' => 0, 'text' => 'Заявка принята, Спасибо.')));

//$ctpl -> ins_page('bw.body', 'insert', 'bw.ok');
        }
    } else {
        die(json_encode(array('cod' => 1, 'text' => 'Не верно указаны цифры')));
    }
}