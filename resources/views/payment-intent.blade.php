<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://js.stripe.com/v3/"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .StripeElement {
  box-sizing: border-box;

  height: 40px;

  padding: 10px 12px;

  border: 1px solid transparent;
  border-radius: 4px;
  background-color: white;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">

                    LaraStripe

                </div>

                <div class="">
                    <p>card 4242 4242 4242 4242</p>
                    <p>card 0 balance 4000 0000 0000 9995</p>
                </div>

                <div class="">
                    <form action="/pay" method="get" id="payment-form">
                      <div class="form-row">
                        <label for="card-element">
                          Credit or debit card
                        </label>
                        <div id="card-element">
                          <!-- A Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert"></div>
                      </div>


                      <button id="submitPayMentNow">Submit Payment</button>
                    </form>
                </div>


            </div>
        </div>

        <script>
        // Create a Stripe client.
        var stripe = Stripe('{{ $pkey }}');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
          base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {hidePostalCode: true, style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
          var displayError = document.getElementById('card-errors');
          if (event.error) {
            displayError.textContent = event.error.message;
          } else {
            displayError.textContent = '';
          }
        });

        // Handle form submission.
        var form = document.getElementById('submitPayMentNow');
        form.addEventListener('click', function(event) {
          event.preventDefault();

          stripe.createSource(card).then(function(result) {
            if (result.error) {
              // Inform the user if there was an error
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
            } else {
              // Send the source to your server
              console.log(result.source.id)
            }
          });

          // stripe.confirmCardPayment('{{ $client_secret_pi }}', {
          //     payment_method: {card: card}
          //   }).then(function(result) {
          //     if (result.error) {
          //       // Show error to your customer (e.g., insufficient funds)
          //       console.log(result.error.message);
          //     } else {
          //       // The payment has been processed!
          //       if (result.paymentIntent.status === 'succeeded') {
          //           console.log(result);
          //         // Show a success message to your customer
          //         // There's a risk of the customer closing the window before callback execution
          //         // Set up a webhook or plugin to listen for the payment_intent.succeeded event
          //         // that handles any business critical post-payment actions
          //       }
          //     }
          //   });

          // stripe.createToken(card).then(function(result) {
          //   if (result.error) {
          //     // Inform the user if there was an error.
          //     var errorElement = document.getElementById('card-errors');
          //     errorElement.textContent = result.error.message;
          //   } else {
          //     // Send the token to your server.
          //     stripeTokenHandler(result.token);
          //   }
          // });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            const tok = token.id
             const url = `{{ route('checkout.direct.pay') }}?token=${tok}`
             fetch(url)
             .then((resp) => resp.json())// Call the fetch function passing the url of the API as a parameter
             .then(function(data) {
                 if(data.isError == 'true'){
                     alert(data.message)
                 }
                 if(data.status === 'error')
                 {
                     console.log(data)
                  }
                  if(data.status === 'succeeded') {
                      alert('payment success');
                  }
                  // Your code for handling the data you get from the API
              })
              .catch(function() {
                  // This is where you run code if the server returns any errors
              });
          // Insert the token ID into the form so it gets submitted to the server
          // var form = document.getElementById('payment-form');
          // var hiddenInput = document.createElement('input');
          // hiddenInput.setAttribute('type', 'hidden');
          // hiddenInput.setAttribute('name', 'stripeToken');
          // hiddenInput.setAttribute('value', token.id);
          // form.appendChild(hiddenInput);
          //
          // // Submit the form
          // form.submit();
        }

        </script>
    </body>
</html>
