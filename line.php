<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'bKSKzYt7MlwbGayuUXLZ9fuDzfNp5gGOuhOpil9V696ZSB2a2X5TzA1IwOXZBONDPzEbnv6rIZZAF34dqcwmbsThpaRhFAgt6wx7A8I8TqTUy+fP0p3/gIrY+nNGFj9H0wFTBLrjsMlk9y25kGWV1gdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "อยากทราบยอด COVID-19 ครับ")||($text == "อยากทราบยอด COVID-19")||($text == "COVID-19")){
			$m1 = "รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 (COVID-19) ในประเทศไทย \n";
			$m2 = "ผู้ป่วยสะสม	จำนวน 398,995 ราย \n";
			$m3 = "ผู้เสียชีวิต	จำนวน 17,365 ราย \n";
			$m4 = "รักษาหาย	จำนวน 103,753 ราย \n";
			$m5 = "ผู้รายงานข้อมูล: นายกานต์ เจริญจิตร ";
			$reply_message = $m1.$m2.$m3.$m4.$m5;
		}
		else if( $text == "ข้อมูลส่วนตัวของผู้พัฒนาระบบ" || $text == "ข้อมูลส่วนตัวของผู้พัฒนา" ){
			$reply_message = 'ชื่อนายปานเดชา เมืองโคตร อายุ 20 ปี น้ำหนัก 46kg. สูง 170cm. ขนาดรองเท้าเบอร์ 4 ใช้หน่วย US';
		}
	   	else if( $text == "ส่งงานครบยัง"){
			$reply_message = 'ส่งครบแล้ว';
		}
	   	else if( $text == "ทำข้อสอบข้อเขียนเสร็จหมดยัง"){
			$reply_message = "ทำเสร็จหมดแล้ว";
		}
		else
		{
			$reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
