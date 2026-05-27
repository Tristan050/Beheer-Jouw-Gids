<?php
function jg_db_debug_enabled(): bool
{
	$debug = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? getenv('APP_DEBUG') ?? '';
	return in_array(strtolower((string)$debug), ['1', 'true', 'yes', 'on'], true);
}

function func_dbsi_EXAMPLE()
{
	// s - De variabele is een string
	// i - De variabele is een integer
	// d - De variabele is een double of een float
	// b - De variabele is een blob
	$db = func_dbsi_open();
	$qry = "INSERT INTO tabel (naam, email, leeftijd, datum) VALUES (?, ?, ?, NOW())";
	if ($stmt = $db->prepare($qry)) {
		$stmt->bind_param('ssi', $naam, $email, $leeftijd);
		if (!$stmt->execute()) func_dbsi_error_stmt($stmt->error, $qry);
		$stmt->close();
	} else func_dbsi_error_db($db->error);
	$db->close();
}

//execSQL("SELECT * FROM table WHERE id = ?", array('i', $id), false);
//execSQL("SELECT * FROM table", array(), false);
//execSQL("INSERT INTO table(id, name) VALUES (?,?)", array('ss', $id, $name), true);
//$sql = Statement to execute;
//$parameters = array of type and values of the parameters (if any)
//$close = true to close $stmt (in inserts) false to return an array with the values;
function execSQL($sql, $params, $close, $database = "")
{
	$map = func_dbsi_getmap();
	if (empty($database)) include($map . "/config.php");
	else include($map . "/config_{$database}.php");
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_dbs);
	if ($stmt = $mysqli->prepare($sql)) {
		if (count($params) > 0) {
			call_user_func_array(array($stmt, 'bind_param'), refValues($params));
		}
		$stmt->execute();
		if ($stmt->error <> "") func_dbsi_error_db("SQL1: $sql<BR>" . $stmt->error);
		else {
			if ($close) {
				if (strtolower(substr($sql, 0, 6)) == "insert") $result = $stmt->insert_id;
				else  $result = $mysqli->affected_rows;
			} else {
				if (version_compare(PHP_VERSION, '5.4.0') >= 0) $result = $stmt->get_result();
			}
		}
		$stmt->close();
	} else func_dbsi_error_db("SQL2: $sql<BR>" . $mysqli->error);
	$mysqli->close();
	return  $result;
}

function refValues($arr)
{
	if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
		$refs = array();
		foreach ($arr as $key => $value) $refs[$key] = &$arr[$key];
		return $refs;
	}
	return $arr;
}

//func_dbsi_s("SELECT * FROM table WHERE id = ?", array('i', $id), false);
//func_dbsi_s("SELECT * FROM table", array(), false);
//func_dbsi_s("INSERT INTO table(id, name) VALUES (?,?)", array('ss', $id, $name), true);
//$sql = Statement to execute;
//$parameters = array of type and values of the parameters (if any)
//$close = true to close $stmt (in inserts) false to return an array with the values;
function func_dbsi_s($sql, $params, $close)
{
	$map = func_dbsi_getmap();
	include($map . "/config.php");
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_dbs);
	$stmt = $mysqli->prepare($sql);
	if (!$stmt) {
		func_dbsi_error_stmt($mysqli->error, $sql);
		$mysqli->close();
		return false;
	}
	if (count($params) > 0) call_user_func_array(array($stmt, 'bind_param'), refValues($params));
	if (!$stmt->execute()) func_dbsi_error_stmt($stmt->error, $sql);
	if ($close) $result = $mysqli->affected_rows;
	elseif (version_compare(PHP_VERSION, '5.4.0') >= 0) $result = $stmt->get_result();
	$stmt->close();
	$mysqli->close();
	return  $result;
}

//Select query met 1 variabele, in gebruik bij bodysupport forgot password->CHECKEN OF NOG IN GEBRUIK
function func_dbsi_s1($qry, $val, $type)
{
	$result = execSQL($qry, array($type, $val), false);
	return ($result);
}
//Select query met 2 variabele, in gebruik bij bodysupport api, misschien nog ombouwen naar variabel aantal met array->CHECKEN OF NOG IN GEBRUIK
function func_dbsi_s2($qry, $val, $val2, $type)
{
	$result = execSQL($qry, array($type, $val, $val2), false);
	return ($result);
}
//Functie ipv mysql_field_name
function func_dbsi_field_name($result, $field_offset)
{
	$properties = mysqli_fetch_field_direct($result, $field_offset);
	return is_object($properties) ? $properties->name : null;
}

