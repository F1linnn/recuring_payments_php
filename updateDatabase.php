<?php
class DB{

    private  $servername = "---";
    private  $username = "---";
    private  $password = "---";
    private  $dbname = "---";

    private  $conn;

    function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Ошибка подключения: " . $this->$conn->connect_error);
        }
    }
    
    
    function createCustomer($email, $customerId, $name)
    {    
        $sql = "INSERT INTO customers (email, id_stripe, customer_name) VALUES ('$email','$customerId', '$name')";

        $this->$conn->query($sql);

    }    
    
    function statusPaymentIntent($paymentIntentId, $amount, $currency, $status, $customer_id)
    {

        
        $sql = "INSERT INTO transactions (payment_intent_id, amount, currency, payment_status, customer_id) VALUES ('$paymentIntentId','$amount', '$currency', '$status', '$customer_id')";
        
        $this->$conn->query($sql);

    }

    function createSubscription($customer_id, $subscription_id, $status, $current_period_start, $current_period_end)
    {

        $sql = "INSERT INTO subscriptions (customer_id, subscription_id, sub_status, current_period_start, current_period_end) VALUES ('$customer_id','$subscription_id', '$status', FROM_UNIXTIME('$current_period_start'), FROM_UNIXTIME('$current_period_end'))";
        
        $this->$conn->query($sql);



    }

    function deleteSubscription($subscription_id)
    {

        $sql = "DELETE FROM ваша_таблица WHERE subscription_id = '$subscription_id'";
        
        $this->$conn->query($sql);



    }

    function updateSubscription($customer_id, $subscription_id, $status, $current_period_start, $current_period_end)
    {
        $sql = "UPDATE `subscriptions` SET `sub_status`='$status' WHERE `subscription_id` = '$subscription_id'";
            
            
        $this->$conn->query($sql);

    }
        
}
?>
