 <?php
require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
define('UPLOAD', dirname(dirname(__DIR__)).'/storage/app/public/admin/');
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

/*---------------------------------Login Check------------------------------------*/

$app->post('/login',function () use ($app)
{
    verifyRequiredParams(array('mobile'));

    $post = (object) $app->request->post();
  
    $db = new DbHandler();

    if($row = $db->login($post))
    {
        $response['row'] = $row;
        $response['error'] = false;
        $response['message'] ="OTP sent successful.";
    }
    else
    {
        $response["error"] = true;
        $response['message'] = "OTP sent not successful!";
    }

    echoRespnse(200, $response);
});

/*-----------------------------End Login Check------------------------------------*/

/*---------------------------------Sign UP------------------------------------*/

$app->post('/signup',function () use ($app)
{
    $validate = ['name', 'phone', 'email', 'commission', 'address', 'lat', 'lng', 'vehicle', 'vehicle_name', 'rc_no', 'insurance_no', 'bank_name', 'ifsc', 'holder_name', 'account_no'];
    
    if (empty($_FILES['image']['name'])) {
        array_push($validate, 'image');
    }

    verifyRequiredParams($validate);
    $post = (object) $app->request->post();
    $db = new DbHandler();
    
    if($db->verify($post->phone, 'phone', 'delivery_boys'))
    {
        $response['error'] = false;
        $response['message'] ="Mobile already in use.";
        echoRespnse(200, $response);
    }

    if($db->verify($post->email, 'email', 'delivery_boys'))
    {
        $response['error'] = false;
        $response['message'] ="Email already in use.";
        echoRespnse(200, $response);
    }
    
    $image = date('Y-m-d-').time().".".pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    
    if(! move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD.$image)){
        $response["error"] = true;
        $response['message'] = "Error in image upload.";
    }
    
    $post->image = $image;
    $post->created_at = date('Y-m-d H:i:s');
    $post->updated_at = date('Y-m-d H:i:s');

    if($row = $db->signup($post))
    {
        // $response['row'] = $row;
        $response['error'] = false;
        $response['message'] ="Sign up successful.";
    }
    else
    {
        if (is_file(UPLOAD.$image)) unlink(UPLOAD.$image);
        $response["error"] = true;
        $response['message'] = "Sign up not successful!";
    }

    echoRespnse(200, $response);
});

/*-----------------------------End Sign UP------------------------------------*/

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
        /* foreach ($row as $k => $v) {
            $row[$k]['shipping_address_data'] = json_decode($v['shipping_address_data']);
            $row[$k]['product_details'] = json_decode($v['product_details']);
        } */
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