function func_dbsi_error_stmt($error, $qry)
{
	$host = $_SERVER["HTTP_HOST"] ?? 'localhost';
	error_log("DB statement error on {$host}: {$error}");
	if (jg_db_debug_enabled()) {
		echo "Het uitvoeren van de query is mislukt.";
	}
	$result = func_dbsi_mailfout("ERROR $host", "Het uitvoeren van de query is mislukt: $error in query: $qry<br>Website: {$_SERVER["HTTP_HOST"]}<br>Ipadres: {$_SERVER["REMOTE_ADDR"]}<br>File:cms/functions/functions_dbs_mysql.php", "");
}
function func_dbsi_error_db($error)
{
	$host = $_SERVER["HTTP_HOST"] ?? 'localhost';
	error_log("DB error on {$host}: {$error}");
	if (jg_db_debug_enabled()) {
		echo "Er zit een fout in de query.";
	}
	$result = func_dbsi_mailfout("ERROR $host", "Er zit een fout in de query: $error<br>Website: {$_SERVER["HTTP_HOST"]}<br>Ipadres: {$_SERVER["REMOTE_ADDR"]}<br>File:cms/functions/functions_dbs_mysql.php", "");
}

function func_dbsi_select_1($strtable, $strfield, $strwhere, $strorder = "", $strextra = "")
{ //->CHECKEN OF NOG IN GEBRUIK
	error_log('Blocked unsafe dynamic SQL helper: func_dbsi_select_1');
	throw new RuntimeException('Gebruik van func_dbsi_select_1 is uitgeschakeld wegens SQL-veiligheid.');
}

function func_dbsi_qry($sql)
{
	$db = func_dbsi_open();
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
		$result = $db->query($sql);
	} catch (Exception $e) {
		error_log('Database insert query failed: ' . $e->getMessage());
		echo "<font color='red'>Er is een fout opgetreden op deze pagina.</font><br>";
		//$result=func_dbsi_mailfout("Qry fout $host", "{$_SERVER["HTTP_HOST"]}{$_SERVER['REQUEST_URI']}<br><br>Qry: $sql<br>Functie: func_dbsi_qry<br>Website: {$_SERVER["HTTP_HOST"]}<br>Script: {$_SERVER['SCRIPT_FILENAME']}<br>Ipadres: {$_SERVER["REMOTE_ADDR"]}<br>Fout:".$db->connect_error,"<font color='red'>Deze is doorgestuurd naar de beheerder!</font><br>");
		return false;
	}
	func_dbsi_close($db);
	return ($result);
}
function func_dbsi_multiqry($sql)
{
	$db = func_dbsi_open();
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
		$result = $db->multi_query($sql);
	} catch (Exception $e) {
		echo "<font color='red'>Er is een fout opgetreden op deze pagina.</font><br>";
		//$result=func_dbsi_mailfout("Qry fout $host", "{$_SERVER["HTTP_HOST"]}{$_SERVER['REQUEST_URI']}<br><br>Qry: $sql<br>Functie: func_dbsi_qry<br>Website: {$_SERVER["HTTP_HOST"]}<br>Script: {$_SERVER['SCRIPT_FILENAME']}<br>Ipadres: {$_SERVER["REMOTE_ADDR"]}<br>Fout:".$db->connect_error,"<font color='red'>Deze is doorgestuurd naar de beheerder!</font><br>");
		return false;
	}
	func_dbsi_close($db);
	return ($result);
}
function func_dbsi_qry_1($strqry)
{
	$result = func_dbsi_qry($strqry);
	if ($result->num_rows > 0) {
		$row = mysqli_fetch_array($result);
		return ($row[0]);
	}
}

