<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// require '../includes/DbOperations.php';

/* 
    endpoint: createcar
    parameters: name, plat_nmber, base_number, model, type, min_rent, color, status, image
    method: POST
    table: car
*/
$app->post('/createcar', function(Request $request, Response $response){
    if (!haveEmptyParameters(array('name', 'plat_number', 'base_number', 'model', 'type', 'min_rent', 'color', 'status', 'image'), $request, $response)) {
        
        $request_data = $request->getParsedBody(); 

        $name = $request_data['name'];
        $plat_nmber = $request_data['plat_number'];
        $base_number = $request_data['base_number'];
        $model = $request_data['model'];
        $type = $request_data['type'];
        $min_rent = $request_data['min_rent'];
        $color = $request_data['color'];
        $status = $request_data['status'];
        $image = $request_data['image'];

        $db = new DbOperations;

        $result = $db->createCar($name, $plat_nmber, $base_number, $model, $type, $color, $status, $image, $min_rent);

        if ($result == CAR_CREATED) {
          
            $message = array();
            $message['error'] = false;
            $message['message'] = 'Car was added successfully';

            $response->write(json_encode($message));

            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(201);
                        
        }elseif($result == CAR_FAILURE){
            $message = array(); 
            $message['error'] = true; 
            $message['message'] = 'Some error occurred';

            $response->write(json_encode($message));

            return $response
                        ->withHeader('Content-type', 'application/json')
                        ->withStatus(422);

        }elseif($result == CAR_EXISTS){
            $message = array(); 
            $message['error'] = true; 
            $message['message'] = 'Car Already Exists';

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
    endpoint: allcars
    method: GET
    table: car
*/
$app->get('/allcars', function(Request $request, Response $response){

    $db = new DbOperations; 

    $cars = $db->getAllCars();

    $response_data = array();

    $response_data['error'] = false; 
    $response_data['cars'] = $cars; 

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);  

});

/* 
    endpoint: updatecar
    parameters: name, plat_namber, base_number, model, type, color, status, image, min_rent
    method: PUT
    table: accounts
*/
$app->put('/updatecar/{id}', function(Request $request, Response $response, array $args){

    $id = $args['id'];

    if(!haveEmptyParameters(array('name', 'plat_number', 'base_number', 'model', 'type', 'min_rent', 'color', 'status', 'image'), $request, $response)){

        $request_data = $request->getParsedBody(); 

        $name = $request_data['name'];
        $platNamuber = $request_data['plat_number'];
        $baseNumber = $request_data['base_number'];
        $model = $request_data['model'];
        $type = $request_data['type']; 
        $minRent = $request_data['min_rent'];
        $color = $request_data['color'];
        $status = $request_data['status'];
        $image = $request_data['image'];

        $db = new DbOperations; 

        if($db->updateCar($name, $platNamuber, $baseNumber, $model, $type, $minRent, $color, $status, $image,$id)){
            $response_data = array(); 
            $response_data['error'] = false; 
            $response_data['message'] = 'Car Updated Successfully';
            $car = $db->getCarById($id);
            $response_data['car'] = $car; 

            $response->write(json_encode($response_data));

            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);  
        
        }else{
            $response_data = array(); 
            $response_data['error'] = true; 
            $response_data['message'] = 'Please try again later';
            $car = $db->getCarByName($pla);
            $response_data['car'] = $car; 

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
    endpoint: deletecar
    method: DELETE
    table: accounts
*/
$app->delete('/deletecar/{id}', function(Request $request, Response $response, array $args){
    $id = $args['id'];

    $db = new DbOperations; 

    $response_data = array();

    if($db->deleteCar($id)){
        $response_data['error'] = false; 
        $response_data['message'] = 'Car has been deleted';    
    }else{
        $response_data['error'] = true; 
        $response_data['message'] = 'Plase try again later';
    }

    $response->write(json_encode($response_data));

    return $response
    ->withHeader('Content-type', 'application/json')
    ->withStatus(200);
});
