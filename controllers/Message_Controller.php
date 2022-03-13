<?php  
	require_once "./models/Account_Model.php";
	require_once "./models/Message_Model.php";
	require_once "./models/Table_Model.php";
	require_once "./models/Information_Model.php";
	require_once "./models/Forums_Model.php";

	class Message_Controller{
		private $Account;
		private $Message;
		private $Table;
		private $Information;
		private $Forums;

		function __construct(){
			$this->Account = new Account_Model();	
			$this->Message = new Message_Model();
			$this->Table = new Table_Model();
			$this->Information = new Information_Model();
			$this->Forums = new Forums_Model();
		}

		public function view($view, $data=[]){
			require_once "./views/".$view.".php";
		}

		public function Register(){
			$this->view("register");
		}

		public function Login(){
			$this->view("login");
		}

		public function ProcessRegister(){
			require_once './libs/middleware.php';
			require_once './libs/functions.php';

			$user = $_POST['username'];
			$pass = sha1(salt.$_POST['password']);

			if($this->Account->Insert($user, $pass)){
				$account = $this->Account->GetInfomation($user);
				$_SESSION['message_userID'] = $account['id'];
				$_SESSION['message_userNAME'] = $account['username'];
				$_SESSION['message_userROLE'] = $account['role'];
				$_SESSION['message_userAVATAR'] = $account['avatar'];
				$_SESSION['message_userIPADRESS'] = get_client_ip();
				$_SESSION['message_userJOIN'] = $account['date_create'];

				$this->Information->Insert($_SESSION['message_userIPADRESS']);

				header('Location: ./');
			}else{
				setcookie("error_user", $user, time() + 2, "/");
				setcookie("error_message", "Username available!", time() + 2, "/");
				header('Location: ./?url=register');
			}
		}

		public function ProcessLogin(){
			require_once './libs/middleware.php';
			require_once './libs/functions.php';

			$user = $_POST['username'];
			$pass = sha1(salt.$_POST['password']);

			if($this->Account->CheckLogin($user, $pass)){
				$account = $this->Account->GetInfomation($user);
				
				if($account['disable'] == 'YES'){
					setcookie("error_user", $user, time() + 2, "/");
					setcookie("error_message", "Account has been disabled!", time() + 2, "/");
					header('Location: ./?url=login');
				}else{
					$_SESSION['message_userID'] = $account['id'];
					$_SESSION['message_userNAME'] = $account['username'];
					$_SESSION['message_userROLE'] = $account['role'];
					$_SESSION['message_userAVATAR'] = $account['avatar'];
					$_SESSION['message_userIPADRESS'] = get_client_ip();
					$_SESSION['message_userJOIN'] = $account['date_create'];

					$this->Information->Insert($_SESSION['message_userIPADRESS']);

					header('Location: ./');
				}
			}else{
				setcookie("error_user", $user, time() + 2, "/");
				setcookie("error_message", "Username or password is incorrect!", time() + 2, "/");
				header('Location: ./?url=login');
			}
		}

		public function ProcessLogout(){
			unset($_SESSION['message_userID']);
			unset($_SESSION['message_userNAME']);
			unset($_SESSION['message_userROLE']);
			unset($_SESSION['message_userAVATAR']);
			unset($_SESSION['message_userIPADRESS']);
			unset($_SESSION['message_userJOIN']);

			header('Location: ./');
		}

		public function HomePage(){
			require_once './libs/functions.php';

			$this->view("message", [
				'page'			=> 	'my_message',
				'list_mess'		=> 	$this->Message->GetMessage(),
				'num_mess'		=> 	$this->Message->CountMessage(),
				'db_size'		=>	$this->Table->GetSize()
			]);
		}
		

		public function AjaxPushMessage(){
			require_once './libs/functions.php';

			$this->Message->Insert(FormatMessage($_POST['mess']));
			$data = $this->Message->GetNow();

			$data_return = '<div id="mess-'.$data['id'].'" class="direct-chat-msg message">
              <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">'.$_SESSION['message_userNAME'].'</span>
                <span class="direct-chat-timestamp float-right">'.ConvertDate($data['date_create']).'</span>
              </div>
              <img class="direct-chat-img" src="'.$_SESSION['message_userAVATAR'].'" alt="message user image">
              <div class="direct-chat-text">
              <div class="float-right">
                                                <button class="dropdown btn btn-sm" style="margin-top: -11px; margin-right: -20px;">
                                                    <a class="nav-link text-muted" data-toggle="dropdown" href="#">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="copyToClipboard(`#cpy-'.$data['id'].'`)"><i class="fas fa-copy"></i> Copy message</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="editMessage(`'.$data['id'].'`)"><i class="fas fa-edit"></i> Edit message</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="deleteMessage(`'.$data['id'].'`)"><i class="fa fa-trash"></i> Delete message</a>
                                                    </div>
                                                </button>
                                            </div><p id="cpy-'.$data['id'].'">'.ConvertMessage($data['message']).'</p></div>
            </div>';

            echo $data_return;
		}

		public function AjaxGetMessage(){
			require_once './libs/functions.php';
			
			$data = $this->Message->GetMessageFrom($_POST['from']);
			$data_return = '';

			foreach ($data as $value) {
				$data_return .= '<div id="mess-'.$value['id'].'" class="direct-chat-msg message">
              <div class="direct-chat-infos clearfix">
                <span class="direct-chat-name float-left">'.$_SESSION['message_userNAME'].'</span>
                <span class="direct-chat-timestamp float-right">'.ConvertDate($value['date_create']).'</span>
              </div>
              <img class="direct-chat-img" src="'.$_SESSION['message_userAVATAR'].'" alt="message user image">
              <div class="direct-chat-text">
              <div class="float-right">
                                                <button class="dropdown btn btn-sm" style="margin-top: -11px; margin-right: -20px;">
                                                    <a class="nav-link text-muted" data-toggle="dropdown" href="#">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="copyToClipboard(`#cpy-'.$value['id'].'`)"><i class="fas fa-copy"></i> Copy message</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="editMessage(`'.$value['id'].'`)"><i class="fas fa-edit"></i> Edit message</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item dropdown-footer text-left" onclick="deleteMessage(`'.$value['id'].'`)"><i class="fa fa-trash"></i> Delete message</a>
                                                    </div>
                                                </button>
                                            </div><p id="cpy-'.$value['id'].'">'.ConvertMessage($value['message']).'</p></div>
            </div>';
			}
			

            echo $data_return;
		}

		public function ProcessUpdateAccount(){
			require_once './libs/middleware.php';

			$user = $_POST['username'];
			$newpass = $_POST['new_password'];
			$confirm_pass = sha1(salt.$_POST['confirm_password']);

			//can't change password
			if(strlen(trim($newpass)) <= 0){
				if(trim($user) === trim($_SESSION['message_userNAME'])){
					header('Location: ./');
				}else{
					if($this->Account->ChangeUsername($user, $confirm_pass) > 0){
						$_SESSION['message_userNAME'] = $user;
						header('Location: ./');
					}else{
						setcookie("error_message", "Incorrect password!", time() + 2, "/");
						header('Location: ./');
					}
				}
			}else{
				//change pass
				if(trim($user) === trim($_SESSION['message_userNAME'])){
					$newpass = sha1(salt.$newpass);
					if($this->Account->ChangePassword($newpass, $confirm_pass) > 0){
						header('Location: ./');
					}else{
						setcookie("error_pass", "Incorrect password!", time() + 2, "/");
						header('Location: ./');
					}
				}else{
					//change pass and user
					$newpass = sha1(salt.$newpass);

					if($this->Account->ChangeUserPassword($user, $newpass, $confirm_pass) > 0){
						$_SESSION['message_userNAME'] = $user;
						header('Location: ./');
					}else{
						setcookie("error_pass", "Username already exists or password confirmation is wrong!", time() + 2, "/");
						header('Location: ./');
					}
				}
			}
		}

		public function ProcessDeleteMessage(){
			require_once './libs/middleware.php';
			$id = $_POST['idMess'];
			$pass = sha1(salt.$_POST['pass']);
			if($this->Account->CheckAccount($pass) > 0){
				$this->Message->DeleteMessage($id);
				echo 'true';
			}else{
				echo 'false';
			}
		}

		public function ProcessEditMessage(){
			require_once './libs/middleware.php';
			require_once './libs/functions.php';

			$id = $_POST['idMess'];
			$pass = sha1(salt.$_POST['pass']);
			$content = FormatMessage($_POST['cont']);

			if($this->Account->CheckAccount($pass) > 0){
				$this->Message->UpdateMessage($id, $content);
				echo str_replace("\n", "<br/>", $_POST['cont']);
			}else{
				echo 'false';
			}
		}

		public function ProcessDatabaseSize(){
			echo $this->Table->GetSize();
		}
		
		public function ProcessChangeAvatar(){
			require_once './libs/functions.php';

			if(isset($_FILES['file']['name'])){
				if($_SESSION['message_userAVATAR'] != "./storage/user.jpeg"){
					unlink($_SESSION['message_userAVATAR']);
				}

				/* Getting file name */
				$filename = $_FILES['file']['name'];

				/* Location */
				$location = "./storage/"."avatar-".RandomCode()."-".$filename;
				$imageFileType = pathinfo($location, PATHINFO_EXTENSION);
				$imageFileType = strtolower($imageFileType);

				/* Valid extensions */
				$valid_extensions = array("jpg","jpeg","png");

				$response = 0;
				/* Check file extension */
				if(in_array(strtolower($imageFileType), $valid_extensions)) {
				  	/* Upload file */
				    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
				        $this->Account->UpdateAvatar($location);
				        $_SESSION['message_userAVATAR'] = $location;
				    }
				}
			}
		}

		public function Forums(){
			require_once './libs/functions.php';

			$this->view('message', [
				'page'			=>	'forums',
				'db_size'		=>	$this->Table->GetSize(),
				'mess_forums'	=>	$this->Forums->GetForums(),
				'num_mess'		=> 	$this->Forums->CountMessForums()
			]);
		}

		public function ProcessPushForums(){
			require_once './libs/functions.php';
			$this->Forums->Insert(FormatMessage($_POST['mess']));
			$data = $this->Forums->GetForums();
			$num_mess = $this->Forums->CountMessForums();
			$data_return = '';

			if($num_mess > 25){
				$data_return = '<div id="thisIsTop" style="text-decoration: underline; cursor: pointer;">
                    <p class="text-center text-danger">View more...</p>
                </div>';
			}
			

			foreach ($data as $value) {
				if($value['account_id'] == $_SESSION['message_userID']){
					$data_return .= '<div class="direct-chat-msg right mt-4">
                        <img class="direct-chat-img" src="'.$value['avatar'].'" alt="message user image">
                        <div class="direct-chat-text">
                        	<div class="float-right">
                                <button class="btn btn-sm nav-link text-muted" onclick="editComment(`'.$value['id'].'`)" style="margin-top: -11px; margin-right: -17px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                            <p id="content-cmt-'.$value['id'].'">'.ConvertMessage($value['message']).'</p>
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>  
                        </div>
                    </div>';
				}else{
					$data_return .= '<div class="direct-chat-msg mt-4">
                        <img class="direct-chat-img" src="'.$value['avatar'].'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>  
                        </div>
                    </div>';
				}
			}

			$data_return .= '<div id="append"></div>';
			
			echo $data_return;
		}

		public function ProcessPrevForums(){
			require_once './libs/functions.php';

			$data = $this->Forums->GetNowForm($_POST['from']);
			$data_return = '';

			foreach ($data as $value) {
				if($value['account_id'] == $_SESSION['message_userID']){
					$data_return .= '<div class="direct-chat-msg right mt-4">
                        <img class="direct-chat-img" src="'.$value['avatar'].'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>  
                        </div>
                    </div>';
				}else{
					$data_return .= '<div class="direct-chat-msg mt-4">
                        <img class="direct-chat-img" src="'.$value['avatar'].'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>  
                        </div>
                    </div>';
				}
			}
			echo $data_return;
		}

		public function AjaxReloadForums(){
			require_once './libs/functions.php';
			$data = $this->Forums->GetForums();
			$num_mess = $this->Forums->CountMessForums();
			$data_return = '';

			if($num_mess > 25){
				$data_return = '<div id="thisIsTop" style="text-decoration: underline; cursor: pointer;">
                    <p class="text-center text-danger">View more...</p>
                </div>';
			}
			

			foreach ($data as $value) {
				if($value['account_id'] == $_SESSION['message_userID']){
					$data_return .= '<div class="direct-chat-msg right mt-4">
                        <img class="direct-chat-img" src="'.$value['avatar'].'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>  
                        </div>
                    </div>';
				}else{
					$data_return .= '<div class="direct-chat-msg mt-4">
                        <img class="direct-chat-img" src="'.$value['avatar'].'" alt="message user image">
                        <div class="direct-chat-text">
                            '.ConvertMessage($value['message']).'
                            <hr style="margin: 0px; padding: 0px;">
                            <span class="direct-chat-timestamp text-light">'.$value['username'].'</span> | 
                            <span class="direct-chat-timestamp text-light">'.ConvertDate($value['date_create']).'</span>  
                        </div>
                    </div>';
				}
			}

			$data_return .= '<div id="append"></div>';
			
			echo $data_return;
		}

		public function AjaxEditCommentForums(){
			require_once './libs/middleware.php';
			require_once './libs/functions.php';

			$id = $_POST['id'];
			$pass = sha1(salt.$_POST['pass']);
			$comment = FormatMessage($_POST['comment']);

			if($this->Account->CheckAccount($pass) > 0){
				$this->Forums->EditComment($id, $comment);
			}else{
				echo 'false';
			}
		}

		public function AjaxGetRecordAccount(){
			require_once './libs/functions.php';

			$result = $this->Information->GetAll();
			$data_return = '<div class="table-responsive" style="height:400px;"><table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">IP ADDRESS</th>
                    <th scope="col">LOGIN DATE</th>
                </tr>
            </thead>
            <tbody>';

            $i = 1;
			foreach ($result as $value) {
				$data_return .= '<tr>
                    <th scope="row" width="50">'.$i.'</th>
                    <td>'.$value['ip_address'].'</td>
                    <td>'.ConvertDate($value['date_create']).'</td>
                </tr>';
                $i++;
			}

			$data_return .= '</tbody></table></div>';

			echo $data_return;
		}
	}
?>