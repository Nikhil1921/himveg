<?php
class DbHandler
{
    private $conn;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';        
        $db = new DbConnect();
        $this->conn = $db->connect();
    } 

    /*---------------------------------Api key Check-----------------------------------*/

    public function isValidApiKey($api)
    {
        $sql = "SELECT id FROM delivery_boys WHERE id = '$api'";
        $data = mysqli_query($this->conn,$sql);
        
        if(mysqli_num_rows($data) > 0)
        {        
            return $key = mysqli_fetch_assoc($data);
        }
        else
        {
            return false;
        }
    }
        
    /*-----------------------------End Api key Check------------------------------------*/

    /*---------------------------------verify-----------------------------------*/

    public function signup($p)
    {
        $sql = "INSERT INTO `delivery_boys`(`name`, `phone`, `image`, `email`, `status`, `created_at`, `updated_at`, `bank_name`, `branch`, `account_no`, `holder_name`, `sales_commission_percentage`, `address`, `lat`, `lng`, `vehicle`, `vehicle_name`, `rc_no`, `insurance_no`) VALUES ('$p->name', '$p->phone', '$p->image', '$p->email', 'approved', '$p->created_at', '$p->updated_at', '$p->bank_name', '$p->ifsc', '$p->account_no', '$p->holder_name', '$p->commission', '$p->address', '$p->lat', '$p->lng', '$p->vehicle', '$p->vehicle_name', '$p->rc_no', '$p->insurance_no')";

        return mysqli_query($this->conn, $sql);
    }

    public function verify($value, $field, $table)
    {
        $sql = "SELECT $field FROM $table WHERE $field = '$value'";
        $data = mysqli_query($this->conn, $sql);
        
        return mysqli_num_rows($data) > 0 ? true : false;
    }
        
    /*-----------------------------End verify------------------------------------*/

    /*---------------------------------Mobile Check-----------------------------------*/

    public function login($p)
    {
        $sql = "SELECT * from delivery_boys where phone = '$p->mobile'";
        $result = mysqli_query($this->conn,$sql);

        if(mysqli_num_rows($result) > 0)
        {
            $data = $result->fetch_assoc();
            $data['otp'] = rand(1000, 9999);
            $this->send_otp($p->mobile, $data['otp']);
            return $data;
        }
        else
        {
            return false;
        }
    }

    /*-----------------------------End Mobile Check------------------------------------*/

    /*---------------------------------Forgot Password-----------------------------------*/

    public function verify_mobile($p)
    {
        $sql = "SELECT id from delivery_boys where phone = '$p->mobile'";
        $result = mysqli_query($this->conn,$sql);

        if(mysqli_num_rows($result) > 0)
        {
            $row = $result->fetch_assoc();
            $otp = rand(1000, 9999);
            $this->send_otp($p->mobile, $otp);
            $response['row'] = ['phone' => $p->mobile, 'otp' => $otp, 'id' => $row['id']];
            $response['error'] = false;
            $response['message'] ="OTP send success.";
        }
        else
        {
            $response['error'] = true;
            $response['message'] ="Mobile no not registered.";
        }

        return $response;
    }

    public function change_password($p)
    {
        $sql = "UPDATE delivery_boys SET password = md5('$p->password') where id = '$p->api'";
        return mysqli_query($this->conn, $sql) === true ? true : false;
    }

    public function send_otp($receiver, $otp)
    {
        $response = 'error';
        if($_SERVER['HTTP_HOST'] != 'localhost'){
            $from = 'wcserv';
            $key = '2612F22D485872';
            $sms = "$otp WCS OTP FOR LOGIN THANKU FOR SINGHUP";
            $url = "key=".$key."&campaign=12188&routeid=7&type=text&contacts=".$receiver."&senderid=".$from."&msg=".urlencode($sms)."&template_id=1707162797401861012";
    
            $base_URL = 'http://denseteklearning.com/app/smsapi/index?'.$url;
    
            $curl_handle = curl_init();
            curl_setopt($curl_handle,CURLOPT_URL,$base_URL);
            curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
            curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($curl_handle);
            curl_close($curl_handle);
            if ($result) {
                $response = 'success';
            } else {
                $response = 'error';
            }
        }
        return $response;
    }

    /*-----------------------------End Forgot Password------------------------------------*/

    /*-----------------------------Orders list------------------------------------*/
    
    public function orders($p)
    {
        $sql = "SELECT o.id, (
                    6371 * acos (
                    cos ( radians('$p->lat') )
                    * cos( radians( s.lat ) )
                    * cos( radians( s.lng ) - radians('$p->lng') )
                    + sin ( radians('$p->lat') )
                    * sin( radians( s.lat ) )
                    )
                ) AS distance, CONCAT(s.f_name, ' ', s.l_name) AS seller, o.order_status, o.order_amount, o.shipping_cost, o.shipping_address_data, o.payment_method, o.payment_status, od.product_details, od.qty, od.price, od.tax, od.discount, od.created_at, u.f_name, u.l_name, u.phone
                FROM orders o
                INNER JOIN sellers s ON s.id = o.seller_id
                INNER JOIN order_details od ON od.order_id = o.id
                INNER JOIN users u ON u.id = o.customer_id
                WHERE o.order_status = '$p->status'";
        
        if ($p->own != 0) $sql .= " AND o.assigned = '$p->api'";
        $sql .= " HAVING distance <= 3";
        
        $data = mysqli_query($this->conn, $sql);
        
        return (mysqli_num_rows($data) > 0) ? mysqli_fetch_all($data, MYSQLI_ASSOC) : false;
    }
    /*-----------------------------End Orders list------------------------------------*/
    /*-----------------------------Change Order status------------------------------------*/
    public function change_status($p)
    {
        $sql = "UPDATE orders SET assigned = '$p->api', order_status = '$p->status'";
        
        if (isset($p->shipping_cost)) $sql .= ", shipping_cost = '$p->shipping_cost'";
        
        $sql .= " WHERE id = '$p->order_id'";

        return mysqli_query($this->conn, $sql) === true ? true : false;
    }
    /*-----------------------------End Change Order status------------------------------------*/
}