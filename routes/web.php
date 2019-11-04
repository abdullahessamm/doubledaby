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

//Main page
Route::get('/', function () {
    $page_details = [
        'page_name'             => '',
        'css_file_name'         => 'MainPage',
        'js_file_name'          => 'MainPage',
        'use_jquery'            => true,
        'use_bootstrap_scripts' => true,
        'use_nav_bar'           => true,
        'use_footer'            => true,
    ];

    return view('MainPage', compact('page_details'));
});

//Login page
Route::get('/login', function () {
    
    $page_details = [
        'page_name'             => '| Login',
        'css_file_name'         => 'login',
        'js_file_name'          => 'login',
        'use_jquery'            => false,
        'use_bootstrap_scripts' => false,
        'use_nav_bar'           => true,
        'use_footer'            => false,
    ];
    return view('login', compact('page_details'));
})->middleware('guest');

Route::post('/login', 'UserController@login')->middleware('guest'); //Route to login controller

//Signup page
Route::get('/signup', function () {
    $page_details = [
        'page_name'             => '| signup',
        'css_file_name'         => 'login',
        'js_file_name'          => '',
        'use_jquery'            => false,
        'use_bootstrap_scripts' => false,
        'use_nav_bar'           => true,
        'use_footer'            => false,
    ];
    return view('signup', compact('page_details'));
})->middleware('guest');

Route::post('/signup', 'UserController@signup')->middleware('guest'); //Route to signup controller

//Adding phone number
Route::post('/addphone', 'UserController@add_phone')->middleware('user');
Route::get('/verifyphone/{user_id?}', 'UserController@verify_phone_view')->middleware('user'); //veryfy phone view
Route::post('/verifyphone', 'UserController@verify_phone')->middleware('user'); //veryfy phone process
Route::get('/resendcode', 'UserController@Resend_code')->middleware('user');

//Forgot Password group
Route::get('/forgot', function () {
    $page_details = [
        'page_name'             => '| reset account',
        'css_file_name'         => 'login',
        'js_file_name'          => '',
        'use_jquery'            => true,
        'use_bootstrap_scripts' => false,
        'use_nav_bar'           => true,
        'use_footer'            => false,
    ];
    return view('RecoverAccount', compact('page_details'));
})->middleware('guest');

Route::post('/forgot', 'UserController@recover_account')->middleware('guest');

Route::get('/recoveraccount', function () {
    if (!session()->has('user'))
    {
        return redirect('/forgot');
    }
    $page_details = [
        'page_name'             => '| reset account',
        'css_file_name'         => 'login',
        'js_file_name'          => '',
        'use_jquery'            => true,
        'use_bootstrap_scripts' => false,
        'use_nav_bar'           => true,
        'use_footer'            => false,
    ];
    return view('EnterCode', compact('page_details'));
})->middleware('guest');

Route::post('recoveraccount', 'UserController@check_recover_code')->middleware('guest');
Route::put('recoveraccount', 'UserController@change_password')->middleware('guest');
Route::get('recover/resendcode/{id}', 'UserController@resend_recover_code')->middleware('guest');
Route::get('/email/recover/{token?}', 'UserController@check_reset_email')->middleware('guest');

//Logout route.
Route::get('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->middleware('user');

//contact us route
Route::get('/contact', function () {
    $page_details = [
        'page_name'             => ' ',
        'css_file_name'         => 'contact',
        'js_file_name'          => '',
        'use_jquery'            => true,
        'use_bootstrap_scripts' => true,
        'use_nav_bar'           => true,
        'use_footer'            => false,
    ];
    return view('contact', compact('page_details'));
});

Route::post('/contact', 'ContactController@index');

//Change language Route
Route::get('lang/{lang?}', function ($lang) {
    if ($lang === 'ar'):
        session()->put('lang', 'ar');
        return back();
    endif;
    session()->forget('lang');
    return back();
});

//products routes
Route::get('get-products', 'DashboardController@get_products');

Route::get('get-product-by-id/{id?}', 'DashboardController@get_product_by_id');

Route::post('addproduct', 'DashboardController@add_product')->middleware('admin');

Route::post('edit-product', 'DashboardController@edit_product')->middleware('admin');

Route::post('delete-product', 'DashboardController@delete_product')->middleware('admin');

//Dashboard view
Route::view('dashboard/{param?}', 'vue.dashboard')->middleware('admin');



//Testing Route
Route::get('test', 'DashboardController@get_products');