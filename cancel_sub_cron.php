<?php

require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk');

try {
    $subscriptions = \Stripe\Subscription::all(); 
    $today = strtotime(date('Y-m-d'));

    foreach ($subscriptions->data as $subscription) {
        $subscriptionEndDate = strtotime(date('Y-m-d', $subscription->current_period_end));
        
        if ($subscription->status === 'active' && $subscriptionEndDate < $today) {
            $subscription->cancel(); 
            // database обработка
        }
    }
} catch (\Stripe\Exception\InvalidRequestException $e) {
    // Обработка ошибок
    error_log('Stripe Error: ' . $e->getError()->message);
}
?>
<!-- 
    chmod +x cancel_subscriptions.php //права на выполнения
    crontab -e // добавить задачу в cron
    0 0 * * * /new_php/cancel_subscriptions.php // выполнение скрипта каждый день в полночь



 -->
