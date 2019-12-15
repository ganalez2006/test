<?php #require_once('index.html') ?>

<?php
// tiempo maximo de ejecucion de php (s * m)
set_time_limit(60 * 3);

$url = 'https://www.instagram.com/p/B3WRcIbg961/';
$url = 'https://opengraphcheck.com/result.php?url=' . urlencode($url);
var_dump($url);

// Peticion curl
$ch = curl_init($url);
// solucionar "SSL certificate problem: unable to get local issuer certificate"
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// No imprimir el contenido en pantalla
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

var_dump($content);
var_dump($error);

$doc = new DOMDocument();
@$doc->loadHTML($content);
$metas = $doc->getElementsByTagName('table');

for ($i = 0; $i < $metas->length; $i++) {

	$meta = $metas->item($i);
	var_dump($meta);
	var_dump($meta->getAttribute('name'));
}

exit;