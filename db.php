<?php
	/*DB Management.
	Expecting several parameters given via GET request

	mandatory parameters
	@mode				: REQUIRED	: SQL method						: create/update/select/selectAll/delete
	@table			: REQUIRED	: table name						: t_questions/t_answers/t_categories
	@url				: REQUIRED	: URL path							: the return URL once the request has been succesfully executed

	all tables
	@id					: OPTIONAL	: SELECT/UPDATE/DELETE	: ID of the record to select

	table = t_questions
	@author			: OPTIONAL	: CREATE/UPDATE					: Author to set
	@categoryId	: OPTIONAL	: CREATE/UPDATE					: Linked category to set (default is 1)
	@question 	: OPTIONAL	: CREATE/UPDATE					: Question to set (default is 1)

	table = t_categories
	@name				: OPTIONAL	: CREATE/UPDATE					: Name of the category

	table = t_answers
	@link				: OPTIONAL	: CREATE/UPDATE					: URL of the suggested answer
	@questionId	: OPTIONAL	: CREATE/UPDATE					: ID of the linked question
	*/

	$dbServer = "localhost";
	$dbName = "PTUQ";
	$dbUser = "trainee";
	$dbPwd = "face";

	try {
		$link = new PDO('mysql:host='.$dbServer.';dbname='.$dbName,$dbUser,$dbPwd);

		// Parsing GET parameters
		$parts = parse_url($_SERVER[REQUEST_URI]);
		parse_str($parts['query'], $query);

		// remove some elements from query, store them in dedicated variables
		if (isset($query['mode'])) {
			$mode = $query['mode'];
			unset($query['mode']);
		}

		$table = $query['table'];
		unset($query['table']);

		$id = $query['id'];
		unset($query['id']);

		$url = $query['url'];
		unset($query['url']);

		// create an array from remaining query elements
		$aKeys = join(",", array_keys($query));

		$sValues = "";
		$upd = "";
		foreach ($query as $key => $value) {
			// generate instructions for "create" call
			if (strlen($sValues) > 0)
				$sValues .= ",";
			if (is_numeric($value))
				$sValues .= intval($value);
			else
				$sValues .= "'".htmlentities($value, ENT_QUOTES, 'UTF-8')."'";
			// generate instructions for "update" call
			if (strlen($upd) > 0)
				$upd .= ",";
			$upd .= $key."='".$value."'";
		}

		$redirect = false;
		switch ($mode) {
			// create new entry (called by insert.php)
		 	case 'create':
				$dbQuery = "INSERT INTO $table ($aKeys) VALUES ($sValues);";
				$redirect = true;
				break;
			// update existing fields (called by update.php)
			case 'update':
				$dbQuery = "UPDATE $table SET $upd WHERE id = $id";
				$redirect = true;
				break;
			// get only entries with "hide" field set to 0
			case 'select':
				$dbQuery = "SELECT * from $table WHERE hide = 0 ORDER BY id DESC;";
				break;
			// get all entries
			case 'selectAll':
				$dbQuery = "SELECT * from $table ORDER BY id DESC;";
				break;
			// delete given entry (called by update.php)
			case 'delete':
				$dbQuery = "DELETE FROM $table WHERE id = $id;";
				$redirect = true;
				break;
			default:
				var_dump($parts);
				break;
		}

		$handle = $link -> prepare($dbQuery);
		$handle -> execute();

		if (!$redirect) {
			$result = $handle -> fetchAll(\PDO::FETCH_OBJ);
			echo json_encode($result);
		}

		$handle = null;

		if ($redirect)
			header('Location: '.$url);
	// end of try
	}
	catch (PDOException $ex) {
		print('Error while connecting to database, please check PDOErrors.txt');
		file_put_contents('PDOErrors.txt', $ex -> getMessage(), FILE_APPEND);
	}
?>
