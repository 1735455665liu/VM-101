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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('goodslist','Cart\CartController@goodslist'); //商品列表
Route::get('cartlist','Cart\CartController@cartlist');//购物车列表
Route::post('/cart/add','Cart\CartController@cartadd');//加入购物车
Route::post('/order/orderadd','Order\OrderController@orderadd');//加入订单
Route::get('orderlist','Order\OrderController@orderlist');//订单展示
Route::get('/pay/payadd','Pay\PayController@pay');//支付
Route::get('Alireturn','Pay\PayController@Alireturn');//同步
Route::post('notify','Pay\PayController@notify');//异步
Route::get('test','Pay\PayController@test');//同步