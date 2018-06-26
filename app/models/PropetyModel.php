<?php

require_once "DB/pdoDbManager.php";
require_once "DB/DAO/PropertiesDAO.php";
require_once "Validation.php";

class PropertyModel {
	private $PropertiesDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	public $modelname;
	private $validationSuite; // contains functions for validating inputs
	
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->PropertiesDAO = new PropertiesDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
		$this->apiResponse = null;
		$this->modelname = "property";
	}
	
	public function getProperties() {
		return ($this->PropertiesDAO->get ());
	}
	public function getProperty($propertyID) {
		if (is_numeric ( $recipeID ))
			return ($this->PropertiesDAO->get ( $propertyID ));
		
		return false;
	}
	/**
	 *
	 * @param array $UserRepresentation:
	 *        	an associative array containing the detail of the new user
	 */
	public function createNewProperty($newProperty) {
		// validation of the values of the new user
		
		// compulsory values
		if (! empty ( $newProperty ["house_type"] )) {
			
			
			if (($this->validationSuite->isLengthStringValid ( $newProperty ["house_type"], TABLE_PROPERTY_TYPE_LENGTH ))) {
				
				
				if ($newId = $this->PropertiesDAO->insert ( $newRecipe ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchPropertiesByCounty ($county) {
		
		if(is_string($region)) 
			return $this->PropertiesDAO->searchByCounty($county);
		return false;
	}
	public function deleteProperty($propertyID) {
		
		//check if user exists
		if(sizeof($this->PropertiesDAO->get($propertyID)) == 1) {
			
			if (! 0 == $this->PropertiesDAO->delete($propertyID))
				return true;	
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	public function __destruct() {
		$this->PropertiesDAO = null;
		$this->dbmanager->closeConnection ();
	}
	
	//check if Parameter is a boolean string
	private function convertToBoolean($value) {
		
		if($value === "true")
			return true;
		if($value === "false")
			return false;
			
		return $value;
	}
}

?>