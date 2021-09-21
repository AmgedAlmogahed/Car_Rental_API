<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

 require '../includes/DbOperations.php';

$app = new \Slim\App([
    'settings'=>[
        'displayErrorDetails'=>true
    ]
]);

require '../includes/cars/methods.php';
require '../includes/customers/methods.php';
require '../includes/renting/methods.php';
require '../includes/services/methods.php';
require '../includes/users/methods.php';

$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "secure"=>false,
    "users" => [
        "amged" => "123456",
    ]
]));

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/photos';
$app->post('/uplode', function(Request $request, Response $response) use ($app) {
    $directory = $this->get('upload_directory');

    $uploadedFiles = $request->getUploadedFiles();

    // $request->headers_sent('Content-type','applicatoin/json');


    // handle single input with single file upload
    $uploadedFile = $uploadedFiles['example1'];
    if(empty($uploadedFile)){ 
        throw new Exception('expected value');
    }
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = moveUploadedFile($directory, $uploadedFile);
        $response->write('uploaded ' . $filename . '<br/>');
        // $response->write(json_encode($filename));
    }


    // handle multiple inputs with the same key
    // foreach ($uploadedFiles['example2'] as $uploadedFile) {
        // if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            // $filename = moveUploadedFile($directory, $uploadedFile);
            // $response->write('uploaded ' . $filename . '<br/>');
        // }
    // }

    // handle single input with multiple file uploads
    // foreach ($uploadedFiles['example3'] as $uploadedFile) {
        // if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            // $filename = moveUploadedFile($directory, $uploadedFile);
            // $response->write('uploaded ' . $filename . '<br/>');
        // }
    // }

    return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
});

/**
 * Moves the uploaded file to the upload directory and assigns it a unique name
 * to avoid overwriting an existing uploaded file.
 *
 * @param string $directory directory to which the file is moved
 * @param UploadedFile $uploadedFile file uploaded file to move
 * @return string filename of moved file
 */

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

function haveEmptyParameters($required_params, $request, $response){
    $error = false; 
    $error_params = '';
    $request_params = $request->getParsedBody(); 

    foreach($required_params as $param){
        if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
            $error = true; 
            $error_params .= $param . ', ';
        }
    }

    if($error){
        $error_detail = array();
        $error_detail['error'] = true; 
        $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';
        $response->write(json_encode($error_detail));
    }
    return $error; 
}



$app->run();