<!DOCTYPE html>
<html>
	<head>
		<title>
			#HGARE:MemeGen
		</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="css/main.css" />
		<script>
			jQuery = jQuery.noConflict();
		</script>
		<script src="js/main.js"></script>
	</head>
	<body>
		<img id="preview" />
		</div>
		<h1>#HGARE:MemeGen</h1>
		<!-- Fa schifo, da cambiare -->
		<h2>Manda #HGARE (a) qualcuno (o qualcosa)</h2>
		<strong>Compila il form per generare il tuo Meme personalizzato</strong>
		<form id="memeGenForm" action="javascript:void(0);" method="post" enctype="multipart/form-data">
			<table cellpadding="0" cellspacing="0" border="0">
				<tbody>
					<tr>
						<td>
							<label for="textstr">Scrivi un testo</label> *:&nbsp;
						</td>
						<td>
							<textarea name="textstr" cols="29" rows="4" maxlength="248" style="margin:4px;"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label for="imgup">Aggiungi un immagine</label> (opz.):&nbsp;
						</td>
						<td>
							<input name="imgup" type="file" accept="image/png, image/jpeg" /><br />
							<progress value="0" max="100"></progress><br /><br />
						</td>
					</tr>
					<tr>
						<td>
							<label for="imgup">Seleziona l'orientamento del meme</label> *:&nbsp;
						</td>
						<td>
							<input type="radio" name="memeformat" value="horizontal" checked="checked" />Orizzontale
							<input type="radio" name="memeformat" value="vertical" /> Verticale
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<input type="submit" name="preview" value="Anteprima" disabled="disabled" />
							<input type="submit" name="send" value="Invia" disabled="disabled" />
						</td>
					</tr>
				</tfoot>
			</table>
		</form>
	</body>
</html>
