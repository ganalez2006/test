<?php #require_once('index.html') ?>

<?php
// tiempo maximo de ejecucion de php (s * m)
set_time_limit(60 * 3);

//$url = 'https://www.instagram.com/p/B3WRcIbg961/';
$url = $_POST['url'];

if (!isset($url) || empty($url)) {

	?>
	<style>
	#url {
		display: block;
		margin: 10px auto;
		width: 100%;
		padding: 10px;
		border-radius: 2px;
		border: 1px solid #cdcdcd;
	}
	</style>
	<form action="" method="POST">
		<label for="url">
			Instagram Image url: <br>
			<input type="text" name="url" id="url">
		</label>
	</form>
	<?php
	exit;
}

$url = 'https://opengraphcheck.com/result.php?url=' . urlencode($url);

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

// extraer las filas de la tabla
preg_match_all("|<tr>(.*?)</tr>|si", $content, $parts);

// fila de la imagen
$img = $parts[1][3];

// expression regular para la url
$pattern = '|(?<!")(?<!"\s)(https?:\/\/[^\s"\[<]+)|im';
preg_match_all($pattern, $img, $parts);

// imagen
$img = $parts[1][0];
echo '<img src="'. $img . '" alt="" style="display:block;margin:auto;">';

exit;