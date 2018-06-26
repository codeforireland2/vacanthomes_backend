<?php

require_once "DB/pdoDbManager.php";
require_once "DB/DAO/UsersDAO.php";
require_once "Validation.php";


class UserModel {
	
	public $apiResponse; // api response
	public $successfulAuthentication; //authentication variable
	public $modelname;
	private $validationSuite; // contains functions for validating inputs
	private $UsersDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	
	
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->UsersDAO = new UsersDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
		$this->apiResponse = null;
		$this->successfulAuthentication = null;
		$this->modelname = "user";
	}
	
	public function getUsers() {
		return ($this->UsersDAO->get ());
	}
	
	public function getUser($userID) {
		if (is_numeric ( $userID ))
			return ($this->UsersDAO->get ( $userID ));
		
		return false;
	}
	
	/**
	 *
	 * @param array $UserRepresentation:
	 *        	an associative array containing the detail of the new user
	 */
	public function createNewUser($newUser) {
		// validation of the values of the new user
		
		// compulsory values
		if (! empty ( $newUser ["name"] ) && ! empty ( $newUser ["surname"] ) && ! empty ( $newUser ["email"] ) && ! empty ( $newUser ["password"] )) {
			/*
			 * the model knows the representation of a user in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
			 */
			
			//convert password into sha1 string
			$newUser["password"] = sha1($newUser["password"]);
			
			if (($this->validationSuite->isLengthStringValid ( $newUser ["name"], TABLE_USER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["surname"], TABLE_USER_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["email"], TABLE_USER_EMAIL_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["password"], TABLE_USER_PASSWORD_LENGTH ))) {
				if ($newId = $this->UsersDAO->insert ( $newUser ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function updateUsers($userID, $userNewRepresentation) {
		
		//check if user exists
		if(sizeof($this->UsersDAO->get($userID)) == 1) {
			
			// compulsory values
			if (! empty ( $userNewRepresentation ["name"] ) && ! empty ( $userNewRepresentation ["surname"] ) && ! empty ( $userNewRepresentation ["email"] ) && ! empty ( $userNewRepresentation ["password"] )) {
				/*
				 * the model knows the representation of a user in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
				 */
				
				if (($this->validationSuite->isLengthStringValid ( $userNewRepresentation ["name"], TABLE_USER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation ["surname"], TABLE_USER_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation ["email"], TABLE_USER_EMAIL_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation ["password"], TABLE_USER_PASSWORD_LENGTH ))) {
					if (! 0 == $this->UsersDAO->update ( $userNewRepresentation, $userID ))
						return true;
				}
			}	
		}
		
		// if validation fails or insertion fails
			return (false);
	}
	
	public function searchUsers($string) {
		
		if(is_string($string)) 
			return $this->UsersDAO->search($string);
		return false;
	}
	
	public function deleteUser($userID) {
		
		//check if user exists
		if(sizeof($this->UsersDAO->get($userID)) == 1) {
			
			if (! 0 == $this->UsersDAO->delete($userID))
				return true;	
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	

	public function authenticateUsers ($headers) {
		
		$headers["password"] = sha1($headers["password"]);
		
		if (($this->validationSuite->isLengthStringValid ( $headers ["user"], TABLE_USER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $headers ["password"], TABLE_USER_PASSWORD_LENGTH ))) {
			return $this->UsersDAO->authenticateUser($headers);
		}
		return false;
	}
	
	public function __destruct() {
		$this->UsersDAO = null;
		$this->dbmanager->closeConnection ();
	}
	
}
?>