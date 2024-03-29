<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Code4mk\LaraStripe\Lib\StripeSubscription;
use Code4mk\LaraStripe\Lib\StripePlans;
use Code4mk\LaraStripe\Lib\StripeCoupon;
use Code4mk\LaraStripe\Lib\StripeRequestPayment;
use Code4mk\LaraStripe\Lib\StripePaymentIntent;


Route::get('invoice', function(){
    \Stripe\Stripe::setApiKey('sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1');
   $r = \Stripe\Invoice::create([
  'customer' => 'cus_G8mHZG6Nu5Gfow',
]);
return response()->json($r);
});
Route::get('paymentintent',function(){
        $rr = new StripePaymentIntent();
        $p = $rr->setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                            ->create();
        return view('payment-intent',['pkey' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR','client_secret_pi' => $p->client_secret]);
        return response()->json($p);
});

Route::get('payment/request/{id}',function(){
        $rr = new StripeRequestPayment();
        $p = $rr->setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                            ->title('Sass project payment $50')
                            ->description('Sass project payment @kamal')
                            ->amount(50,'usd')
                            ->get();
        $pkey = 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR';
    return view('payment-request',['id'=>request('id'),'skus'=>$p->skus,'pkey'=>$pkey]);
});

Route::get('payment/request/{id}/success',function(){
    $rr = new StripeRequestPayment();
    $p = $rr->setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                        ->status(request('session_id'));

    return response()->json($p);
});

Route::get('product/create',function(){
//     $rr = new StripeRequestPayment();
//     $p = $rr->setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
//                         ->title('Sass project payment $50')
//                         ->description('Sass project payment @kamal')
//                         ->amount(50,'usd')
//                         ->get();
//     return response()->json($p);
//     \Stripe\Stripe::setApiKey('sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1');
//     $r = \Stripe\Product::create([
//       'name' => 'T-shirt 32 hj21',
//       'type' => 'good',
//       'description' => 'Comfortable cotton t-shirt',
//       'attributes' => ['name'],
//     ]);
// //https://stripe.com/docs/api/skus/create
//     $skus = \Stripe\SKU::create([
//       'attributes' => [
//         'name' => 'T-shirt 32 hj21'
//       ],
//       'price' => 1600,
//       'currency' => 'usd',
//       'inventory' => [
//           'type' => 'infinite',
//       ],
//       'product' => $r->id,
//     ]);
//
//     $ty = (object)['product' => $r, 'skus' => $skus->id];
 \Stripe\Stripe::setApiKey('sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1');
    $u = \Stripe\PaymentIntent::retrieve(
      'pi_1Fc7LBAHZl11YnL9VDcoroIc'
    );

    return response()->json($u);
});

Route::get('coupon/create',function(){
  $l = LaraStripeCoupon::setup([
                      'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                    ])
                    ->amount(20,'per','usd')
                    ->duration('once')
                    ->name('polo aree223')
                    ->get();
  return response()->json($l);
});

Route::get('coupon/delete',function(){
  $l = LaraStripeCoupon::setup([
                      'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                    ])
                    ->delete('polo_aree22');

  return response()->json($l);
});

Route::get('plan/create',function(){
  $l = LaraStripePlan::setup([
                      'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                    ])->amount(34.50)->currency('usd')->interval('month')->product(['name'=>'sass software buy 3'])->trial(3)->get();
  return response()->json($l);
});

Route::get('plan/get',function(){
  $tu = new StripePlans;
  $l = $tu->setup([
                      'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                    ])->retrieve('plan_G7ZvPPExf8oNtc');
  return response()->json($l);
});

Route::get('plan/delete',function(){
  $tu = new StripePlans;
  $l = $tu->setup([
                      'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                    ])->delete('plan_G6XcGO6UREtug5');
  return response()->json($l);
});

Route::get('plan/deactive',function(){
  $tu = new StripePlans;
  $l = $tu->setup([
                      'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                    ])->active('plan_G7aD01GTKUi2OO');
  return response()->json($l);
});

Route::get('subscription',function(){
  $r = new StripeSubscription;
$ty = LaraStripeSubs::setup([
                    'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                  ])->customer('cus_G6X5e2H0Ki2IcV')
                  ->plan('plan_G7xhY99PKQAhvD')
                  ->trial(0)
                  ->coupon('free-now')
                  ->get();
  return response()->json($ty);

});

Route::get('subscription/get',function(){
  $r = new StripeSubscription;
$ty = $r->setup([
                    'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                  ])->retrieve('sub_G7y4OWjTWR7fiy');
  return response()->json($ty);

});

Route::get('subscription/cancel',function(){
  $r = new StripeSubscription;
$ty = LaraStripeSubs::setup([
                    'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
                  ])->cancel('sub_G7y4OWjTWR7fiy');
  return response()->json($ty);

});

Route::get('/', function () {
    return view('welcome');
});

Route::get('charge/create',function(){
    $charge = LaraStripeCharge::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency'=>'usd'
                      ])
                      ->card('tok_visa')
                      ->amount(25.267654)
                      ->metadata(['tnx_id' => 'tnx-32343','purchase_id' => 'trgtrg45'])
                      ->description('kamal is here')
                      ->purchase()
                      ->getAll();
return response()->json($charge);
});

Route::get('charge/refund',function(){
    $refund = LaraStripeCharge::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                      ])
                      ->refund('ch_1FVzAMAHZl11YnL9W7UJQd0l');
    return response()->json($refund);
});


Route::get('charge/customer',function(){
    $charge = LaraStripeCharge::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency'=>'usd'
                      ])
                      ->customer('cus_G2L2KoumL45hzn')
                      ->amount(25.54567)
                      ->metadata(['tnx_id' => 'tnx-32343','purchase_id' => 'trgtrg45'])
                      ->description('charge with customer id ')
                      ->purchase()
                      ->get();
return response()->json($charge);
});

Route::get('customer/create',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                              ->create(['source' => 'tok_visa','email' => 'ahiremostafa@gmail.com'])
                              ->metadata(['phone' => '21232'])
                              ->get();

    return response()->json($cus);
});

Route::get('customer/add-card',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                              ->changeCard('cus_G8gpFwsMHE575v','card_1FcPVzAHZl11YnL99qRpnwaC');


    return response()->json($cus);
});

Route::get('customer/get',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->retrieve('cus_G8gpFwsMHE575v');
    return response()->json($cus);
});

Route::get('customer/cards',function(){
    $cards = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->cards('cus_G8gpFwsMHE575v');
    return response()->json($cards);
});

Route::get('customer/add-card',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->addCard('cus_G8gpFwsMHE575v','tok_visa');

    return response()->json($cus);
});

Route::get('customer/delete-card',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->deleteCard('cus_G8gpFwsMHE575v','card_1FcQjeAHZl11YnL9F62Mwfm9');

    return response()->json($cus);
});

Route::get('customer/set-default-card',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->setDefaultCard('cus_G8gpFwsMHE575v','card_1FcQVCAHZl11YnL9zDm2QJmz');

    return response()->json($cus);
});

Route::get('checkout',function(){
    $session = LaraStripeCheckout::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency' => 'usd'
                      ])
                      ->configure([
                            'success_url' => 'http://127.0.0.1:8000/checkout/success?session_id={CHECKOUT_SESSION_ID}',
                            'cancel_url' => 'http://127.0.0.1:8000',
                            'ref_key' => 'tnx_4345623232'
                      ])
                      ->products([
                          [
                            'name' => 'Mobile',
                            'amount' => 150,
                            'description' => 'china mobile',
                            'images' => ['https://cdn.pixabay.com/photo/2016/12/06/09/31/blank-1886008_960_720.png']
                        ],
                        [
                            'name' => 'T-shirt',
                            'amount' => 24.24,
                            'quantity' => 2
                        ]
                      ])
                      ->getSession();
                      //return response()->json($session);
    return view('checkout',['session'=>$session]);
});

Route::get('checkout/future',function(){
    $session = LaraStripeCheckout::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency' => 'usd'
                      ])
                      ->configure([
                            'success_url' => 'http://127.0.0.1:8000/checkout/future/success?session_id={CHECKOUT_SESSION_ID}',
                            'cancel_url' => 'http://127.0.0.1:8000',
                            'ref_key' => 'tnx_4345623232_future'
                      ])
                      ->future()
                      ->getSession();
                      // return response()->json($session);
    return view('checkout',['session'=>$session]);
});

Route::get('checkout/success',function(){
    $output = LaraStripeCheckout::setup([
                                    'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                                    'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                                    'currency'=>'usd'
                                ])
                                ->status(request('session_id'));
    return response()->json($output);
});

Route::get('checkout/future/success',function(){
    $output = LaraStripeCheckout::setup([
                                    'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                                    'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                                    'currency'=>'usd'
                                ])
                                ->storeFuture(request('session_id'));
    return response()->json($output);
});

Route::get('checkout/direct',function(){
    $pkey = 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR';
    return view('checkout-direct',['pkey'=>$pkey]);
});

Route::get('checkout/direct-pay',function(){
    $charge = LaraStripeCharge::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency'=>'usd'
                      ])
                      ->card(request('token'))
                      ->amount(25.267654)
                      ->metadata(['tnx_id' => 'tnx-32343','purchase_id' => 'trgtrg45'])
                      ->description('kamal is here')
                      ->purchase()
                      ->get();
return response()->json($charge);
})->name('checkout.direct.pay');

Route::get('checkout/refund',function(){
    $charge = LaraStripeCheckout::setup([
        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'
    ])->refund('pi_1FWIACAHZl11YnL9rTSOcxNB');
    return response()->json($charge);
});
