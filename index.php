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
			$dbCallMode = "select";
			$urlHost = "http".( ($_SERVER["HTTPS"] == "on")?"s":"" )."://".$_SERVER["HTTP_HOST"];
			$url = $urlHost.$_SERVER["PHP_SELF"];
			include("menu.php");
		?>
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
					echo "<tr>";
					echo "<td>", $value->author, "</td>";
					echo "<td>", $value->question, "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</body>
</html>
