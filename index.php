<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Subscription</title>
    <script src="https://js.stripe.com/v3"></script>
    <script src="js/payment.js" defer></script>
</head>
<body>
    <h1>Subscribe to Our Service</h1>

    <form id="payment-form">
        <div>
            <label for="subscription-type">Choose Subscription Type:</label>
            <select id="subscription-type" name="subscription-type">
                <option value="basic" data-price="10">Basic Subscription ($10/month)</option>
                <option value="premium" data-price="20">Premium Subscription ($20/month)</option>
            </select>
        </div>
        <div>
            <label for="price">Price:</label>
            <span id="price-display">$10</span>/month
        </div>
        <div id="card-element">
            <!-- Добавляем элемент для ввода данных карты -->
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="username">Name:</label>
            <input type="username" id="username" name="username" required>
        </div>
        <button id="submit-button">Subscribe and Pay</button>
    </form>
</body>
</html>
