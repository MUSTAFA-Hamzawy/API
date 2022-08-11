<?php

require "Entities/user.php";


// url --> domain.topLevelDomain/test-api/version/entity/method/parameters

$urlInfo = explode('/', trim($_SERVER['QUERY_STRING']));

// assume the data is validated
$version = $urlInfo[1];
$entity  = $urlInfo[2];
$method  = $urlInfo[3];

// some important headers for postman
header("Access-Control-Allow-Origin: application/json");
header("Content-type: application/json");

if ($version == "v1")
{
    if ($entity == "user")
    {
        $user = new user();
        switch ($method)
        {
            case "getAll" :
                header("Access-Control-Allow-Methods: GET");
                $info = $user->getAllData();
                http_response_code(200);        // set the status code in the postman
                $response = [
                    'status code' => 200,
                    'users data' => $info
                ];

                $jsonData = json_encode($response);
                echo $jsonData;
                break;
            case "add":
                header("Access-Control-Allow-Methods: POST");
                $sentData = file_get_contents("php://input"); // this data is sent from the post man in json format
                $data = json_decode($sentData);
                $user->add("employee", $data);
                http_response_code(201);        // set the status code in the postman
                $response = [   // we can create another response with error code 400 if the add is failed
                    'status code' => 201,
                    'msg' => "Successfully added"
                ];
                echo $response;
                break;

            case "update":
                header("Access-Control-Allow-Methods: PUT");
                $newData = file_get_contents("php://input");
                $newData = json_decode($newData, TRUE);
                $user->update("employee", $newData, ["Employee_id" => $newData['id']]);
                http_response_code(201);        // set the status code in the postman
                $response = [           // we can create another response with error code 400 if the update is failed
                    'status code' => 201,
                    'msg' => "Successfully updated"
                ];
                echo $response;
                break;

            case "delete":
                /** we can get the id form postman as we did in add and update, but it is simple to take it from parameters of QUERY_STRING for url */

                header("Access-Control-Allow-Methods: DELETE");
                $id = $urlInfo[4];
                $user->delete($id);
                http_response_code(201);        // set the status code in the postman
                $response = [           // we can create another response with error code 400 if the update is failed
                    'status code' => 201,
                    'msg' => "Successfully removed"
                ];
                echo $response;
                break;
        }
    }
}

