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

Route::get('charge',function(){
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
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency'=>'usd'
                      ])
                      ->refund('ch_1FVzAMAHZl11YnL9W7UJQd0l');
    return response()->json($refund);
});

Route::get('customer/create',function(){
    $cus = LaraStripeCustomer::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency'=>'usd'
                      ])
                      ->create(['source' => 'tok_visa'])
                      ->metadata(['phone' => '212'])
                      ->get();

    return response()->json($cus);
});

Route::get('customer/get',function(){
    $cus = LaraStripeCustomer::setup([
                        'secret_key'=>'sk_test_mBGoFuccDy2KCD4pobbaixKK00qUW0ghu1',
                        'public_key' => 'pk_test_VNi7F1zcwwffZIi1tAkX1dVs00JfKPsCGR',
                        'currency'=>'usd'
                      ])
                      ->retrieve('cus_G23dCkE08ktOmUa');

    return response()->json($cus);
});
