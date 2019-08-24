<?php
    // required headers
    header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-jwt/");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Method, Authorization, X-Requested-With");
    
    // files needed to connect to database
    include_once '../../config/Database.php';
    include_once '../../objects/User.php';
    
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // instantiate product object
    $user = new User($db);
    
    // get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    // make sure data is not empty
    if(! empty($data->firstname)
       && ! empty($data->lastname) 
       && ! empty($data->email) 
       && ! empty($data->password) 
    ) { // set product property values
        $user->firstname  =  $data->firstname;
        $user->lastname   =  $data->lastname;
        $user->email      =  $data->email;
        $user->password   =  $data->password;
    
        // Create the user
        if ($user->create()) {
        
            // set response code - 201 created
            http_response_code(201);
        
            // display message: user was created
            echo json_encode(
                array("message" => "User Created.")
            );
        } else { // if unable to create the user, tell the user
        
            // set response code - 503 service unavailable
            http_response_code(503);
        
            // display message: unable to create user
            echo json_encode(
                array("message" => "User Not Created.")
            );
        }
    } else {// tell the user data is incomplete
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(
            array("message" => "User Not Created. Incomplete Data.")
        );
    }
