<?php
	/**
	 * MemeSave.php
	 */
	
	function meme_output($array = array()) {
		die('<!--' . ((gettype($array) == 'array' && !empty($array)) ? json_encode($array) : '') . '-->');
	}
?>