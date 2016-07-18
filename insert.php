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
			$urlHost = "http".( ($_SERVER["HTTPS"] == "on")?"s":"" )."://".$_SERVER["HTTP_HOST"];
			$url = $urlHost.$_SERVER["PHP_SELF"];
			include("menu.php");
		?>
		<div class="row">
			<div class="col-md-6 col-md-push-3">
				<form action="<?php echo $dbPage ?>" method="get">
					<div class="form-group">
						<label for="question">Question</label>
						<input type="text" class="form-control" id="question" name="question" placeholder="Quelle question vous posez-vous ?">
					</div>
					<div class="form-group">
						<label for="author">Auteur</label>
						<input type="text" class="form-control" id="author" name="author" placeholder="Votre nom">
					</div>
					<div class="form-group">
						<label for="categoryId">Catégorie</label>
						<select id="categoryId" class="form-control" name="categoryId">
							<option value="1" selected>Default</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="mode" value="create">
						<input type="hidden" name="table" value="<?php echo $dbTable ?>">
						<input type="hidden" name="url" value="<?php echo dirname($url).'/index.php' ?>">
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				</form>
			</div>
		</div>
	</body>
</html>
