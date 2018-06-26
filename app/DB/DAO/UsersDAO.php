<?php

/**
 * @author Luca
 * definition of the User DAO (database access object)
 */

class UsersDAO {
	
	private $dbManager;
	
	
	function UsersDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM users ";
		if ($id != null)
			$sql .= "WHERE users.id=? ";
		$sql .= "ORDER BY users.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO users (name, surname, email, password) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["email"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["password"], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	
	public function update($parametersArray, $userID) {
		
		// update assumes that all the required parameters are defined and set
		$sql = "UPDATE users SET name=?, surname =?, email=?, password=? WHERE id=?";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["email"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["password"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 5, $userID, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getNumberOfAffectedRows ($stmt));
	}
	
	public function delete($userID) {
		
		//prepare Statement
		$sql = "DELETE FROM users WHERE users.id=?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		//bind values
		$this->dbManager->bindValue($stmt, 1, $userID, $this->dbManager->INT_TYPE );		
		
		//execute the query
		$this->dbManager->executeQuery($stmt);
		return ($this->dbManager->getNumberOfAffectedRows ($stmt));
		
	}
	
	public function search($str) {

		//prepare Statement
		$sql = "SELECT * FROM users WHERE users.name LIKE ? OR users.surname LIKE ? OR users.email LIKE ? ";
		$sql .= "ORDER BY users.name ; ";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		//bind values
		$this->dbManager->bindValue($stmt, 1, "%$str%", $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 2, "%$str%", $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 3, "%$str%", $this->dbManager->STRING_TYPE );
		
		//execute the query
		$this->dbManager->executeQuery($stmt);
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function authenticateUser($headers) {
		
		//prepare Statement
		$sql = "SELECT 1 FROM users WHERE users.name = ? AND users.password = ? ";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		//bind values
		$this->dbManager->bindValue($stmt, 1, $headers ["user"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 2, $headers ["password"], $this->dbManager->STRING_TYPE );
		
		//execute the query
		$this->dbManager->executeQuery($stmt);
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
}
?>
