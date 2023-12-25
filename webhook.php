<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require 'vendor/autoload.php';
require 'updateDatabase.php';

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient('sk_test');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec';

$payload = @file_get_contents('php://input');
// $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

$DB = new DB();

try {
    $event = \Stripe\Event::constructFrom(
        json_decode($payload, true)
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
  case 'customer.created':
    $customer = $event->data->object;
    $DB->createCustomer( $customer->email, $customer->id,  $customer->name);
    break;
  case 'customer.subscription.created':
    $subscription = $event->data->object;
    $DB->createSubscription($subscription->customer, $subscription->id, "CR", $subscription->current_period_start, $subscription->current_period_end);
    break;
  case 'customer.subscription.deleted':
    $subscription = $event->data->object;
    $DB->deleteSubscription($subscription->id)
    break;
  case 'customer.subscription.resumed':
    $subscription = $event->data->object;
    $DB->updateSubscription($subscription->customer, $subscription->id, "OK", $subscription->current_period_start, $subscription->current_period_end);
    break;del
  case 'payment_intent.canceled':
    $paymentIntent = $event->data->object;
    $DB->statusPaymentIntent($paymentIntent->id, $paymentIntent->amount, $paymentIntent->currency, "CL", $paymentIntent->customer);
    break;
  case 'payment_intent.created':
    $paymentIntent = $event->data->object;
    $DB->statusPaymentIntent($paymentIntent->id, $paymentIntent->amount, $paymentIntent->currency, "NW", $paymentIntent->customer);
    break;
  case 'payment_intent.requires_action':
    $paymentIntent = $event->data->object;
    $DB->statusPaymentIntent($paymentIntent->id, $paymentIntent->amount, $paymentIntent->currency, "OP", $paymentIntent->customer);
    break;
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
    $DB->statusPaymentIntent($paymentIntent->id, $paymentIntent->amount, $paymentIntent->currency, "OK", $paymentIntent->customer);
    break;
  case 'invoice.payment_succeeded':
   $invoice = $event->data->object;
   $DB->updateSubscription($subscription->customer, $subscription->id, "OK", $subscription->current_period_start, $subscription->current_period_end);
    break;
  case 'invoice.payment_failed':
    $invoice = $event->data->object;
    $DB->updateSubscription($subscription->customer, $subscription->id, "ER", $subscription->current_period_start, $subscription->current_period_end);
  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);