<?php

class LoginModel {
	
	//Declare variables.
	public $loginName = "Admin";
	public $loginPassword = "Password";
	private $username;
	private $password;
	private $errorMessage = "";
	
	public function setSession() {
		$_SESSION['login'] = true;
	}

    public function setAgent($agent) {
        $_SESSION['agent'] = $agent;
    }

    public function getAgent() {
        if (isset($_SESSION['agent'])) {
            return $_SESSION['agent'];
        }
    }

	public function isSessionSet() {
		if (isset($_SESSION['login'])) {
			return true;
		}
		return false;
	}

	public function checkLogin($username, $password, $agent){
		$this->username = $username;
		$this->password = $password;

        if($agent !== $this->getAgent()){
            $this->killSession();
        }
		 
		 //Checks if user has written username or not.
		 if($this->username == "" ) {
		 	 $this->errorMessage = "Fyll i användarnamn";
				return true;
		 }
		 //Checks if user has written password or not.
		 else if ($password == "") {
		 	$this->errorMessage = "Fyll i lösenord";
			 return true;
		 }
		 //Checks if user has written wrong password.
		 else if ($username == $this->loginName && $password != $this->loginPassword) {
		 	$this->errorMessage = "Felaktigt användarnamn och/eller lösenord";
			 return true;
		 }
		 //Checks if user has written wrong username.
		 else if ($username != $this->loginName && $password == $this->loginPassword) {
		 	$this->errorMessage = "Felaktigt användarnamn och/eller lösenord";
			 return true;
		 }
		 //Checks if user has written the correct input.
		 else if($username == $this->loginName && $password == $this->loginPassword){
		 	return true;
		 } else {
		 $this->errorMessage = "Fel inloggningsuppgifter";
		 return false;
		 }	  
	}

    //Checks if it's the right user.
	public function authenticateUser($username, $password) {
		if ($username == $this->loginName && $password == $this->loginPassword) {
			return true;	
		}
	}
	
	//Returns the current active errormessage.
	public function getErrorMessage() {
		return $this->errorMessage;
	}

    public function killSession() {
        unset($_SESSION['login']);
    }
}
