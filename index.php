<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>Download Images/Videos Instagram</title>

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

	<form action="" method="POST">
		<label for="dwAll">
			<input type="checkbox" name="dwAll" id="dwAll" value="true">
			Descargar todo.
		</label>
		<br><br>
		<label for="url">
			Instagram Post url: <br>
			<input type="text" name="url" id="url">
		</label>
	</form>

	<?php

	// tiempo maximo de ejecucion de php (s * m)
	set_time_limit(60 * 3);

	$url = isset($_POST['url']) ? $_POST['url'] : '';

	global $dwAll;
	$dwAll = isset($_POST['dwAll']) ? true : false;

	// carpeta de descarga
	if ($dwAll) {
		
		global $path;
		$path = 'download-'.time();
		if (!file_exists($path))
			mkdir($path, 0755, true);

		$filezip = time().'.zip';

		global $zip;
		$zip = new ZipArchive;
		$zip->open($filezip, ZipArchive::CREATE);
	}

	if (isset($url) && !empty($url)) {

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if ($content == '') {

			echo 'Error: Url invalida.';
			return;
		}

		function get_elemnt($element) {

			global $dwAll;

			$time = time();

			$text = isset($element->accessibility_caption) ? $element->accessibility_caption : time();

			if ($element->is_video) {
				echo '<li><a href="'.$element->video_url.'" target="_blank">Video</a></li>';
				$text = 'Video';

				if ($dwAll) {

					global $path;
					$file = md5($element->video_url).'.mp4';
					$fch = fopen($path.'/'.$file, "w");
					fwrite($fch, file_get_contents($element->video_url));
					fclose($fch);

					global $zip;
					$zip->addFile($path.'/'.$file);
				}
			}

			foreach ($element->display_resources as $key => $value) {

				echo '<li><a href="'.$value->src.'" target="_blank">Media Preview | ' . $text . ' | '.$value->config_width.'</a></li>';

				if ($dwAll) {

					global $path;
					$file = md5($value->src).'.jpg';
					$fch = fopen($path.'/'.$file, "w");
					fwrite($fch, file_get_contents($value->src));
					fclose($fch);

					global $zip;
					$zip->addFile($path.'/'.$file);
				}
			}
		}

		preg_match_all('~<script[^>]*>\K[^<]*(?=</script>)~i', $content, $scripts);

		foreach ($scripts[0] as $key => $value) {
			if (strpos($value, 'window._sharedData = ') === 0) {
				$scripts = $value;
				break;
			}
		}

		$json = str_replace('window._sharedData = ', '', $scripts);
		$json = trim($json, ';');
		$json = json_decode($json);
		$json = $json->entry_data->PostPage[0]->graphql->shortcode_media;

		echo '<ul>';

		// post
		echo '<li><a href="'.$url.'" target="_blank">Post url</a></li>';
		
		// username
		echo '<li>@'.$json->owner->username.'</li>';

		// full_name
		echo '<li>Name: '.$json->owner->full_name.'</li>';

		// profile_pic_url
		echo '<li><a href="'.$json->owner->profile_pic_url.'" target="_blank">Profile pic</a></li>';

		// description
		$description = isset($json->edge_media_to_caption->edges[0]->node->text)
						? $json->edge_media_to_caption->edges[0]->node->text
						: false;
		if ($description) 
			echo '<li>'.$description.'</li>';

		echo '</ul>';

		echo '<ol>';

		if (isset($json->edge_sidecar_to_children->edges)) {

			$json = $json->edge_sidecar_to_children->edges;
			foreach ($json as $key => $value) {
				
				$element = $value->node;
				get_elemnt($element);
			}
		} else {

			get_elemnt($json);
		}

		echo '</ol>';

		if ($dwAll) {

			// close the zip file
			if (!$zip->close()) {
				echo '<p>Error al crear archivo ZIP.</p>';
			} else {
				echo '<p>Successfully created the ZIP Archive!</p>';

				// forzar descarga
				header("Content-type: application/octet-stream");
				header("Content-disposition: attachment; filename=".$filezip);
				readfile($filezip);
				unlink($filezip);

				function rmDir_rf($folder) {
					if (file_exists($folder)) {
						foreach(glob($folder . "/*") as $file){             
							if (is_dir($file)){
								rmDir_rf($file);
							} else {
								unlink($file);
							}
						}
						rmdir($folder);
					}
				}
				rmDir_rf($path);
				/**/
			}
		}		
	}
	?>

</body>
</html>