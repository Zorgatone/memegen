<?php
	$top_image = imagecreatefrompng('images/vertical.png');
	imagealphablending($top_image, true);
	imagesavealpha($top_image, true);
	
	if(isset($_FILES['imgup']) && isset($_FILES['imgup']['name']) && !$_FILES['imgup']['error']) {
		$filename = $_FILES['imgup']['name'];
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		
		if($extension === 'png') {
			$bottom_image = imagecreatefrompng($_FILES['imgup']['tmp_name']);
		} else if($extension === 'jpeg' || $extension === 'jpg') {
			$bottom_image = imagecreatefromjpeg($_FILES['imgup']['tmp_name']);
		}
		
		$maxx = 800;
		$maxy = 561;
		
		$startx = 40;
		$endx = 416;
		$starty = 0;
		$endy = 561;
		
		$width = $endx - $startx;
		$height = $endy - $starty;
		
		$x = imagesx($bottom_image);
		$y = imagesy($bottom_image);
		
		if($y * ($width / $x) < $height) {
			$newy = $height;
			$newx = $x * ($newy / $y);
		} else {
			$newx = $width;
			$newy = $y * ($newx / $x);
		}
		
		$xdiff = $width - $newx;
		$ydiff = $height - $newy;
		
		$resized = imagecreatetruecolor($newx, $newy);
		imagealphablending($resized, true);
		imagesavealpha($resized, true);
		$transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
		imagefill($resized, 0, 0, $transparent);
		
		imagecopyresized($resized, $bottom_image, 0, 0, 0, 0, $newx, $newy, $x, $y);
		
		$output_image = imagecreatetruecolor($maxx, $maxy);
		imagealphablending($output_image, true);
		imagesavealpha($output_image, true);
		$transparent = imagecolorallocatealpha($output_image, 0, 0, 0, 127);
		imagefill($output_image, 0, 0, $transparent);
		
		imagecopy($output_image, $resized, $startx, $starty, -$xdiff / 2, -$ydiff / 2, $newx, $newy);
		imagecopy($output_image, $top_image, 0, 0, 0, 0, $maxx, $maxy);
		
		ob_start();
		imagepng($output_image);
		imagedestroy($top_image);
		imagedestroy($bottom_image);
		imagedestroy($output_image);
		$output_image = ob_get_clean();
		
		$img = base64_encode($output_image);
		
		$res = array('preview' => $img);
		echo json_encode($res);
		
		exit;
	}
	
	ob_start();
	imagepng($top_image);
	imagedestroy($top_image);
	$top_image = ob_get_clean();
	
	$img = base64_encode($top_image);
	
	$res = array('preview' => $img);
	echo json_encode($res);
 ?>
 