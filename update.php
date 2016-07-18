<!DOCTYPE html>
<html>
	<head>
		<title>Pose-toi une question</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
		<link rel="stylesheet" href="css/bootstrap.min.css"/>
	</head>
	<body class="container-fluid">
		<?php
			$dbPage = "db.php";
			$dbTable = "t_questions";
			$dbCallMode = "selectAll";
			$urlHost = "http".( ($_SERVER["HTTPS"] == "on")?"s":"" )."://".$_SERVER["HTTP_HOST"];
			$url = $urlHost.$_SERVER["PHP_SELF"];
			include("menu.php");
		?>
		<button type="button" class="btn btn-default button-filter" onclick="filterByAuthor()">Filtrer par auteur</button>
		<button type="button" class="btn btn-default button-filter" onclick="filterByHide(this)">Masquer les entrées cachées</button>
		<table class="table">
			<?php
				$dbUrl = dirname($url)."/".$dbPage."?mode=".$dbCallMode."&table=".$dbTable;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $dbUrl);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$output = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($output);
				foreach ($result as $key => $value) {
					echo "<tr hide='", $value->hide, "'";
					if($value->hide == 1)
						echo "style='background-color:#eee;'";
					echo ">";
					echo "<td>", $value->author, "</td>";
					echo "<td id='", $value->id, "'>", $value->question, "</td>";
					echo "<td><button type='button' class='btn btn-default' onclick='openForm(\"", $value->id, "\")'>Éditer</button></td>";
					echo "</tr>";
				}
			?>
		</table>
		<div class="row">
			<div class="col-md-6 col-md-push-3">
				<form action="db.php" method="get" style="display: none">
					<div class="form-group">
						<label for="question">Question</label>
						<input id="formfield-question" name="question" type="text" class="form-control"></input>
					</div>
					<div class="checkbox">
						<label>
							<input id="formbox-hide" type="checkbox" onclick="checkIsHidden()"/>
							Entrée cachée
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" onclick="checkToDelete(this)"/>
							Supprimer l’entrée
						</label>
					</div>
					<input id="formfield-mode" type="hidden" name="mode" value="update"></input>
					<input type="hidden" name="table" value="<?php echo $dbTable ?>"></input>
					<input id="formfield-id" type="hidden" name="id"></input>
					<input id="formfield-hide" type="hidden" name="hide"></input>
					<input type="hidden" name="url" value="<?php echo $url ?>"></input>
					<button type="button" class="btn btn-default" onclick="submitForm()">Valider</button>
					<button type="button" class="btn btn-default" onclick="location.reload()">Annuler</button>
				</form>
			</div>
		</div>
	</body>
	<!-- loading page-specific JavaScript functions -->
	<script src="js/update.js"></script>
</html>