function func_dbsi_qry_try($sql)
{ //Gewoon doorgaan bij fout zonder melding!
	$db = func_dbsi_open();
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
		$result = $db->query($sql);
	} catch (Exception $e) {
		return false;
	}
	func_dbsi_close($db);
	return ($result);
}
function func_dbsi_insert_qry($sql)
{
	$db = func_dbsi_open();
	$host = $_SERVER["HTTP_HOST"] ?? 'localhost';

	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
		$result = $db->query($sql);
		$newid = $db->insert_id;
		func_dbsi_close($db);
		return ($newid);
	} catch (Exception $e) {
		func_dbsi_close($db);
		echo "<font color='red'>Er is een fout opgetreden op deze pagina.</font><br>";
		$result = func_dbsi_mailfout("Qry fout $host", "Qry: $sql<br>Functie: func_dbsi_insert_qry<br>Website: {$_SERVER["HTTP_HOST"]}<br>Script: {$_SERVER['SCRIPT_FILENAME']}<br>Request url: {$_SERVER['REQUEST_URI']}<br>Ipadres: {$_SERVER["REMOTE_ADDR"]}<br>Fout:" . $db->connect_error, "<font color='red'>Deze is doorgestuurd naar de beheerder!</font><br>");
		return false;
	}
}
function func_dbsi_getmap()
{
	$map = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : getcwd();
	if ($map == "/") $map = getcwd();
	return ($map);
}

//Open de mysql database met MYSQLI
function func_dbsi_open()
{
	$map = func_dbsi_getmap();
	include($map . "/config.php");
	$db = new mysqli($db_host, $db_user, $db_pass, $db_dbs);
	if (isset($_SESSION["SET_MYSQLI_CHARSET_UFT8"]) and $_SESSION["SET_MYSQLI_CHARSET_UFT8"] == 1) $db->set_charset("utf8");
	if ($db->connect_errno > 0) {
		error_log('Database connection failed: ' . $db->connect_error);
		echo "<BR>Wegens technische problemen is deze pagina tijdelijk niet bereikbaar.<BR><BR>Excuses voor het ongemak. Probeer het over enkele minuten opnieuw.";
		func_dbsi_mailfout("Database error", "Geen verbinding met database $db_dbs op $db_host met gebruiker $db_user.<br>Website: {$_SERVER["HTTP_HOST"]}<br>Ipadres: {$_SERVER["REMOTE_ADDR"]}<br>Fout:" . $db->connect_error, "");
		exit();
	} else return ($db);
}

//Schrijft datum tijd uur/minuut weg. Als die al bestaat, dat false. (dus geen mail sturen) Max 1 per minuut. -> nu nog maar 1x per uur!
function func_dbsi_check_log()
{
	$log = "lasterror.txt";
	$datetime = date('d-m-Y H'); //:i:s
	$renewlog = false;
	if (file_exists($log)) {
		$myfile = fopen($log, "r");
		$txt = fread($myfile, filesize($log));
		fclose($myfile);
		if ($datetime <> $txt) $renewlog = true;
	} else $renewlog = true;
	if ($renewlog) {
		$myfile = fopen($log, "w") or die("Unable to open log file!");
		fwrite($myfile, $datetime);
		fclose($myfile);
		return (true);
	} else return (false);
}
function func_dbsi_mailfout($subject, $msg, $melding)
{
	//Zelfde fout maar 1x sturen PER UUR!!!
	if (!isset($_SESSION["mailfout"]) or $_SESSION["mailfout"] <> $msg) {
		$_SESSION["mailfout"] = $msg;
		//Niet snachts
		$hour = date('H');
		if ($hour > 6 and $hour < 23) {
			//En ook maximaal 1 mail per minuut! (per website)
			if (func_dbsi_check_log()) {
				$host = str_replace("http://", "", $_SERVER["HTTP_HOST"]);
				$host = str_replace("https://", "", $host);
				$host = str_replace("www.", "", $host);
				$email = "info@$host";
				$result = mail("info@itrend.nl", $subject, $msg, "From: $email\nReply-To: $email\nContent-Type: text/html; charset=iso-8859-1\n");
				if ($melding <> "") echo $melding;
			}
		}
	}
}
//Sluit de mysql database met MYSQLI
function func_dbsi_close($db)
{
	$db->close();
}

function func_dbsi_copy($table, $idfield, $idvalue)
{
	error_log('Blocked unsafe dynamic SQL helper: func_dbsi_copy');
	throw new RuntimeException('Gebruik van func_dbsi_copy is uitgeschakeld wegens SQL-veiligheid.');
}
function func_dbsi_isJson($string)
{
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}
