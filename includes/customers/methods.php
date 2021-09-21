<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// include '../includes/DbOperations.php';


/*
endpoint: createcustomer
parameters: name, passport_number, image, passport, license
method: POST
table: customers
*/

$app->post('/createcustomer', function(Request $request, Response $response){

if(!haveEmptyParameters(array('name', 'passport_number', 'image', 'passport', 'license'), $request, $response)){

    $request_data = $request->getParsedBody(); 

    $name = $request_data['name'];
    $passportNumber = $request_data['passport_number'];
    $image = $request_data['image'];
    $passport = $request_data['passport'];
    $license = $request_data['license'];

    $db = new DbOperations; 

    $result = $db->createCustomer($name, $passportNumber, $image, $passport, $license);
    
    if($result == CUSTOMER_CREATED){

        $message = array(); 
        $message['error'] = false; 
        $message['message'] = 'Customer created successfully';

        $response->write(json_encode($message));

        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(201);

    }else if($result == CUSTOMER_FAILURE){

        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Some error occurred';

        $response->write(json_encode($message));

        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);    

    }else if($result == CUSTOMER_EXISTS){
        $message = array(); 
        $message['error'] = true; 
        $message['message'] = 'Customer Already Exists';

        $response->write(json_encode($message));

        return $response
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(422);    
    }
}
return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(422);    
});

/* 
endpoint: allcustomer
method: GET
table: customers
*/
$app->get('/allcustomers', function(Request $request, Response $response){

$db = new DbOperations; 

$customers = $db->getAllCustomers();

$response_data = array();

$response_data['error'] = false; 
$response_data['customers'] = $customers; 

$response->write(json_encode($response_data));

return $response
->withHeader('Content-type', 'application/json')
->withStatus(200);  

});

/* 
endpoint: updatecustomer
parameters: name, passport_number, image, passport, license
method: PUT
table: customers
*/
$app->put('/updatecustomer/{id}', function(Request $request, Response $response, array $args){

$id = $args['id'];

if(!haveEmptyParameters(array('name', 'passport_number', 'image', 'passport', 'license'), $request, $response)){

    $request_data = $request->getParsedBody(); 

    $name = $request_data['name'];
    $passportNumber = $request_data['passport_number'];
    $image = $request_data['image'];
    $passport = $request_data['passport'];
    $license = $request_data['license']; 


    $db = new DbOperations; 

    if($db->updateCustomer($name, $passportNumber, $image, $passport, $license,$id)){
        $response_data = array(); 
        $response_data['error'] = false; 
        $response_data['message'] = 'Customer Updated Successfully';
        $car = $db->getCustomerById($id);
        $response_data['customer'] = $car; 

        $response->write(json_encode($response_data));

        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
    
    }else{
        $response_data = array(); 
        $response_data['error'] = true; 
        $response_data['message'] = 'Please try again later';
        $car = $db->getCustomerById($id);
        $response_data['customer'] = $car; 

        $response->write(json_encode($response_data));

        return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);  
          
    }

}

return $response
->withHeader('Content-type', 'application/json')
->withStatus(200);  

});

/* 
endpoint: deletecustomer
method: DELETE
table: customers
*/
$app->delete('/deletecustomer/{id}', function(Request $request, Response $response, array $args){
$id = $args['id'];

$db = new DbOperations; 

$response_data = array();

if($db->deleteCustomer($id)){
    $response_data['error'] = false; 
    $response_data['message'] = 'Customer has been deleted';    
}else{
    $response_data['error'] = true; 
    $response_data['message'] = 'Plase try again later';
}

$response->write(json_encode($response_data));

return $response
->withHeader('Content-type', 'application/json')
->withStatus(200);
});

