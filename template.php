<?php
	$response = [];
	
	$im = imagecreatefrompng("./resources/img/template.png");
	
	$w = imagesx($im); // image width
	$h = imagesy($im); // image height
	
	$count = 0;
	for($i=0;$i<$w;$i++)
		for($j=0;$j<$h;$j++) {
			$rgb = imagecolorat($im, $i, $j);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			
			if($r!=255 || $g!=255 || $b!=255)
				$count++;

		}

	$k=0;
	for($i=0;$i<$w;$i++)
		for($j=0;$j<$h;$j++) {
			$rgb = imagecolorat($im, $i, $j);
			$r = ($rgb >> 16) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			
			if($r!=255 || $g!=255 || $b!=255) {
				$point['x'] = $i-$w/2;
				$point['y'] = $j-$h/2;
				$point['chk'] = 0;
				$response[]=$point;
				$k=0;
			}
		}
				
    echo json_encode($response);
	
	
	