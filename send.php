<?php
// // Сообщение
// $message = $_POST['data'];

// // На случай если какая-то строка письма длиннее 70 символов мы используем wordwrap()
// $message = wordwrap($message, 70, "\r\n");

// // Отправляем
// mail('sleepingftx@gmail.com', 'Новая заявка от '+$_POST['number'], $message);


$partnerName='Заявка с сайта aktivcredit.ru';
$Name=$_POST['nname'];
$site= $_POST['ssite'];
$html='<ul>';

$html.='<li>Имя: '.$_POST['nname'].'</li>';
$html.='<li>Номер: '.$_POST['nnumber'].'</li>';
$html.='<li>Email: '.$_POST['eemail'].'</li>';
$html.='<li>Сайт: '.$site.'</li>';

$html.='</ul>';


 $mail = array(
        'to' => "pos@aktivkredit.ru",
        'subject' => 'Новая заявка от '.$_POST['nname'],
        'message' => "<html><body>".$html."</body></html>",
        'headers' => "MIME-Version: 1.0\r\n"."Content-type: text/html; charset=utf-8\r\n");

   if ($_POST['nnumber']) {
        addsd2($Name,$_POST['nnumber'],$_POST['eemail'],$partnerName, $_POST['preferred_connection'], '3207754',$site);
        echo 1;
      } else echo 0;




function auth1(){
#Массив с параметрами, которые нужно передать методом POST к API системы
$user=array(
  'USER_LOGIN'=>'pos-credit@yandex.ru', #Ваш логин (электронная почта)
  'USER_HASH'=>'de75ce5f1673947a0b80b22c2e118fdd6183fd12' #Хэш для доступа к API (смотрите в профиле пользователя)
);
$subdomain='fortunaperm'; #Наш аккаунт - поддомен
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
/* Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP). Вы также
можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP. */
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//writeToLog2($out, 'Запрос 2.1-повтор');
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
curl_close($curl); #Завершаем сеанс cURL
/* Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code=(int)$code;
$errors=array(
  301=>'Moved permanently',
  400=>'Bad request',
  401=>'Unauthorized',
  403=>'Forbidden',
  404=>'Not found',
  500=>'Internal server error',
  502=>'Bad gateway',
  503=>'Service unavailable'
);
try
{
  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
 if($code!=200 && $code!=204)
    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
}
catch(Exception $E)
{
  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
}
/*
 Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 нам придётся перевести ответ в формат, понятный PHP
 */
$Response=json_decode($out,true);
$Response=$Response['response'];
if(isset($Response['auth'])) #Флаг авторизации доступен в свойстве "auth"
 //return 'Авторизация прошла успешно';
 return true;
//return 'Авторизация не удалась';
return false;
}

function addsd2($cname,$cphone,$cemail,$stags, $cconnection, $pipeline,$site){
if(!auth1()) die('Нет соединения с amoCRM');
$contact_name = $cname; //Название добавляемого контакта
$contact_phone = $cphone; //Телефон контакта
$contact_email = $cemail; //Емейл контакта
$contact_connection = $cconnection; // приорититный тип связи
$lead_name = $cname; //Название добавляемой сделки


//$responsible_user_id = 7292136; //id ответственного по сделке, контакту, компании

//$lead_status_id = '11331793'; //id этапа продаж, куда помещать сделку

//АВТОРИЗАЦИЯ
$user=array(
	'USER_LOGIN'=>'pos-credit@yandex.ru', #Ваш логин (электронная почта)
	'USER_HASH'=>'de75ce5f1673947a0b80b22c2e118fdd6183fd12' #Хэш для доступа к API (смотрите в профиле пользователя)
);
$subdomain='fortunaperm';
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_POST,true);
curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($user));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//writeToLog2($out, 'Запрос 2.1');
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
curl_close($curl);  #Завершаем сеанс cURL
$Response=json_decode($out,true);
$Response=$Response['response'];
//echo '<b>Авторизация:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
//ПОЛУЧАЕМ ДАННЫЕ АККАУНТА
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/accounts/current'; #$subdomain уже объявляли выше
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//writeToLog2($out, 'Запрос 2.2');
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);
$Response=json_decode($out,true);
$account=$Response['response']['account'];
//echo '<b>Данные аккаунта:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
//ПОЛУЧАЕМ СУЩЕСТВУЮЩИЕ ПОЛЯ
$amoAllFields = $account['custom_fields']; //Все поля
$amoConactsFields = $account['custom_fields']['contacts']; //Поля контактов
//echo '<b>Поля из амо:</b>'; echo '<pre>'; print_r($amoConactsFields); echo '</pre>';
//ФОРМИРУЕМ МАССИВ С ЗАПОЛНЕННЫМИ ПОЛЯМИ КОНТАКТА
//Стандартные поля амо:
$sFields = array_flip(array(
		'PHONE', //Телефон. Варианты: WORK, WORKDD, MOB, FAX, HOME, OTHER
		'EMAIL' //Email. Варианты: WORK, PRIV, OTHER
	)
);
//Проставляем id этих полей из базы амо
foreach($amoConactsFields as $afield) {
	if(isset($sFields[$afield['code']])) {
		$sFields[$afield['code']] = $afield['id'];
	}
}
//ДОБАВЛЯЕМ СДЕЛКУ
$leads['request']['leads']['add']=array(
	array(
		'name' => $lead_name,
		'tags'=>$stags,
		'pipeline_id'=>$pipeline

		//'status_id' => $lead_status_id, //id статуса
		//'responsible_user_id' => $responsible_user_id, //id ответственного по сделке
		//'date_create'=>1298904164, //optional
		//'price'=>300000,
		//'tags' => 'Important, USA', #Теги
		//'custom_fields'=>array()
	)
);
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//writeToLog2($out, 'Запрос 2.3');
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
$Response=json_decode($out,true);
//echo '<b>Новая сделка:</b>'; echo '<pre>'; print_r($Response); echo '</pre>';
if(is_array($Response['response']['leads']['add']))
	foreach($Response['response']['leads']['add'] as $lead) {
		$lead_id = $lead["id"]; //id новой сделки
	};
//ДОБАВЛЯЕМ СДЕЛКУ - КОНЕЦ
//ДОБАВЛЕНИЕ КОНТАКТА
$contact = array(
	'name' => $contact_name,
	'linked_leads_id' => array($lead_id), //id сделки
	//'responsible_user_id' => $responsible_user_id, //id ответственного
	'custom_fields'=>array(
		array(
			'id' => $sFields['PHONE'],
			'values' => array(
				array(
					'value' => $contact_phone,
					'enum' => 'MOB'
				)
			)
		),
		array(
			'id' => $sFields['EMAIL'],
			'values' => array(
				array(
					'value' => $contact_email,
					'enum' => 'WORK'
				)
			)
		),
    array(
			'id' => 459317,
			'values' => array(
				array(
					'value' => $site
				)
			)
        ),
    array(
      'id' => 569371,
      'values' => array(
        array(
          'value' => $contact_connection
        )
      )
    )
	)
);
$set['request']['contacts']['add'][]=$contact;
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
//writeToLog2($out, 'Запрос 2.4');
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
//CheckCurlResponse($code);
$Response=json_decode($out,true);
//ДОБАВЛЕНИЕ КОНТАКТА - КОНЕЦ
//amo

	}
?>


