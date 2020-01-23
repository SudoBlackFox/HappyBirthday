<?
mb_internal_encoding("UTF-8");
//echo '228e6e7d';//обязательный параметр его не трогать
$confirmation_token = '228e6e7d'; //Ключ для Callback api
$token = ''; //ключ сообщества, для отправки сообщений от его имени
$usertoken = '';//ключ пользователя для отправки от его имени 


$id = ;//id группы

$date = date("d.m");//получение сегодняшней даты
$pieces = explode(".", $date);
if ($pieces[0] < 10) 
{
	$date1 = str_replace('0','',$pieces[0]);
	$date = $date1.'.'.$pieces[1];
}
$pieces = explode(".", $date);
if ($pieces[1] < 10) {
	$date2 = str_replace('0','',$pieces[1]);
	$date = $pieces[0].'.'.$date2;
}
//echo $date.' ';

$adminId = ;//id человека которому придёт информация об окончании и ошибках отправки
$tenday = date("d.m", strtotime("+20 days")); // "+20 days" число 20 можно менять на любое, то есть если сегодня 10 апреля, то это будет 20
$piecesD = explode(".", $tenday);
if ($piecesD[0] < 10) 
{
	$date1 = str_replace('0','',$piecesD[0]);
	$tenday = $date1.'.'.$piecesD[1];
}
$piecesD = explode(".", $tenday);
if ($piecesD[1] < 10) {
	$date2 = str_replace('0','',$piecesD[1]);
	$tenday = $piecesD[0].'.'.$date2;
}

//echo $tenday;
$a = 0; //счетчик юзеров с др


	$mentions1 = execute('var members = [];
        	    var i = 0;
        	    while (i < 5) {
        	    members = members + API.groups.getMembers({"group_id":'.$id.', "sort":"id_desc", "offset":i*1000, "count":1000, "fields":"bdate"}).items; 
        	    i = i + 1; 
       	    	}; 
       	    	 return members;', $usertoken);

	for($i; $i<count($mentions1['response']); $i++)
	{
		
		$pieces = explode(".", $mentions1['response'][$i]['bdate']);
		$mentions = $pieces[0].'.'.$pieces[1];
		echo $mentions1;
		if($mentions == $date)
			
		{
			$a = $a+1;
			$error = sendMessageEr($mentions1['response'][$i]['first_name'].', с днём рождения! Желаем неиссякаемого оптимизма, искренних улыбок и ярких впечатлений каждый день!🎊🎉🎈'."\n".'Всегда рады Вас видеть в нашем клубе! Для именинников есть выгодные предложения!😉', $mentions1['response'][$i]['id'], $token);
			if (array_key_exists('error', $error)) 
			{			
				$error = sendMessageEr($mentions1['response'][$i]['first_name'].', с днём рождения! Желаю неиссякаемого оптимизма, искренних улыбок и ярких впечатлений каждый день!🎊🎉🎈'."\n".'Всегда рады Вас видеть в нашем клубе! Для именинников есть выгодные предложения!😉', $mentions1['response'][$i]['id'], $usertoken);
				if (array_key_exists('error', $error)) 
				{
					$text = $text.' [id'.$mentions1['response'][$i]['id'].'|'.$mentions1['response'][$i]['first_name'].' '.$mentions1['response'][$i]['last_name'].'] '.'- '.$mentions1['response'][$i]['bdate'];	
				}
			}
		}
		elseif ($mentions == $tenday) 
		{
			$error = sendMessageEr($mentions1['response'][$i]['first_name'].' привет!'."\n".'У тебя скоро день рождения! Есть офигенное предложение, как насчет дня рождения в милатари стиле в нашем страйкбольном клубе!) Много оружия и адреналина.😎☺😄👌🏻'."\n".'Имениннику игра бесплатно в течении всего месяца (при наборе команды от 6 + человек) '."\n\n".'Планируйте праздник!!! '."\n".'Будем рады Вас видеть!😄', $mentions1['response'][$i]['id'], $token);
			if (array_key_exists('error', $error)) 
			{
				
				$error = sendMessageEr($mentions1['response'][$i]['first_name'].' привет!'."\n".'У тебя скоро день рождения! Есть офигенное предложение, как насчет дня рождения в милатари стиле в нашем страйкбольном клубе!) Много оружия и адреналина.😎☺😄👌🏻'."\n".'Имениннику игра бесплатно в течении всего месяца (при наборе команды от 6 + человек) '."\n\n".'Планируйте праздник!!! '."\n".'Будем рады Вас видеть!😄', $mentions1['response'][$i]['id'], $usertoken);
				if (array_key_exists('error', $error)) 
			{
				$text1 = $text1.' [id'.$mentions1['response'][$i]['id'].'|'.$mentions1['response'][$i]['first_name'].' '.$mentions1['response'][$i]['last_name'].'] '.'- '.$mentions1['response'][$i]['bdate'];
			}
			}
		}
	}

	if ($a==0) 
	{
		sendMessage('Днюх сегодня нет.', $adminId, $token);
	}
	else
	{
		sendMessage('Я не могу их поздравить. (л/с закрыты или забанены)'.$text, $adminId, $token);
		sendMessage('Я не могу их поздравить. (л/с закрыты или забанены) '.$text1, $adminId, $token);
		sendMessage('Всё, я закончил. ', $adminId, $token);
	}
echo 'ok';//обязательный параметр его не трогать




function user_get($user_ids, $fields,$token)
	{
		$resp = 'https://api.vk.com/method/users.get?user_ids='.$user_ids.'&fields='.$fields.'&v=5.67&access_token='.$token;
		$resp = file_get_contents($resp);
		$result = json_decode($resp,true);
		return $result;
		//https://vk.com/dev/users.get
	}

function execute($code, $token)
{
	$request_params = [
		'code' => $code,
		'access_token' => $token,
		'v' => '5.78'
	];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/execute?');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_result = json_decode(curl_exec($ch),true);
curl_close($ch);
return $curl_result;
}

function sendMessage($text, $id, $token)//отправка сообщения, опять же для удобств
{
	$request_params = [
		'message' => $text,
		'user_id' => $id,
		'access_token' => $token,
		'v' => '5.0'
	];
	$resp = file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($request_params));

}
function sendMessageEr($text, $id, $token)//отправка сообщения, опять же для удобств
{
	$request_params = [
		'message' => $text,
		'user_id' => $id,
		'access_token' => $token,
		'v' => '5.0'
	];
	$resp = file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($request_params));
	$result = json_decode($resp,true);
	return $result;

}
function isStart($str, $substr)//Проверка начинается ли строка с указаной строчки, сделал тупо для удобств
{
    $result = strpos($str, $substr);
    if ($result === 0) {
      return true;
    } else {
      return false; 
    }
}
?>
