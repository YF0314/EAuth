<?php
/**
 * This is SQL based user auth library
 * @author @Xere_yukky
 */
require 'html.php';
require 'config/define.php';
session_start();

class Main
{



	public function login($dir)
	{
		if ($_SESSION['auth'] == true) {
			header('Location: '.PANEL, true, 301);
			return true;
		}
		$dsn = 'mysql:host='.DB_Server.';dbname='.DB_Name.';charset=utf8';
    	$db = new PDO($dsn,DB_User,DB_Password);
    	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$view = new html;
		if (isset($_POST['submit'])) {
			if ($_SESSION['csrf_token'] == $_POST['csrf_token']
			 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
			  && $this->checkpassword($_POST['password'])) {
			  	try {
                    $sql = 'SELECT * FROM users WHERE email=:email';
                    $prepare = $db->prepare($sql);
                    $prepare->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
                    $prepare->execute();
                    $user = $prepare->fetch(PDO::FETCH_ASSOC);
			  	} catch (PDOException $e) {
			  		echo $view->login_form($dir,'Something wrong.');
			  	return true;
			  	}
			  	if ($user['email'] == $_POST['email']
			  	 && password_verify($_POST['password'], $user['password'])) {
			  		session_regenerate_id(true);
			  	 	$_SESSION['auth'] = true;
			  	 	$_SESSION['userid'] = $user['id'];
			  	 	header('Location: '.PANEL, true, 301);
			  	 	return true;
			  	 	exit();
			  	}
			}else{
				echo $view->login_form($dir,'Invalid email address or invalid password or invalid csrf_token.');
				return true;
			}
		}else{
			echo $view->login_form($dir);
			return true;
		}
	}



	public function register()
	{
		if ($_SESSION['auth'] == true) {
			header('Location: '.PANEL, true, 301);
			return true;
		}
		$dsn = 'mysql:host='.DB_Server.';dbname='.DB_Name.';charset=utf8';
    	$db = new PDO($dsn,DB_User,DB_Password);
    	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$view = new html;
		if (isset($_POST['confirm'])) {
			// Email confirm code verification
			if ($_SESSION['csrf_token'] == $_POST['csrf_token']
				&& filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				try {
			  		$sql = 'SELECT * FROM temp_account WHERE email=:email';
					$prepare = $db->prepare($sql);
                    $prepare->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
                    $prepare->execute();
                    $temp_user = $prepare->fetch(PDO::FETCH_ASSOC);
				} catch (PDOException $e) {
					echo $view->comp($_POST['email'],$hashed_pass,'Something wrong.');
					return true;
				}
				if ($temp_user['code'] === $_POST['code']
					&& $temp_user['password'] === $_POST['password']) {
					try {
						$sql = 'DELETE FROM temp_account WHERE email = :email';
						$prepare = $db->prepare($sql);
						$prepare->bindValue(':email', $temp_user['email'], PDO::PARAM_STR);
						$result = $prepare->execute();
						$sql = 'INSERT INTO users (email,password) VALUES (:email,:pass)';
						$prepare = $db->prepare($sql);
						$prepare->bindValue(':email',$temp_user['email'], PDO::PARAM_STR);
						$prepare->bindValue(':pass',$temp_user['password'], PDO::PARAM_STR);
						$prepare->execute();
						echo $view->gj();
						return true;
					} catch (PDOException $e) {
						echo $view->comp($_POST['email'],$hashed_pass,'Something wrong.');
						return true;
					}
				}
			}else{$view->comp($_POST['email'],$hashed_pass,'Invalid code');}
		}elseif (isset($_POST['submit'])) {
			if ($_SESSION['csrf_token'] == $_POST['csrf_token']
			 && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
			  && $this->checkpassword($_POST['password'])) {
			  	// usersテーブルに登録されたメールアドレスがないかを確認
			  	try {
			  		$sql = 'SELECT id FROM users WHERE email=:email';
					$prepare = $db->prepare($sql);
                    $prepare->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
                    $prepare->execute();
                    $user = $prepare->fetch(PDO::FETCH_ASSOC);
			  	} catch (PDOException $e) {
			  		echo $view->register_form('Something wrong.');
			  		return true;
			  	}
			  	if (empty($user['id'])) {
			  		// usersテーブルにemailがなかったら仮アカウントにEmailがないか確認
			  		try {
			  			$sql = 'SELECT id FROM temp_account WHERE email=:email';
						$prepare = $db->prepare($sql);
                    	$prepare->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
                    	$prepare->execute();
                    	$temp_account = $prepare->fetch(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                    	echo $view->register_form('Something wrong.');
                    	return true;
			  		}
			  		if (empty($temp_account)) {
			  			// temp_accountテーブルにemailがなかったら確認メールを送信
			  			$option = array('cost' => 10);
			  			$hashed_pass = password_hash($_POST['password'], PASSWORD_DEFAULT, $option);
			  			$rand_str = mb_substr(base64_encode(bin2hex(openssl_random_pseudo_bytes(32))), 4 , 5);
			  			try {
			  				$sql = 'INSERT INTO temp_account (email,code,password,unixtime) VALUES (:email,:phrase,:pass,:unix)';
							$prepare = $db->prepare($sql);
							$prepare->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
							$prepare->bindValue(':phrase',$rand_str, PDO::PARAM_STR);
							$prepare->bindValue(':pass',$hashed_pass, PDO::PARAM_STR);
							$prepare->bindValue(':unix',time(), PDO::PARAM_STR);
							$prepare->execute();
			  			} catch (PDOException $e) {
			  				echo $view->register_form('Something wrong.');
			  				return true;
			  			}
			  			$header = 'From: ' . mb_encode_mimeheader(EMAIL_FROM) . ' <' . EMAIL_FROM . '>';
			  			$body = EMAIL_BODY.'
			  			You email verification code is '.$rand_str.'
			  			This user control system made by @Xere_yukky. thank you.';
			  			if (mb_send_mail($_POST['email'], EMAIL_TITLE, $body, $header)) {
			  				echo $view->comp($_POST['email'],$hashed_pass);
			  				return true;
			  			}else{echo $view->register_form('Email can not send.');return true;}
			  		}else{echo $view->register_form('A mail is already send.');return true;}
			  	}else{echo $view->register_form('This email is already used');}
			}else{
				echo $view->register_form('Invalid email address or invalid password or invalid csrf_token.');
				return true;
			}
		}else{
			echo $view->register_form();
			return true;
		}
	}
	/* Filter_var
	private function checkemail($email){
		if (preg_match(pattern, $email)) {
			# code...
		}else{
			return false;
		}
	}*/



	private function checkpassword($pass){
		if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/u', $pass)) {
			return true;
		}else{
			return false;
		}
	}
	private function check_temp($email){
		// unixタイムのチェックとブルートフォース攻撃対策を行う関数
		// 時間がれば今度また記述する
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			return true;
		}else{return false;}
	}
	/*
	function __construct(argument)
	{
		# code...
	}
	*/
}

?>
