<?php
require_once("./view/LoginView.php");
require_once("./view/HTMLview.php");
require_once './model/LoginModel.php';

class LoginController {

    //Declare variables.
	private $loginModel;
	private $username;
	private $password;
	private $htmlView;
	private $loginView;
	private $errorMessage;
	
	public function __construct(){
		$this->htmlView = new HTMLView();
		$this->loginView = new LoginView();
		$this->loginModel = new LoginModel();
	}
	
	public function checkLogin() {
		$this->username = $this->loginView->getUserName();
		$this->password = $this->loginView->getPassword();
        $agent = $this->loginView->requireAgent();
		$this->loginModel->checkLogin($this->username, $this->password, $agent);
		$this->errorMessageHandler();
	}

	public function errorMessageHandler() {
		$this->errorMessage = $this->loginModel->getErrorMessage();
		$this->loginView->setErrorMessage($this->errorMessage);
	}

	public function startController () {
        //Checks if user wanted to log out with either cookies or not.
		if($this->loginView->didUserPressLogoutButton()) {
            if ($this->loginView->checkCookie()) {
                $this->loginView->setLoggedOutMSG();
                $this->loginView->killEverything();
                $loginhtml = $this->loginView->show();
                return $this->htmlView->echoHTML($loginhtml);
            } else {
                $this->loginView->setLoggedOutMSG();
                $this->loginModel->killSession();
                $loginhtml = $this->loginView->show();
                return $this->htmlView->echoHTML($loginhtml);
            }
		}

        //Checks if a session exists and returns the logged in view.
		if ($this->loginModel->isSessionSet()) {
            $agent = $this->loginView->requireAgent();
            if($agent === $this->loginModel->getAgent()) {
                return $this->htmlView->echoHTML($this->loginView->loggedinView());
            } else {
                $loginhtml = $this->loginView->show();
                return $this->htmlView->echoHTML($loginhtml);
            }

		}

        //Checks if there is a cookie and if it has been manipulated.
        if($this->loginView->checkCookie()){
            if ($this->loginView->checkCookieIfChanged()) {
                $this->loginView->setcookieErrorMSG();
                $this->loginView->killEverything();
                $loginhtml = $this->loginView->show();
                return $this->htmlView->echoHTML($loginhtml);
            } else {
                $this->loginView->setSavedCookieMSG();
                $this->loginModel->setSession();
                return $this->htmlView->echoHTML($this->loginView->loggedinView());
            }
        }

        //Checks if the user wanted to log in with or without cookies.
		if($this->loginView->didUserPressLoginButton()) {
			$this->checkLogin();
			if ($this->loginModel->authenticateUser($this->username, $this->password)) {
				if ($this->loginView->didUserPressLoginKeeperButton()) {
					$this->loginModel->setSession();
					$this->loginView->createCookie();
					$this->loginView->createCookieFile();
                    $agent = $this->loginView->requireAgent();
                    $this->loginModel->setAgent($agent);
					$this->isSavedValidUser();
				} else {
					$this->loginModel->setSession();
                    $agent = $this->loginView->requireAgent();
                    $this->loginModel->setAgent($agent);
					$this->isValidUser();
				}
			}
		}

        //Checks if there isn't a session, then it redirects to the main page.
		if (!$this->loginModel->isSessionSet()) {
			$loginhtml = $this->loginView->show();
			return $this->htmlView->echoHTML($loginhtml);
		}
	}
	
	public function isValidUser() {
		$this->loginView->setLoginMSG();
		return $this->htmlView->echoHTML($this->loginView->loggedinView());
	}
	
	public function isSavedValidUser() {
		$this->loginView->setSavedLoginMSG();
		return $this->htmlView->echoHTML($this->loginView->loggedinView());
	}
}
