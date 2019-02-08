<?php

/**
 * This class is HTML write class but you must not do remove csrf() function because it protect csrf attack
 */
require 'config/define.php';
class HTML
{
	function login_form($dir,$error = null)
	{
		// Please edit this html code
		if ($error == null) {
			$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>ログイン</title>

</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">LOGIN</h1>
<form action="'.URL.$dir.'" method="post">
        <dl>
          <dt><label for="email">メールアドレス：</label></dt>
          <dd><input type="text" name="email" id="email" value=""></dd>
        </dl>
    <dl>
      <dt><label for="password">パスワード：</label></dt>
      <dd><input type="password" name="password" id="password" value=""></dd>
    </dl>
'.$this->csrf().'
    <input type="submit" name="submit" id="submit" value="login">
  </form>
<p><a href="'.REGISTER.'">You need new account?</a></p>
</div>
</body>
</div>
</html>
		';
		}else{
		$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>ログイン</title>

</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">LOGIN</h1>
  <p style="color:red;">'.htmlspecialchars($error).'</p>
<form action="'.URL.$dir.'" method="post">
        <dl>
          <dt><label for="email">メールアドレス：</label></dt>
          <dd><input type="text" name="email" id="email" value=""></dd>
        </dl>
    <dl>
      <dt><label for="password">パスワード：</label></dt>
      <dd><input type="password" name="password" id="password" value=""></dd>
    </dl>
'.$this->csrf().'
    <input type="submit" name="submit" id="submit" value="login">
  </form>
<p><a href="'.REGISTER.'">You need new account?</a></p>
</div>
</body>
</div>
</html>
		';
		}
		return $html;
	}
	function register_form($error = null)
	{
		// Please edit this html code
		if ($error == null) {
		$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>登録</title>

</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">Register</h1>
<form action="'.REGISTER.'" method="post">
        <dl>
          <dt><label for="email">メールアドレス：</label></dt>
          <dd><input type="text" name="email" id="email" value=""></dd>
        </dl>
    <dl>
      <dt><label for="password">パスワード：</label></dt>
      <dd><input type="password" name="password" id="password" value=""></dd>
    </dl>
'.$this->csrf().'
    <input type="submit" name="submit" id="submit" value="register">
  </form>
<p><a href="'.LOGIN.'">You need new account?</a></p>
</div>
</body>
</div>
</html>
		';
		}else{
		$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>登録</title>

</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">Register</h1>
  <p style="color:red;">'.htmlspecialchars($error).'</p>
<form action="'.REGISTER.'" method="post">
        <dl>
          <dt><label for="email">メールアドレス：</label></dt>
          <dd><input type="text" name="email" id="email" value=""></dd>
        </dl>
    <dl>
      <dt><label for="password">パスワード：</label></dt>
      <dd><input type="password" name="password" id="password" value=""></dd>
    </dl>
'.$this->csrf().'
    <input type="submit" name="submit" id="submit" value="register">
  </form>
<p><a href="'.LOGIN.'">You need new account?</a></p>
</div>
</body>
</div>
</html>
		';
		}

		return $html;
	}
		function comp($email,$hashed_pass,$error = null)
	{
		// Please edit this html code
		if ($error == null) {
			$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>登録</title>
</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">Email send.<br>Please check Box and Junkbox.</h1>
  <h3>If you have code,type here.</h3>
  <form action="'.REGISTER.'" method="post">
        <dl>
          <dt><label for="email">Verification code：</label></dt>
          <dd><input type="text" name="code" id="code" value=""></dd>
        </dl>
'.$this->csrf().'
<input type="hidden" name="email" id="email" value="'.$email.'">
<input type="hidden" name="password" id="password" value="'.$hashed_pass.'">
    <input type="submit" name="confirm" id="submit" value="confirm">
  </form>
</div>
</body>
</div>
</html>
		';

		}else{
			$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>登録</title>
</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">Email send.<br>Please check Box and Junkbox.</h1>
  <p style="color:red;">'.htmlspecialchars($error).'</p>
  <h3>If you have code,type here.</h3>
  <form action="'.REGISTER.'" method="post">
        <dl>
          <dt><label for="email">Verification code：</label></dt>
          <dd><input type="text" name="code" id="code" value=""></dd>
        </dl>
'.$this->csrf().'
<input type="hidden" name="email" id="email" value="'.$email.'">
<input type="hidden" name="password" id="password" value="'.$hashed_pass.'">
    <input type="submit" name="confirm" id="submit" value="confirm">
  </form>
</div>
</body>
</div>
</html>
		';

		}
		return $html;
	}
	function gj()
	{
		// Please edit this html code
		$html = '
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!--<link href="stylesheet url write here" rel="stylesheet">-->
<title>GoodJob</title>
</head>
<div class="page">
<body>
<div class"main">
  <h1 class="welecome">GoodJob boy.<br>complet to create account! Have fun!</h1>
  <h3>Login <a href="'.PANEL.'">here</a></h3>
</div>
</body>
</div>
</html>
		';
		return $html;
	}
		private function csrf(){

		$_SESSION['csrf_token'] = rtrim(base64_encode(bin2hex(openssl_random_pseudo_bytes(32))), '==');
		return '
		<input type="hidden" name="csrf_token" id="csrf_token" value="'.$_SESSION['csrf_token'].'">
		';
		}
/*

	function __construct(argument)
	{
		# code...
	}

*/
}
?>