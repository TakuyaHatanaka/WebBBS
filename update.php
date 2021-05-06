<html>
<head>
	<link rel="stylesheet" href="bbs.css">
	<link rel="icon" href="img/bbs.ico">
	<title>EDIT COMPLITE</title>
</head>
<body>
		
	<?php
		require_once("dbc.php");
		$data = $_POST;
		$dbc = new DBC();
		$dbc->update($data);
	?>

	<form action="index.php">
		<input class="button" type="submit" value="戻る">
	</form>

</body>
</html>