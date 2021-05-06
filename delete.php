<html>
<head>
	<link rel="stylesheet" href="bbs.css">
	<link rel="icon" href="img/bbs.ico">
	<title>DELETE COMPLITE</title>
</head>
<body>
		
	<?php

		require_once("dbc.php");
		$dbc = new DBC();
		$id = $_POST["id"];
		$dbc->delete($id);
		
	?>

	<form action="index.php">
		<input class="button" type="submit" value="戻る">
	</form>

</body>

</html>