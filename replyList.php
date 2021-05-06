<html>
	<head>
		<link rel="stylesheet" href="bbs.css">
		<link rel="icon" href="img/bbs.ico">
		<title>PHP BBS REPLYLIST</title>
	</head>
	<body>

	<h1>返信一覧　<a class="back" href="index.php">戻る</a></h1>

<?php

    require_once("dbc.php");
    $id = $_GET["id"];
    $dbc = new DBC();
	$parent_message = $dbc->getById($id);
    $dbh = $dbc->dbConnect();
    $sql = "SELECT * FROM message WHERE reply_to_id = $id ORDER BY date DESC";
	try {
		$stmt = $dbh->query($sql);
	} catch (PDOException $e) {
		exit($e->getMessage());
	}
    
?>

	<div class="messageList">
		<div class="messageDetail">
			<p>
				<?php echo $parent_message['name']." / ".$parent_message['date']?>
				<a href="editForm.php?id=<?php echo $parent_message["id"] ?>">編集</a>
				<a href="deleteForm.php?id=<?php echo $parent_message["id"] ?>">削除</a>
				<a href="replyForm.php?id=<?php echo $parent_message["id"] ?>">返信</a>
			</p>
			<p>
				<?php echo $parent_message['message'] ?>
			</p>
		</div>

		
		<?php foreach($stmt as $row): ?>
			<div class="replyMessageDetail">
        	
			<p>
				<?php echo $row['name']." / ".$row['date']?>
				<a href="editForm.php?id=<?php echo $row["id"] ?>">編集</a>
				<a href="deleteForm.php?id=<?php echo $row["id"] ?>">削除</a>
				<a href="replyForm.php?id=<?php echo $row["id"] ?>">返信</a>
			</p>
        	<p>
				<?php echo $row['message'] ?>
				<?php $reply_num = $dbc->numberOfReply($row); ?>
				<?php if(!($reply_num === "0")): ?>
				<a href="replyList.php?id=<?php echo $row["id"] ?>">返信一覧<?php echo "(".$reply_num.")件" ?></a>
				<?php endif ?>
			</p>
				</div>

      	<?php endforeach ?>
	</div>

    </body>
</html>