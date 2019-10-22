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
                              ->create(['source' => 'tok_visa','email' => 'test@test.co'])
                              ->metadata(['phone' => '212'])
                              ->get();

    return response()->json($cus);
});

Route::get('customer/get',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->retrieve('cus_G2L2KoumL45hzn');

    return response()->json($cus);
});

Route::get('customer/change-card',function(){
    $cus = LaraStripeCustomer::setup(['secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1'])
                                ->changeCard('cus_G2L2KoumL45hzn','tok_amex');

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
                      // return response()->json($session);
    return view('checkout',['session'=>$session]);
});

Route::get('checkout/success',function(){
    $output = LaraStripeCheckout::setup([
                                    'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                                    'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                                    'currency'=>'usd'
                                ])
                                ->retrieve(request('session_id'));
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
