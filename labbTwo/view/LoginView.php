<?php

require_once("./model/LoginModel.php");

class LoginView {

    //Declare variables.
	private $loginModel;
	private static $username = "UserName";
	private static $password = "Password";
	private $errorMessage;
	private $inlogmessage;
    private $pass;
    private $cookieTextFile;
    private $fileName = "cookieTime.txt";
    private $userInput;


	public function __construct() {
		$this->loginModel = new LoginModel();
		$this->inlogmessage = "";
	}
	
	public function setErrorMessage($errorMessage) {
		$this->errorMessage = $errorMessage;
	}

    public function setPreviousUserInput($userInput) {
        $this->userInput = $userInput;
    }

    //Start page.
	public function show() {
		$ret ="<h1>Laborationskod sh222td</h1>
		<h2>Ej inloggad</h2>
		<form method='POST' Action='?login'>
			<fieldset>

				<legend>Login - Skriv in användarnamn och lösenord</legend>
				<p>$this->errorMessage</p>
				<label for='UserNameID'>Användarnamn : </label>
				<input type='text' size='20' name='".self::$username."' value='".$this->userInput ."' id='UserNameID' />
				<label for='PasswordID'>Lösenord : </label>
				<input type='password' size='20' name='".self::$password."' id='PasswordID' />
				<label for='LoginKeeper'>Håll mig inloggad : </label>
				<input type='checkbox' name='loginKeeper' id='LoginKeeper'/>
				<input type='submit' name='sendButton' value='Logga in'>
			</fieldset>		
		</form>";

		return $ret;
	}

    //Display of the logged in view.
	public function loggedinView() {
		$ret ="<h1>Laboratonskod sh222td</h1>
		<form method='POST' Action='?logout'>
			<h2>Admin är inloggad</h2>
			<p>$this->inlogmessage</p>
			<input type='submit' name='logoutButton' value='Logga ut'>
		</form>";
		return $ret;
	}

	function didUserPressLoginButton() {
		if (isset($_POST["sendButton"])) {
			return true;
		}
	}


	
	public function getUserName(){
		return  $_POST[self::$username];
	}
	public function getPassword() {
		return $_POST[self::$password];
	}
	
	public function setLoginMSG() {
		$this->inlogmessage = "Inloggning lyckades";
	}
	
	public function setSavedLoginMSG() {
		$this->inlogmessage = "Inloggning lyckades och vi kommer ihåg dig till nästa gång";
	}
	
	public function setSavedCookieMSG() {
		$this->inlogmessage = "Inloggning lyckades via cookies";
	}

    public function setLoggedOutMSG() {
        $this->errorMessage = "Du har nu loggats ut";
    }
	
	public function didUserPressLoginKeeperButton() {
		if (isset($_POST["loginKeeper"])) {
			return true;
		}
	}

    //Creates a cookie with a hashed password.
	public function createCookie() {
        $this->pass = $this->loginModel->loginPassword;
		$hash = password_hash($this->pass, PASSWORD_BCRYPT);

		if (isset($_POST["loginKeeper"])) {

			setcookie('username', $_POST[self::$username], time()+600);
			setcookie('password', $hash, time()+600);
		}
	}

    //Checks if the cookies values are set.
	public function checkCookie() {
		if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		    return true;
		}
	}

    //Checks first if the cookie time has changed, then checks if the password cookie still is correct.
	public function checkCookieIfChanged() {
        $this->cookieTextFile = file_get_contents($this->fileName);
        if ($this->cookieTextFile > time()) {
            $this->pass = $this->loginModel->loginPassword;
            if (password_verify($this->pass, $_COOKIE['password'])) {
                return false;
            }
        }return true;
	}
	
	public function setcookieErrorMSG() {
		$this->errorMessage = "Kakan har blivit manipulerad!";
	}
	
	public function createCookieFile() {
		$cookieTime = time()+600;
		file_put_contents($this->fileName, $cookieTime);
	}
	
	function didUserPressLogoutButton() {
		if (isset($_POST["logoutButton"])) {
			return true;
		}	
	}

	public function killEverything() {
        $pass = $this->loginModel->loginPassword;
        $hash = password_hash($pass, PASSWORD_BCRYPT);

		setcookie('username', $_COOKIE['username'], time()-600);
		setcookie('password', $hash, time()-600);
		unset($_SESSION['login']);
	}

    public function requireAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

}