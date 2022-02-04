 <?php
require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/*--------------------------Required field Check----------------------------------*/

function verifyRequiredParams($required_fields)
{
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;

    if ($_SERVER['REQUEST_METHOD'] == 'PUT')
    {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }

    foreach ($required_fields as $field) 
    {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) 
        {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
    if ($error) 
    {
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(200, $response);
        $app->stop();
    }
}

/*-----------------------------End Required field Check-------------------------*/

/*-----------------------------Api key Check------------------------------------*/

function authenticate(\Slim\Route $route)
{
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
    
    if (isset($headers['Authorization']) || isset($headers['authorization']))
    {
        $db = new DbHandler();
        $key = isset($headers['Authorization']) ? $headers['Authorization'] : $headers['authorization'];        
        
        if (!$key=$db->isValidApiKey($key))
        {            
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Id";
            echoRespnse(200, $response);
            $app->stop();
        }
        else
        {
            global $api;
            $api = $key["id"];
        }
    }
    else
    {
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(200, $response);
        $app->stop();
    }
}

/*----------------------------End Api key Check----------------------------------*/

/*---------------------------------Mobile Check------------------------------------*/

$app->post('/login',function () use ($app)
{
    verifyRequiredParams(array('mobile', 'password'));

    $post = (object) $app->request->post();
  
    $db = new DbHandler();

    if($row = $db->login($post))
    {        
        $response['row'] = $row;
        $response['error'] = false;
        $response['message'] ="Login Successfully.";
    }
    else 
    {
        $response["error"] = true;
        $response['message'] = "Login Not Successfully!";
    }

    echoRespnse(200, $response);
});

/*-----------------------------End Mobile Check------------------------------------*/

/*---------------------------------Forgot Password------------------------------------*/

$app->post('/verify-mobile',function () use ($app)
{
    verifyRequiredParams(array('mobile'));

    $post = (object) $app->request->post();
  
    $db = new DbHandler();

    $row = $db->verify_mobile($post);

    echoRespnse(200, $row);
});

$app->post('/change-password', 'authenticate', function () use ($app)
{
    verifyRequiredParams(array('password'));
    
    global $api;
    $post = (object) $app->request->post();
    $post->api = $api;
  
    $db = new DbHandler();
    if($row = $db->change_password($post))
    {
        $response['error'] = false;
        $response['message'] ="Password change successful.";
    }
    else 
    {
        $response["error"] = true;
        $response['message'] = "Password change not successful!";
    }

    echoRespnse(200, $response);
});

/*-----------------------------End Forgot Password------------------------------------*/
/*-----------------------------Orders list------------------------------------*/
$app->get('/orders', 'authenticate', function () use ($app)
{
    verifyRequiredParams(array('status', 'lat', 'lng', 'own'));
    
    global $api;
    $get = (object) $app->request->get();
    $get->api = $api;
  
    $db = new DbHandler();

    if($row = $db->orders($get))
    {
        $response['row'] = $row;
        $response['error'] = false;
        $response['message'] ="Orders list successful.";
    }
    else 
    {
        $response["error"] = true;
        $response['message'] = "Orders list not successful!";
    }

    echoRespnse(200, $response);
});
/*-----------------------------End Orders list------------------------------------*/
/*-----------------------------Change Order status------------------------------------*/
$app->post('/change-status', 'authenticate', function () use ($app)
{
    verifyRequiredParams(array('order_id', 'status'));
    // , 'shipping_cost'
    global $api;
    $post = (object) $app->request->post();
    $post->api = $api;
  
    $db = new DbHandler();

    if($row = $db->change_status($post))
    {
        $response['error'] = false;
        $response['message'] ="Order status change successful.";
    }
    else 
    {
        $response["error"] = true;
        $response['message'] = "Order status change not successful!";
    }

    echoRespnse(200, $response);
});
/*-----------------------------End Change Order status------------------------------------*/

//************** no delete***********************************//
function echoRespnse($status_code, $response)
{
    $app = \Slim\Slim::getInstance();
    $app->status($status_code);
    $app->contentType('application/json');
    echo json_encode($response);
}
$app->run();

?>