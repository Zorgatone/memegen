/*jslint white: true */
/*jshint strict: true*/
/*jslint vars: true*/
/*global clearInterval: false, clearTimeout: false, document: false, event: false, frames: false, history: false, Image: false, location: false, name: false, navigator: false, Option: false, parent: false, screen: false, setInterval: false, setTimeout: false, window: false, XMLHttpRequest: false, FormData: false*/
/*global alert: false, confirm: false, console: false, Debug: false, opera: false, prompt: false, WSH: false*/
/*global jQuery: false*/

(function($) {
	'use strict';
	
	var completeHandler = function completeHandler(data) {
		var pattern = /<!--(((?!-->).)*)-->/; // Match the content of the HTML comment
		var matches = data.match(pattern);

		if(matches != null && matches.length > 1) {
			var decoded = matches[1];
			var response = JSON.parse(decoded);
			console.dir(response);
			if(response.debug) {
				console.log(response.debugInfo);
			}
			
			if(!response.error && response.preview) {
				$('#preview').attr('src', "data:image/png;base64," + response.preview).load(function() {
					if(!$(this).is(':visible')) {
						$(this).fadeIn();
					}
				});
				//$('#memeGenForm input[type=submit]:first-child').attr('disabled', 'disabled');
			}
		} else {
			console.log('No response from server.');
			if(data.length > 0)console.log(data); //@TODO: Remove Debug console logging
		}
	};
	
	var progressHandlingFunction = function progressHandlingFunction(e) {
		if (e.lengthComputable) {
			$('progress').attr({
				value: e.loaded,
				max: e.total
			});
		}
	};
	
	var submit = function submit() {
		var formData = new FormData($('form')[0]);
		$.ajax({
			url: 'gen/memegen.php',
			type: 'POST',
			xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
				}
				return myXhr;
			},
			success: completeHandler,
			data: formData,
			cache: false,
			contentType: false,
			processData: false
		});
	};
	
	var memeSelect = function memeSelect() {
		if($(this).val().length > 0 && !$('#memeGenForm tbody tr:last-child').is(':visible')) {
			$('#memeGenForm tbody tr:last-child').fadeIn();
		} else if($(this).val().length <= 0 && $('#memeGenForm tbody tr:last-child').is(':visible')) {
			$('#memeGenForm tbody tr:last-child').fadeOut();
		}
		buttonActivate();
	};
	
	var buttonActivate = function buttonActivate() {
		if($('#memeGenForm textarea').val().length > 0) {
			//$('#memeGenForm input[type=submit]:first-child').removeAttr('disabled');
		} else {
			//$('#memeGenForm input[type=submit]:first-child').attr('disabled', 'disabled');
		}
	};

	$(document).ready(function() {
		$('#memeGenForm').submit(submit);
		$('#memeGenForm input[type=file]').change(memeSelect);
		$('#memeGenForm textarea').keyup(buttonActivate);
		$('input[type=radio]').change(buttonActivate);
	});
}(jQuery));