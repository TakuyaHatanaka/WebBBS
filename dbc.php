<?php
require_once("config.php");

class DBC {
		
	private $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
	
	/**
	 * DB接続
	 * @return $dbh
	 */ 
	function dbConnect() {
		//$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";
		try {
			$dbh = new PDO($this->dsn, DB_USER, DB_PASS);
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
		return $dbh;
	}

	/**
	 * メッセージ取得
	 * @return $stmt
	 */
	function getAllMessage() {
		//DBに接続
		$dbh = $this->dbConnect();
		//SELECT文を変数に格納
		$sql = "SELECT * FROM message WHERE reply_to_id = 0 ORDER BY id DESC";
		try {
			//SQLステートメントを実行し、結果を変数に格納
			$stmt = $dbh->query($sql);
			return $stmt;
		} catch(PDOException $e) {
			exit($e->getMessage());
		}
		$dbh = null;
	}

	/**
	 * idでメッセージを取得
	 * @param $id 投稿ID
	 * @return $editdata idが一致するメッセージ
	 */
	function getById($id) {
		//DBに接続
		$dbh = $this->dbConnect();
		//SELECT文を変数に格納
		$sql = "SELECT * FROM message WHERE id = $id";	
		//SQLステートメントを実行し、結果を変数に格納
		try {
			$stmt = $dbh->query($sql);
			$editData = $stmt->fetch(PDO::FETCH_ASSOC);
			return $editData;
			$dbh = null;
		} catch(PDOException $e) {
			exit($e->getMessage());
		}
	}

	/**
	 * メッセージをDBに登録
	 * @param $data 投稿メッセージ
	 */
	function regist($data){
		date_default_timezone_set('Asia/Tokyo');
		$id = null;
		$name = htmlspecialchars($data['name']);
		$message = nl2br(htmlspecialchars($data['message']));
		$date = date('Y-m-d H:i:s');
		//DBに接続
		$dbh = $this->dbConnect();
		//INSERT文を変数に格納
		$sql = "INSERT INTO message (id, name, message, date) VALUES (:id, :name, :message, :date)";
		try {
			//データの保存
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':id',$id, PDO::PARAM_INT);
			$stmt->bindParam(':name',$name, PDO::PARAM_STR);
			$stmt->bindParam(':message',$message, PDO::PARAM_STR);
			$stmt->bindValue(':date', $date, PDO::PARAM_STR);
			$stmt->execute();
		}catch(PDOException $e) {
			exit($e->getMessage());
		}
		echo "投稿が完了しました";
	}

	/**
	 * メッセージの更新
	 * @param　$data 更新するメッセージ
	 */
	function update($data) {
		date_default_timezone_set('Asia/Tokyo');
		$id = $data["id"];
		$name = htmlspecialchars($data['name']);
		$message = nl2br(htmlspecialchars($data['message']));
		$date = date('Y-m-d H:i:s');
		//DBに接続
		$dbh = $this->dbConnect();
		//UPDATE文を変数に格納
		$sql = "UPDATE message SET name = :name, message = :message, date = :date WHERE id = :id";
		try {
			//データの更新
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':id',$id, PDO::PARAM_INT);
			$stmt->bindParam(':name',$name, PDO::PARAM_STR);
			$stmt->bindParam(':message',$message, PDO::PARAM_STR);
			$stmt->bindValue(':date', $date, PDO::PARAM_STR);
			$stmt->execute();
		}catch(PDOException $e) {
			exit($e->getMessage());
		}
		echo "更新が完了しました";
	}

	/**
	 * メッセージを削除
	 * @param $id 削除する投稿ID
	 */
	function delete($id) {
		//DBに接続
		$dbh = $this->dbConnect();
		//DELETE文を変数に格納(返信もまとめて削除)
		$sql = "DELETE FROM message WHERE id = $id OR reply_to_id = $id";
		try {
			$dbh->query($sql);
		} catch(PDOException $e) {
			exit($e->getMessage());
		}
		echo "削除が完了しました";
	}

	/**
	 * メッセージに返信を投稿
	 * @param $data 返信先のメッセージ
	 */
	function reply($data) {
		date_default_timezone_set('Asia/Tokyo');
		$id = null;
		//返信先の投稿ID
		$reply_to_id = $data["id"];
		//DBに接続
		$dbh = $this->dbConnect();

		//返信メッセージ数取得
		$sql_count = "SELECT COUNT(reply_to_id = $reply_to_id OR NULL) FROM message";
		try {
			$stmt_count = $dbh->query($sql_count);
		} catch(PDOException $e) {
			exit($e->getMessage());
		}
		$reply_no = $stmt_count->fetchColumn();
		$reply_no ++;

		$name = htmlspecialchars($data['name']);
		$message = nl2br(htmlspecialchars($data['message']));
		$date = date('Y-m-d H:i:s');
		
		//INSERT文を変数に格納
		$sql = "INSERT INTO message (id, reply_to_id, reply_no, name, message, date) 
						VALUES (:id, :reply_to_id, :reply_no, :name, :message, :date)";
		try {
			//データの保存
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':id',$id, PDO::PARAM_INT);
			$stmt->bindParam(':reply_to_id',$reply_to_id, PDO::PARAM_INT);
			$stmt->bindParam(':reply_no',$reply_no, PDO::PARAM_INT);
			$stmt->bindParam(':name',$name, PDO::PARAM_STR);
			$stmt->bindParam(':message',$message, PDO::PARAM_STR);
			$stmt->bindValue(':date', $date, PDO::PARAM_STR);
			$stmt->execute();
		}catch(PDOException $e) {
			exit($e->getMessage());
		}
		echo "返信しました";
	}

	/**
	 * 返信の数を取得
	 * @param $data 親メッセージ
	 * @return $reply_num 返信数
	 */
	function numberOfReply($data) {
		$reply_to_id = $data["id"];
		//DBに接続
		$dbh = $this->dbConnect();
		//親メッセージIDとreply_to_idが一致する数を数える
		$sql = "SELECT COUNT(*) FROM message WHERE reply_to_id = $reply_to_id";
		try {
			$count = $dbh->query($sql);
			$reply_num = $count->fetchColumn();
		} catch (PDOException $e) {
			exit($e->getMessage());
		}
		return $reply_num;
	}
}

?>