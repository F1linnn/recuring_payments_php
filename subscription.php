<?php

require_once 'vendor/autoload.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$subscriptionType = $data['subscriptionType'];
$email = $data['email'];
$username = $data['username'];
$paymentMethodId = $data['paymentMethodId'];

if($subscriptionType === 'basic'){
    $priceId = "price_1OMsaHAjbAXh6JkRdiH5Jisb";
}
else
{
    $priceId = "price_1OMsbZAjbAXh6JkRLUymrtGc"; 
}


\Stripe\Stripe::setApiKey('sk_test'); // Замените секретный ключ Stripe

$customer = \Stripe\Customer::all(['email' => $email])->data[0] ?? null;

// Проверка на существование
if (!$customer) {
    $customer = \Stripe\Customer::create(['name' => $username,'email' => $email,'payment_method' => $paymentMethodId,'invoice_settings'=> ['default_payment_method' => $paymentMethodId]]);
    
}
$existingSubscription = \Stripe\Subscription::all([
    'customer' => $customer['id'],
    'status' => 'active', 
    'limit' => 1,
]);

if (!empty($existingSubscription->data and $existingSubscription->data[0]->plan->id === $priceId)) {
    echo json_encode(['success' => false, 'error' => 'Дубликат оплаты', 'sub' => $existingSubscription]);
    exit;
}
else{
    try {
    
        $subscription = \Stripe\Subscription::create([
            'customer' => $customer['id'],
            'items' => [
                [
                    'price' => $priceId
                ],
            ],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'payment_method_options' => [
                'card' => [
                    'request_three_d_secure' => 'any',
                ],],
                'save_default_payment_method' => 'on_subscription'],
            'expand' => ['latest_invoice.payment_intent'],
        ]);
    
    
        echo json_encode([
            'subscriptionId' => $subscription,
            'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret,
            'paymentIntent' => $subscription->latest_invoice->payment_intent,
            'customer' => $customer
        ]);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}



?>
