<?php

class PropertiesDAO {
	private $dbManager;
	function PropertiesDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function get($id = null) {
		//TODO: change to work for properties
		$sql = "SELECT * ";
		$sql .= "FROM properties ";
		if ($id != null)
			$sql .= "WHERE properties.id=? ";
		$sql .= "ORDER BY properties.name ";
		
		// $stmt = $this->dbManager->prepareQuery ( $sql );
		// $this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		// $this->dbManager->executeQuery ( $stmt );
		// $rows = $this->dbManager->fetchResults ( $stmt );
		
		return [];//($rows);
	}
	public function insert($parametersArray) {
		
		//TODO: change to add a property
		
		//convert bool to tinyint (as set in database)
		$vegetarian = $this->convertBoolToInt($parametersArray ["vegetarian"]);
		$savoury = $this->convertBoolToInt($parametersArray ["savoury"]);
		
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO properties (name, vegetarian, savoury, fk_cookbook_id) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		// $stmt = $this->dbManager->prepareQuery ( $sql );
		// $this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		// $this->dbManager->bindValue ( $stmt, 2, $vegetarian, $this->dbManager->INT_TYPE );
		// $this->dbManager->bindValue ( $stmt, 3, $savoury, $this->dbManager->INT_TYPE );
		// $this->dbManager->bindValue ( $stmt, 4, $parametersArray ["cookbook"], $this->dbManager->INT_TYPE );
		// $this->dbManager->executeQuery ( $stmt );
		
		return 0; //($this->dbManager->getLastInsertedID ());
	}
	
	public function delete($userID) {
		
		//TODO: change to work for properties table
		
		//prepare Statement
		$sql = "DELETE FROM properties WHERE id=?";
		// $stmt = $this->dbManager->prepareQuery($sql);
		
		// //bind values
		// $this->dbManager->bindValue($stmt, 1, $userID, $this->dbManager->INT_TYPE );		
		
		// //execute the query
		// $this->dbManager->executeQuery($stmt);
		// return ($this->dbManager->getNumberOfAffectedRows ($stmt));
		return null;
	}
	
	public function searchByCounty($countyStr) {
		
		//TODO: change to work for seearch by county

		//prepare Statement
		$sql = "SELECT * FROM properties WHERE name LIKE ? ";
		$sql .= "ORDER BY properties.name ; ";
		// $stmt = $this->dbManager->prepareQuery($sql);
		
		// //bind values
		// $this->dbManager->bindValue($stmt, 1, "%$countyStr%", $this->dbManager->STRING_TYPE );
		
		// //execute the query
		// $this->dbManager->executeQuery($stmt);
		// $rows = $this->dbManager->fetchResults ( $stmt );
		
		return []; //($rows);
	}
	
	private function convertBoolToInt ($bool) {
		
		if ($bool)
			return 1;
		else return 0;
		
	}
}
?>
