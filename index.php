<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Download Images Instagram</title>

	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<style>
	html,
	body {
		margin: 0;
		padding: 10px;
		color: #000;
		font-family: helvetica, verdana, arial;
		font-size: 100%;
		line-height: 1.4;
		height: auto;
	}
	#url {
		display: block;
		margin: 10px auto;
		width: calc(100% - 20px);
		max-width: 100%;
		padding: 10px;
		border-radius: 2px;
		border: 1px solid #cdcdcd;
	}
	img {
		display: block;
		margin: 20px auto;
		max-width: 100%;
	}
	</style>
</head>
<body>

	<?php
	// tiempo maximo de ejecucion de php (s * m)
	set_time_limit(60 * 3);

	//$url = 'https://www.instagram.com/p/B3WRcIbg961/';
	$url = $_POST['url'];

	if (isset($url) && !empty($url)) {

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
		?>

		<img src="<?= $img ?>" alt="">

		<?php
	}
	?>

	<form action="" method="POST">
		<label for="url">
			Instagram Image url: <br>
			<input type="text" name="url" id="url">
		</label>
	</form>

</body>
</html>

<?php #require_once('index.html') ?>