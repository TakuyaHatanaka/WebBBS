<html>
	<head>
		<link rel="stylesheet" href="bbs.css">
		<link rel="icon" href="img/bbs.ico">
		<title>EDIT MESSAGE</title>
	</head>
	<body>
		<?php
			require_once("dbc.php");
			$id = $_GET["id"];
			$dbc = new DBC();
			$dbh = $dbc->dbConnect();
			$editData = $dbc->getById($id);
		?>

		<!-- 編集フォーム -->
		<?php if($_GET["action"] === "update"): ?>
			<h1>投稿の編集</h1>
			<form action ="update.php" method="post">
			<input type="hidden" name="id" value="<?php echo $id ?>">
				<div class="name">
					<label for="name">名前</label>
					<input type="text" name="name" id="name" value="<?php echo $editData["name"]; ?>" required>
				</div>
				<div class="message">
					<label calss="labelMessage" for="message">本文</label>
					<textarea name="message" id="message" cols="30" rows="5" required>
						<?php echo strip_tags($editData["message"], "<br />"); ?>
					</textarea>
				</div>
				<div class="buttonEdit">
					<input class="button" type="submit" name="buttonEdit" value="更新">
				</div>
			</form>
			<form action ="index.php" method="post">
				<div class="buttonBack">
					<input class="button" type="submit" name="buttonBack" value="戻る"> 
				</div>
			</form>
		<?php endif ?>

		<!-- 削除フォーム -->
		<?php if($_GET["action"] === "delete"): ?>
			<h1>投稿の削除</h1>
				<form action ="delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id ?>">
					<div class="romMessage">
						<p>名前：<?php echo $editData["name"]; ?></p>
						<p>本文：<?php echo $editData["message"]; ?></p>
					</div>
					<div class="buttonDelete">
						<input class="button" type="submit" name="buttonDelete" value="削除">
					</div>
				</form>
				<form action ="index.php" method="post">
					<div class="buttonBack">
						<input class="button" type="submit" name="buttonBack" value="戻る">
					</div>
				</form>
		<?php endif ?>
		
		<!-- 返信フォーム -->
		<?php if($_GET["action"] === "reply"): ?>
			<h1>投稿へ返信</h1>
				<form action ="reply.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id ?>">
				<input type="hidden" name="reply_to_id" value="<?php echo $reply_to_id ?>">
				<input type="hidden" name="reply_no" value="<?php echo $reply_no ?>">
					<h3>返信先</h3>
					<div class="romMessage">
						<p>名前：<?php echo $editData["name"]; ?></p>
						<p>本文：<?php echo $editData["message"]; ?></p>
					</div>
					<h3>返信内容</h3>
					<div class="name">
						<label for="name">名前</label>
						<input type="text" name="name" id="name" required>
					</div>
					<div class="message">
						<label calss="labelMessage" for="message">本文</label>
						<textarea name="message" id="message" cols="30" rows="5" required>Re:</textarea>
					</div>
					<div class="buttonReply">
						<input class="button" type="submit" name="buttonReply" value="返信">
					</div>
				</form>
				<form action ="index.php" method="post">
					<div class="buttonBack">
						<input class="button" type="submit" name="buttonBack" value="戻る"> 
					</div>
		<?php endif ?>

    </body>
</html>