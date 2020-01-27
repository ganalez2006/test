<?php
// API access key from Google API's Console
define('API_ACCESS_KEY','AIzaSyCuMQvWQDrPlsx4g24Ym2RNo3jd5Ts2bkE');

$url = 'https://fcm.googleapis.com/fcm/send';

//$registrationIds = array($_GET['id']);

// prepare the message
$message = array( 
	'title'     => 'This is a title.',
	'body'      => '{"title":"bikegirlparis","options":{"body":"What do you think about my new equipment?","tag":"push-1580105438977","vibrate":[200,100,200],"renotify":true,"icon":"./images/icons/launcher-icon-1x.png","image":"https://scontent-mia3-1.cdninstagram.com/v/t51.2885-15/e35/81773741_156712045749336_1405689013483310911_n.jpg?_nc_ht=scontent-mia3-1.cdninstagram.com&_nc_cat=100&_nc_ohc=Xd0nAUOfRB0AX_mTvY-&oh=c9ee02d7f25a1b8f58d6aec9cb4160ef&oe=5ED16BC4","actions":[{"action":"reply","title":"Responder","type":"text","placeholder":"Escribe tu respuesta"},{"action":"action","title":"Me interesa","type":"button"}],"data":{"id":9999,"url":"https://google.com"}}}',
	'vibrate'   => 1,
	'sound'      => 1
);

$fields = array( 
	//'registration_ids' => $registrationIds, 
	'data'             => $message
);

$headers = array( 
	'Authorization: key='.API_ACCESS_KEY, 
	'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL,$url);
curl_setopt( $ch,CURLOPT_POST,true);
curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt( $ch,CURLOPT_POSTFIELDS,json_encode($fields));
$result = curl_exec($ch);
curl_close($ch);

echo $result;