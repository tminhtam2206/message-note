<?php  require_once "./models/dbCon.php";
	class Information_Model{
		private $Information;

		function __construct(){
			$this->Information = new dbCon();
			$this->Information = $this->Information->KetNoi();
		}

		public function Insert($ip_address){
			try{
				$qr = "INSERT INTO tbl_information(account_id, ip_address, date_create) VALUES (:account_id, :ip_address, :date_create)";
				$cmd = $this->Information->prepare($qr);
				$cmd->bindValue(":account_id", $_SESSION['message_userID']);
				$cmd->bindValue(":ip_address", $ip_address);
				$cmd->bindValue(":date_create", date('Y-m-d H:i:m'));
				$cmd->execute();
			}
			catch(PDOException $e){
				return false;
			}
		}

		public function GetAll(){
			try{
				$qr = "SELECT info.* FROM tbl_information info, tbl_account acc WHERE info.account_id = acc.id AND info.account_id = :account_id ORDER BY info.date_create DESC LIMIT 0, 25";
				$cmd = $this->Information->prepare($qr);
				$cmd->bindValue(":account_id", $_SESSION['message_userID']);
				$cmd->execute();
				return array_reverse($cmd->fetchAll());
			}
			catch(PDOException $e){
				return false;
			}
		}
	}
?>