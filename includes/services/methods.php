<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// require '../includes/DbOperations.php';


/* 
    endpoint: createservice
    parameters: carId, name, prcie, date
    method: POST
    table: service
*/
$app->post('/createservice', function(Request $request, Response $response){

    if(!haveEmptyParameters(array('car_id', 'name', 'price', 'date'), $request, $response)){

        $request_data = $request->getParsedBody(); 

        $carId = $request_data['car_id'];
        $name = $request_data['name'];
        $prcie = $request_data['price'];
        $date = $request_data['date'];

        $db = new DbOperations; 

        $result = $db->createService($carId, $name, $prcie, $date);
        
        if($result){
            $response_data = array(); 
            $response_data['error'] = false; 
            $response_data['message'] = 'Service added Successfully';

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
    endpoint: allservices
    method: GET
    table: services
*/
$app->get('/allservices', function(Request $request, Response $response){

    $db = new DbOperations; 

    $services = $db->getAllServices();

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['services'] = $services; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  

});

/* 
    endpoint: updateservice
    parameters: 'name', 'price', 'date'
    method: PUT
    table: services
*/
$app->put('/updateservice/{id}', function(Request $request, Response $response, array $args){

    $id = $args['id'];

    if(!haveEmptyParameters(array('name', 'price', 'date'), $request, $response)){

        $request_data = $request->getParsedBody(); 

        $name = $request_data['name'];
        $price = $request_data['price'];
        $date = $request_data['date'];

        $db = new DbOperations; 

        if($db->updateService($name,$price, $date, $id)){
            $response_data = array(); 
            $response_data['error'] = false; 
            $response_data['message'] = 'Service Updated Successfully';
            $service = $db->getServiceById($id);
            $response_data['service'] = $service; 

            $response->write(json_encode($response_data));

            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
        
        }else{
            $response_data = array(); 
            $response_data['error'] = true; 
            $response_data['message'] = 'Please try again later';
            $service = $db->getServiceById($id);
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
$app->delete('/deleteservice/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $db = new DbOperations; 

    $response_data = array();

    if($db->deleteService($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'Service has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});
