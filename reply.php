<html>
<head>
	<link rel="stylesheet" href="bbs.css">
	<link rel="icon" href="img/bbs.ico">
	<title>REPLY COMPLITE</title>
</head>
<body>
		
<?php

	require_once("dbc.php");
	$id = $_POST["id"];
	$data = $_POST;
	$dbc = new DBC();
	$dbc->reply($data);
	
?>

<form action="index.php">
	<input class="button" type="submit" value="戻る">
</form>

</body>
</html>