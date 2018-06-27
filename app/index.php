<?php

require '../vendor/autoload.php';

$app = new \Slim\App; // slim run-time object

require_once "conf/config.inc.php";

//function to authenticate user
// function authenticate() {
	
// 	require_once 'models/UserModel.php';
// 	require_once 'controllers/UserController.php';
// 	require_once 'views/generalView.php';
	
// 	$app = \Slim\Slim::getInstance ();
// 	$headers = $app->request->headers;
	
// 	if(($headers ["user"] != null) && ($headers ["password"] != null))
// 	{
		
// 		$action = ACTION_AUTHENTICATE;
// 		$parameters ["body"] = null;
// 		$parameters ["header"] ["user"] = $headers ["user"]; 
// 		$parameters ["header"] ["password"] = $headers ["password"]; 
		
// 		//create model and controller
// 		$model = new UserModel(); // common model
// 		$controller = new UserController($model, $action, $app, $parameters );
		
// 		//check authentication successful
// 		if($model->successfulAuthentication == true) {
// 			return true;
// 		}
// 		else {
// 			//read header
// 			$encoding = $app->request->getContentType();
			
// 			//write and send response
// 			$view = new GeneralView( $controller, $model, $app, $app->headers ); // common view
// 			$view->output ($encoding); 
// 			$app->stop();
// 		}	
// 	}
// 	else {
// 		$app->stop();
// 	}
// }
$app->get("/", function($request, $response, $args) {
	return $response->withStatus(200)->write('Hello World!');
});

$app->get( "/properties/search/{county}", function ($request, $response, $args) {
	$county = $args['county'];
	$action = null;
	$parameters ["body"] = null;
	$parameters ["SearchingString"] = $county;
	
	if ($county != null) {
		$action = ACTION_SEARCH_PROPERTIES;
	}
	return $response->withStatus(200)->write($county);
});

$app->any( "/properties/{id}", function ($request, $response, $args) {
	$httpMethod = $request->getMethod();
	$propertyID = $args['id'];
	$action = null;
	$parameters["id"] = $propertyID; // prepare parameters to be passed to the controller (example: ID)
	
	//read header
	$encoding = $request->getContentType();
	
	//decode body
	$body = $request->getBody (); // get the body of the HTTP request (from client)
	$parameters ["body"] = decodeBody($body, $encoding, "property");
	
	if (($propertyID == null) or is_numeric ( $propertyID )) {
		if ($request->isGet()){
			if ($propertyID != null)
				$action = ACTION_GET_PROPERTY;
			else
				$action = ACTION_GET_PROPERTIES;
		} elseif ($request->isPost()) {
			$action = ACTION_CREATE_PROPERTY;
		} elseif ($request->isDelete()) {
			$action = ACTION_DELETE_PROPERTY;
		}
	}
	return new loadRunMVCComponents ( "PropertyModel", "PropertyController", "GeneralView", $action, $this, $parameters );
});

// //method for search of users
// $app->map ( "/users/search(/:prefix)", function ($prefix = null) use($app) {
	
// 	$httpMethod = $app->request->getMethod ();
// 	$action = null;
// 	$parameters ["body"] = null;
// 	$parameters ["SearchingString"] = $prefix;
	
// 	if ($prefix != null) {
// 		$action = ACTION_SEARCH_USERS;
// 	}
// 	return new loadRunMVCComponents ( "UserModel", "UserController", "GeneralView", $action, $app, $parameters );
	
// })->via ( "GET" );

// $app->map ( "/users(/:id)", function ($userID = null) use($app) {
	
// 	$httpMethod = $app->request->getMethod ();
// 	$action = null;
// 	$parameters ["id"] = $userID; // prepare parameters to be passed to the controller (example: ID)
	
// 	//read header
// 	$encoding = $app->request->getContentType();
	
// 	//decode body
// 	$body = $app->request->getBody (); // get the body of the HTTP request (from client)
// 	$parameters ["body"] = decodeBody($body, $encoding, "user");
	
	
// 	if (($userID == null) or is_numeric ( $userID )) {
// 		switch ($httpMethod) {
// 			case "GET" :
// 				if ($userID != null)
// 					$action = ACTION_GET_USER;
// 				else
// 					$action = ACTION_GET_USERS;
// 				break;
// 			case "POST" :
// 				$action = ACTION_CREATE_USER;
// 				break;
// 			case "PUT" :
// 				$action = ACTION_UPDATE_USER;
// 				break;
// 			case "DELETE" :
// 				$action = ACTION_DELETE_USER;
// 				break;
// 			default :
// 		}
// 	}
	
// 	return new loadRunMVCComponents ( "UserModel", "UserController", "GeneralView", $action, $app, $encoding, $parameters);
// } )->via ( "GET", "POST", "PUT", "DELETE" );

$app->run ();
class loadRunMVCComponents {
	public $model, $controller, $view;
	public function __construct($modelName, $controllerName, $viewName, $action, $app, $encoding = null, $parameters = null) {
		include_once "models/" . $modelName . ".php";
		include_once "controllers/" . $controllerName . ".php";
		include_once "views/" . $viewName . ".php";
		
		$model = new $modelName (); // common model
		$controller = new $controllerName ( $model, $action, $app, $parameters );
		$view = new $viewName ( $controller, $model, $app, $app->headers ); // common view
		$view->output ($encoding); // this returns the response to the requesting client
	}
}

function decodeBody ($body, $encoding, $resource) {
	
	//check encoding
	if($encoding == ENCODING_JSON) {
		return json_decode ( $body, true );
	}
	
	// decode xml
	if($encoding == ENCODING_XML) {
		return decodeXmlBody($body, $resource);
	}
	
	return false;
}

function decodeXmlBody($body, $resource) {
	
	$info = null;
	
	//body not empty
	if(! empty($body)) {
		$parser = xml_parser_create();
	    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	    xml_parse_into_struct($parser, $body, $values, $tags);
	    xml_parser_free($parser);
	    
	
	    // loop through the structures
	    foreach ($tags as $key=>$val) {
	        if ($key == $resource) {
	            $feature = $val;
	            // each contiguous pair of array entries are the 
	            // lower and upper range for each user definition
	            for ($i=0; $i < count($feature); $i+=2) {
	                $offset = $feature[$i] + 1;
	                $len = $feature[$i + 1] - $offset;
	                
	                $tdb = array_slice($values, $offset, $len);
	                //add data of each tag to an associative array
	            	for ($i=0; $i < count($tdb); $i++) {
	            		
				        $info[$tdb[$i]["tag"]] = $tdb[$i]["value"];
				    }
	            }
	        } else {
	            continue;
	        }
	    }
	}
    return $info;
}


?>