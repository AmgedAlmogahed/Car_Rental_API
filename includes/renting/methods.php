<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// require '../includes/DbOperations.php';


/* 
    endpoint: createrent
    parameters: car_id , customer_id , start_date, end_date, price, move_agent, details
    method: POST
    table: renting
*/
$app->post('/createrent', function(Request $request, Response $response){

    if(!haveEmptyParameters(array('car_id' , 'customer_id' , 'start_date', 'end_date', 'price', 'move_agent', 'details'), $request, $response)){

        $request_data = $request->getParsedBody(); 

        $carId = $request_data['car_id'];
        $customerId = $request_data['customer_id'];
        $startDate = $request_data['start_date'];
        $endDate = $request_data['end_date'];
        $price = $request_data['price'];
        $moveAgent = $request_data['move_agent'];
        $details = $request_data['details'];

        $db = new DbOperations; 

        $result = $db->createRent($carId , $customerId , $startDate, $endDate, $price, $moveAgent, $details);
        
        if($result){
            $response_data = array(); 
            $response_data['error'] = false; 
            $response_data['message'] = 'Rent added Successfully';

            $response->write(json_encode($response_data));

            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
                
        }else{
            $response_data = array(); 
            $response_data['error'] = true; 
            $response_data['message'] = 'Please try again later';

            $response->write(json_encode($response_data));

            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
              
    }

}
});

/* 
    endpoint: allrents
    method: GET
    table: renting
*/
$app->get('/allrents', function(Request $request, Response $response){

    $db = new DbOperations; 

    $services = $db->getAllRents();

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['renting'] = $services; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  

});

/* 
    endpoint: updaterent
    parameters: end_date, price
    method: PUT
    table: renting
*/
$app->put('/updaterent/{id}', function(Request $request, Response $response, array $args){

    $id = $args['id'];

    if(!haveEmptyParameters(array('end_date', 'price'), $request, $response)){

        $request_data = $request->getParsedBody(); 

        $endDate = $request_data['end_date'];
        $price = $request_data['price'];
     

        $db = new DbOperations; 

        if($db->updateRent($endDate, $price, $id)){
            $response_data = array(); 
            $response_data['error'] = false; 
            $response_data['message'] = 'Rent Updated Successfully';
            $Rent = $db->getRentById($id);
            $response_data['renting'] = $Rent; 

            $response->write(json_encode($response_data));

            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
        
        }else{
            $response_data = array(); 
            $response_data['error'] = true; 
            $response_data['message'] = 'Please try again later';
            $service = $db->getRentById($id);
            $response_data['service'] = $service; 

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
$app->delete('/deleterent/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $db = new DbOperations; 

    $response_data = array();

    if($db->deleteRent($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'Rent has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});
