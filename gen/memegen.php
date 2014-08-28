<?php
	/**
	 * MemeGen.php
	 */
	
	define('FONT', '../font/Nexa Light.ttf');
	
	function meme_output($array = array()) {
		die('<!--' . ((gettype($array) == 'array' && !empty($array)) ? json_encode($array) : '') . '-->');
	}
	
	function memegen_text($base_image, $text) {
		// Valid arguments
		if(!empty($base_image) && !empty($text) && gettype($text) === 'string' && get_resource_type($base_image) === 'gd') {
			$frame_dimension = array(
				'width' => 800,
				'height' => 561
			);
			
			$content_rectangle = array(
				'x' => 40,
				'y' => 52 //,
				//'width' => XXX,
				//'height' => XXX
			);
			
			// Write the text
			$text_color = imagecolorallocate($base_image, 0, 0, 0);
			
			if($text_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 22,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'text',
					'preview' => false
				));
			}
			imagettftext($base_image, 45.36, 0, 36, 76, $text_color, FONT, $text);
			
			// Output the image as Base64
			ob_start();
			imagepng($base_image);
			imagedestroy($base_image);
			$output_image = ob_get_clean();
			
			$base64_image = base64_encode($output_image);
			meme_output(array(
				'status' => 'ok',
				'error' => false,
				'errorCode' => false,
				'errorName' => false,
				'format' => 'text',
				'preview' => $base64_image,
			));
		}
	}
	
	function memegen_horizontal($upload_image, $frame_image, $text) {
		// Valid arguments
		if(!empty($upload_image) && !empty($frame_image) && !empty($text) && gettype($text) === 'string' && get_resource_type($upload_image) === 'gd' && get_resource_type($frame_image) === 'gd') {
			$frame_dimension = array(
				'width' => 800,
				'height' => 561
			);
			
			$content_rectangle = array(
				'x' => 0,
				'y' => 116,
				'width' => 800,
				'height' => 447
			);
			
			$upload_dimension = array(
				'width' => imagesx($upload_image),
				'height' => imagesy($upload_image)
			);
			
			if($upload_dimension['height'] * ($content_rectangle['width'] / $upload_dimension['width']) < $content_rectangle['height']) {
				$new_dimension = array(
					'width' => $upload_dimension['width'] * ($content_rectangle['height'] / $upload_dimension['height']),
					'height' => $content_rectangle['height']
				);
			} else {
				$new_dimension = array(
					'width' => $content_rectangle['width'],
					'height' => $upload_dimension['height'] * ($content_rectangle['width'] / $upload_dimension['width'])
				);
			}
			
			$offset = array(
				'width' => $new_dimension['width'] - $content_rectangle['width'],
				'height' => $new_dimension['height'] - $content_rectangle['height']
			);
			
			// Create blank image with new dimensions
			$resized_image = @imagecreatetruecolor($new_dimension['width'], $new_dimension['height']) or meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 14,
				'errorName' => 'Cannot instantiate blank image',
				'format' => 'horizontal',
				'preview' => false
			));
			imagealphablending($resized_image, true);
			imagesavealpha($resized_image, true);
			$transparent_color = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
			
			if($transparent_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 15,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'horizontal',
					'preview' => false
				));
			}
			imagefill($resized_image, 0, 0, $transparent_color);
			
			// Resize the uploaded image
			imagecopyresized($resized_image, $upload_image, 0, 0, 0, 0, $new_dimension['width'], $new_dimension['height'], $upload_dimension['width'], $upload_dimension['height']);
			
			$output_image = @imagecreatetruecolor($frame_dimension['width'], $frame_dimension['height']) or meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 16,
				'errorName' => 'Cannot instantiate blank image',
				'format' => 'horizontal',
				'preview' => false
			));
			imagealphablending($output_image, true);
			imagesavealpha($output_image, true);
			$transparent_color = imagecolorallocatealpha($output_image, 0, 0, 0, 127);
			
			if($transparent_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 17,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'horizontal',
					'preview' => false
				));
			}
			imagefill($output_image, 0, 0, $transparent_color);
			
			// Copy all the layers to produce the final output
			imagecopy($output_image, $resized_image, $content_rectangle['x'], $content_rectangle['y'], $offset['width'] / 2, $offset['height'] / 2, $content_rectangle['width'], $content_rectangle['height']);
			imagecopy($output_image, $frame_image, 0, 0, 0, 0, $frame_dimension['width'], $frame_dimension['height']);
			
			// Write the text
			$text_color = imagecolorallocate($output_image, 0, 0, 0);
			
			if($text_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 23,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'horizontal',
					'preview' => false
				));
			}
			imagettftext($output_image, 27.01, 0, 40, 52, $text_color, FONT, $text);
			
			// Output the image as Base64
			ob_start();
			imagepng($output_image);
			imagedestroy($output_image);
			imagedestroy($frame_image);
			imagedestroy($upload_image);
			imagedestroy($resized_image);
			$output_image = ob_get_clean();
			
			$base64_image = base64_encode($output_image);
			meme_output(array(
				'status' => 'ok',
				'error' => false,
				'errorCode' => false,
				'errorName' => false,
				'format' => 'horizontal',
				'preview' => $base64_image,
			));
		} else {
			meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 11,
				'errorName' => 'Invalid argument resource type',
				'format' => 'horizontal',
				'preview' => false
			));
		}
	}
	
	function memegen_vertical($upload_image, $frame_image, $text) {
		// Valid arguments
		if(!empty($upload_image) && !empty($frame_image) && !empty($text) && gettype($text) === 'string' && get_resource_type($upload_image) === 'gd' && get_resource_type($frame_image) === 'gd') {
			$frame_dimension = array(
				'width' => 800,
				'height' => 561
			);
			
			$content_rectangle = array(
				'x' => 40,
				'y' => 0,
				'width' => 376,
				'height' => 561
			);
			
			$upload_dimension = array(
				'width' => imagesx($upload_image),
				'height' => imagesy($upload_image)
			);
			
			if($upload_dimension['height'] * ($content_rectangle['width'] / $upload_dimension['width']) < $content_rectangle['height']) {
				$new_dimension = array(
					'width' => $upload_dimension['width'] * ($content_rectangle['height'] / $upload_dimension['height']),
					'height' => $content_rectangle['height']
				);
			} else {
				$new_dimension = array(
					'width' => $content_rectangle['width'],
					'height' => $upload_dimension['height'] * ($content_rectangle['width'] / $upload_dimension['width'])
				);
			}
			
			$offset = array(
				'width' => $new_dimension['width'] - $content_rectangle['width'],
				'height' => $new_dimension['height'] - $content_rectangle['height']
			);
			
			// Create blank image with new dimensions
			$resized_image = @imagecreatetruecolor($new_dimension['width'], $new_dimension['height']) or meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 14,
				'errorName' => 'Cannot instantiate blank image',
				'format' => 'vertical',
				'preview' => false
			));
			imagealphablending($resized_image, true);
			imagesavealpha($resized_image, true);
			$transparent_color = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
			
			if($transparent_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 15,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'vertical',
					'preview' => false
				));
			}
			imagefill($resized_image, 0, 0, $transparent_color);
			
			// Resize the uploaded image
			imagecopyresized($resized_image, $upload_image, 0, 0, 0, 0, $new_dimension['width'], $new_dimension['height'], $upload_dimension['width'], $upload_dimension['height']);
			
			$output_image = @imagecreatetruecolor($frame_dimension['width'], $frame_dimension['height']) or meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 16,
				'errorName' => 'Cannot instantiate blank image',
				'format' => 'vertical',
				'preview' => false
			));
			imagealphablending($output_image, true);
			imagesavealpha($output_image, true);
			$transparent_color = imagecolorallocatealpha($output_image, 0, 0, 0, 127);
			
			if($transparent_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 18,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'vertical',
					'preview' => false
				));
			}
			imagefill($output_image, 0, 0, $transparent_color);
			
			// Copy all the layers to produce the final output
			imagecopy($output_image, $resized_image, $content_rectangle['x'], $content_rectangle['y'], $offset['width'] / 2, $offset['height'] / 2, $content_rectangle['width'], $content_rectangle['height']);
			imagecopy($output_image, $frame_image, 0, 0, 0, 0, $frame_dimension['width'], $frame_dimension['height']);
			
			// Write the text
			$text_color = imagecolorallocate($output_image, 0, 0, 0);
			
			if($text_color === FALSE) {
				meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 21,
					'errorName' => 'Cannot instantiate new color',
					'format' => 'vertical',
					'preview' => false
				));
			}
			imagettftext($output_image, 27.01, 0, 435, 67, $text_color, FONT, $text);
			
			// Output the image as Base64
			ob_start();
			imagepng($output_image);
			imagedestroy($output_image);
			imagedestroy($frame_image);
			imagedestroy($upload_image);
			imagedestroy($resized_image);
			$output_image = ob_get_clean();
			
			$base64_image = base64_encode($output_image);
			meme_output(array(
				'status' => 'ok',
				'error' => false,
				'errorCode' => false,
				'errorName' => false,
				'format' => 'vertical',
				'preview' => $base64_image,
			));
		} else {
			meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 12,
				'errorName' => 'Invalid argument resource type',
				'format' => 'vertical',
				'preview' => false
			));
		}
	}
	
	if(!empty($_POST)) {
		if(!empty($_POST['textstr'])) {
			// Upload Successful
			if(isset($_FILES['imgup'], $_FILES['imgup']['name']) && !$_FILES['imgup']['error']) {
				// Get upload info
				$filename = $_FILES['imgup']['name'];
				$filetmpname = $_FILES['imgup']['tmp_name'];
				$extension = pathinfo($filename, PATHINFO_EXTENSION);
				
				// Try to open the uploaded image
				if($extension === 'png') {
					// Portable Network Graphics
					$upload_image = @imagecreatefrompng($filetmpname) or meme_output(array(
						'status' => 'error',
						'error' => true,
						'errorCode' => 3,
						'errorName' => 'Cannot open png image',
						'extension' => $extension,
						'format' => false,
						'preview' => false
					));
				} else if($extension === 'jpg' || $extension === 'jpg') {
					// Joint Photographic Experts Group
					$upload_image = @imagecreatefromjpeg($filetmpname) or meme_output(array(
						'status' => 'error',
						'error' => true,
						'errorCode' => 4,
						'errorName' => 'Cannot open jpeg image',
						'extension' => $extension,
						'format' => false,
						'preview' => false
					));
				} else {
					if(!empty($extension)) {
						meme_output(array(
							'status' => 'error',
							'error' => true,
							'errorCode' => 5,
							'errorName' => 'Unsupported file extension',
							'extension' => $extension,
							'format' => false,
							'preview' => false
						));
					} else {
						meme_output(array(
							'status' => 'error',
							'error' => true,
							'errorCode' => 6,
							'errorName' => 'File without extension',
							'extension' => false,
							'format' => false,
							'preview' => false
						));
					}
				}
				
				// Open the frame image
				if(isset($_POST['memeformat'])) {
					// Choose meme format
					if($_POST['memeformat'] === 'horizontal') {
						// Open horizontal Meme
						$frame_image = @imagecreatefrompng('../images/horizontal.png') or meme_output(array(
							'status' => 'error',
							'error' => true,
							'errorCode' => 9,
							'errorName' => 'Cannot open horizontal frame image',
							'format' => 'horizontal',
							'preview' => false
						));
						
						// Generate the horizontal Meme version
						memegen_horizontal($upload_image, $frame_image, $_POST['textstr']);
					} else if($_POST['memeformat'] === 'vertical') {
						// Open vertical Meme
						$frame_image = @imagecreatefrompng('../images/vertical.png') or meme_output(array(
							'status' => 'error',
							'error' => true,
							'errorCode' => 10,
							'errorName' => 'Cannot open vertical frame image',
							'format' => 'vertical',
							'preview' => false
						));
						// Generate the vertical Meme version
						memegen_vertical($upload_image, $frame_image, $_POST['textstr']);
					} else {
						meme_output(array(
							'status' => 'error',
							'error' => true,
							'errorCode' => 8,
							'errorName' => 'Unknown meme format',
							'format' => false,
							'preview' => false
						));
					}
				} else {
					meme_output(array(
						'status' => 'error',
						'error' => true,
						'errorCode' => 7,
						'errorName' => 'Undefined meme format',
						'format' => false,
						'preview' => false
					));
				}
			} else {
				// Open base Meme
				$frame_image = @imagecreatefromjpeg('../images/text.jpeg') or meme_output(array(
					'status' => 'error',
					'error' => true,
					'errorCode' => 20,
					'errorName' => 'Cannot open text base image',
					'format' => 'text',
					'preview' => false
				));
				// Generate text meme
				memegen_text($frame_image, $_POST['textstr']);
			}
		} else {
			meme_output(array(
				'status' => 'error',
				'error' => true,
				'errorCode' => 19,
				'errorName' => 'No text given',
				'extension' => false,
				'format' => false,
				'preview' => false
			));
		}
	} else if(!empty($_GET)) {
		meme_output(array(
			'status' => 'error',
			'error' => true,
			'errorCode' => 13,
			'errorName' => 'Wrong data submission method',
			'extension' => false,
			'format' => false,
			'preview' => false
		));
	} else {
		meme_output(array(
			'status' => 'error',
			'error' => true,
			'errorCode' => 1,
			'errorName' => 'No data submitted',
			'extension' => false,
			'format' => false,
			'preview' => false
		));
	}
?>
