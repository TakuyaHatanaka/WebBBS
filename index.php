<html>
	<head>
		<link rel="stylesheet" href="bbs.css">
		<link rel="icon" href="img/bbs.ico">
		<title>PHP BBS</title>
	</head>
	<body>

		<h1>掲示板</h1>
		<form action ="regist.php" method="post">
			<div class="name">
				<label class="label" for="name">名前</label>
				<input type="text" name="name" id="name" required>
			</div>
			<div class="message">
				<label class="label" for="message">本文</label>
				<textarea name="message" id="message" cols="25" rows="5" required></textarea>
			</div>
			<div>
				<input class="button" type="submit" name="button" value="投稿">
			</div>
		</form>

		<?php
			//DBからデータを取得
			require_once("config.php");
			require_once("dbc.php");
			$dbc = new DBC();
			$dbh = $dbc->dbConnect();
			$stmt = $dbc->getAllMessage();
			$data = $stmt->fetchAll();
			//1ページの表示数
			//define('MAX','5');
            //返信を除くメッセージ数
			$sql = "SELECT COUNT(*) FROM message WHERE reply_to_id = 0";
			try {
				$count = $dbh->query($sql);
			} catch (PDOException $e) {
				exit($e->getMessage());
			}
			$total_data = $count->fetchColumn();
			
			//最大ページ数
    		$max_page = ceil($total_data / MAX);
			//現在のページ番号を設定
			if(!isset($_GET['page'])) { 
				$page = 1;
			} else {
				$page = $_GET['page'];
			}
			//配列の何番目から取得するか
			$start_no = ($page - 1) * MAX; 
			//現在のページに表示するデータを取得
			$disp_data = array_slice($data, $start_no, MAX, true);
			
		?>

		<h2>
			投稿一覧
			<p class="currentPage"><?php echo $max_page."ページ中".$page."ページ目" ?></p>
		</h2>
		<div class="messageList">
			<?php foreach($disp_data as $row): ?>
			<?php if($row["reply_to_id"] === "0"): ?>
        <div class="messageDetail">
			<p>
				<?php echo $row['name']." / ".$row['date']?>
				<a href="editForm.php?id=<?php echo $row["id"] ?>&action=update">編集</a>
				<a href="editForm.php?id=<?php echo $row["id"] ?>&action=delete">削除</a>
				<a href="editForm.php?id=<?php echo $row["id"] ?>&action=reply">返信</a>
			</p>
        	<p>
				<?php echo $row['message'] ?>

				<?php $reply_num = $dbc->numberOfReply($row); ?>
				<?php if(!($reply_num === "0")): ?>
				<a href="replyList.php?id=<?php echo $row["id"] ?>">返信一覧<?php echo "(".$reply_num."件)" ?></a>
				<?php endif ?>
			</p>
				</div>
			<?php endif ?>
      		<?php endforeach ?>

			<?php if($page > 1): ?>
				<a href="index.php?page=<?php echo ($page - 1); ?>">前へ</a>
			<?php endif ?>

			<?php for($i = 1; $i <= $max_page; $i++): ?>
				<?php if($i == $page): ?>
					<?php echo $i; ?>
				<?php else: ?>
					<a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
				<?php endif ?>
			<?php endfor ?>

			<?php if($page < $max_page): ?>
				<a href="index.php?page=<?php echo ($page + 1); ?>">次へ</a>
			<?php endif ?>
		</div>

	</body>
</html>