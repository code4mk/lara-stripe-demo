<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <button
          style="background-color:#6772E5;color:#FFF;padding:8px 12px;border:0;border-radius:4px;font-size:1em"
          id="checkout-button-{{ $skus }}"
          role="link"
        >
          Checkout
        </button>

        <div id="error-message"></div>
        <script src="https://js.stripe.com/v3"></script>

        <script>
        (function() {
          var stripe = Stripe('{{ $pkey }}');

          var checkoutButton = document.getElementById('checkout-button-{{ $skus }}');
          checkoutButton.addEventListener('click', function () {
            // When the customer clicks on the button, redirect
            // them to Checkout.
            let skus = '{{ $skus }}'
            stripe.redirectToCheckout({
              items: [{sku: '{{ $skus }}', quantity: 1}],

              // Do not rely on the redirect to the successUrl for fulfilling
              // purchases, customers may not always reach the success_url after
              // a successful payment.
              // Instead use one of the strategies described in
              // https://stripe.com/docs/payments/checkout/fulfillment
              successUrl: `http://127.0.0.1:8000/payment/request/{{ $id }}/success?session_id={CHECKOUT_SESSION_ID}`,
              cancelUrl: 'https://your-website.com/canceled',
            })
            .then(function (result) {
              if (result.error) {
                // If `redirectToCheckout` fails due to a browser or network
                // error, display the localized error message to your customer.
                var displayError = document.getElementById('error-message');
                displayError.textContent = result.error.message;
              }
            });
          });
        })();
        </script>
    </body>
</html>
