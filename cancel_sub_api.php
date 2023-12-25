<?php

require_once 'vendor/autoload.php';

// Установите ваш секретный ключ Stripe
\Stripe\Stripe::setApiKey('ваш_секретный_ключ');

// Получите идентификатор подписки из запроса (замените на реальный идентификатор)
$subscriptionId = $_GET['subscriptionId'] ?? '';

if (empty($subscriptionId)) {
    echo json_encode(['success' => false, 'error' => 'Не указан идентификатор подписки']);
    exit;
}

try {
    // Получите подписку по идентификатору
    $subscription = \Stripe\Subscription::retrieve($subscriptionId);

    // Проверьте, активна ли подписка перед отменой
    if ($subscription->status === 'active') {
        // Отмените подписку
        $subscription->cancel_at_period_end = true;

        echo json_encode(['success' => true, 'message' => 'Подписка успешно отменена']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Подписка не является активной']);
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Обработка ошибок
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
