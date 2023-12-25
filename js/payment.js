
document.addEventListener('DOMContentLoaded', function () {
    var stripe = Stripe('pk_test_51OKemNAjbAXh6JkRVbUlwVo0zDIlQ1Q6Lum3XufObzh0dm6v2ePvZTexRd5HCXNVXWHFq4VOQbJAgBKMOhCkdWUG007YVMsczJ'); // Замените на ваш публичный ключ Stripe
    var elements = stripe.elements();
    
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    var form = document.getElementById('payment-form');
    var priceDisplay = document.getElementById('price-display');
    

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        createPaymentMethod();
    });

    document.getElementById('subscription-type').addEventListener('change', function () {
        updatePriceDisplay();
    });

    function updatePriceDisplay() {
        var selectedSubscription = document.getElementById('subscription-type');
        var selectedPrice = selectedSubscription.options[selectedSubscription.selectedIndex].getAttribute('data-price');
        priceDisplay.textContent = '$' + selectedPrice;
    }

    async function createSub(pay_id) {
        var subscriptionType = document.getElementById('subscription-type').value;
        var email = document.getElementById('email').value;
        var name = document.getElementById('username').value;
        
        console.log(email);
        console.log(pay_id);
        fetch('subscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ subscriptionType: subscriptionType, email: email, paymentMethodId: pay_id, username: name }),
        }).then(result => result.json().then(result => {
            console.log(result)
            if(result.error){
                console.error(result.error)
            }
            else{
                stripe.confirmCardPayment(result.paymentIntent.client_secret).then(result =>{
                    console.log(result);
                })
            }
        }
        ));
        
    }


    function createPaymentMethod() {
        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        }).then(function(result) {
            if (result.error) {
                console.error(result.error.message);
            } else {
                // Получаем ID PaymentMethod и отправляем его на сервер
                var paymentMethodId = result.paymentMethod.id;
                createSub(paymentMethodId);
            }
        });
    }


    // Инициализация отображения цены при загрузке страницы
    updatePriceDisplay();
});
