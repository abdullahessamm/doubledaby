<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AccountRecover;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Mail\ResetMail;

class UserController extends Controller
{
    //


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  =>  'required|string|min:3',
            'password'  =>  'required|string|min:8'
        ]);
        
        if ( $validator->fails() ) {
            return json_encode([
                'status' => 'error'
            ]);
        } else {

            $users = User::all();
            foreach ($users as $user) {
                if ($user->username === $request->username) {
                    if (\Hash::check($request->password, $user->password)) {
                        auth()->attempt(['username' => $request->username, 'password' => $request->password]);
                        return json_encode([
                            'status'  =>  'Login Success'
                        ]);
                    } else {
                        return json_encode([
                            'status'  =>  'Password Error'
                        ]);
                    } //End if
                    break;
                } //End if
            } // End foreach
            return json_encode([
                'status'  =>  'Username Error'
            ]);
        } //End if

    }

    public function signup(Request $request)
    {

        $messages = [
            'full_name.min'     => session()->get('lang') === 'ar' ? __('ar.full_name_min') : 'The full name must be at least 6 characters',
            'email.unique'      => session()->get('lang') === 'ar' ? __('ar.email_unique') : 'The email has already been taken',
            'username.unique'   => session()->get('lang') === 'ar' ? __('ar.username_unique') : 'The username has already been taken',
            'username.min'     => session()->get('lang') === 'ar' ? __('ar.username_min') : 'The username must be at least 6 characters',
            'password.min'     => session()->get('lang') === 'ar' ? __('ar.password_min') : 'The password must be at least 8 characters',
        ];

        $validator = Validator::make($request->all(), [
            'full_name'         => 'required|string|min:6|max:100',
            'username'          => 'required|string|unique:users|min:6|max:100',
            'email'             => 'required|email|unique:users|max:100',
            'password'          => 'required|min:8|string|max:100',
            'confirm_password'  => 'required|string|max:100',
            'gender'            => 'required|in:male,female'
        ], $messages);
        if ($validator->fails()) {
            return json_encode([
                    'validator_messages' => $validator->messages(),
                    'status'             => 'error'
                ]);
        } else {
            if ($request->password !== $request->confirm_password) {
                return json_encode([
                    'status'             => 'error',
                    'confpassword_err'   => session()->get('lang') === 'ar' ? __('ar.pass_conf') : 'Passwords not matched',
                    'validator_messages' => 'Passwords not matched'
                ]);
            }
            $user = User::create([
                'full_name'     => $request->full_name,
                'username'      => $request->username,
                'email'         => $request->email,
                'password'      => bcrypt( $request->password ),
                'gender'        => $request->gender,
                'level'         => 'user'
            ]);
            auth()->loginUsingId($user->id);
            return json_encode([
                'status'             => 'success'
            ]);
        }
    }
    public function add_phone()
    {

        $phone = request()->phone;
     
        if (strpos($phone, '+20') !== false)
        {
            if ( strpos($phone, '+20') === 0 )
            {
                $phone = substr($phone, 3);
            }
        }
        if (strpos($phone, '20') !== false)
        {
            if ( strpos($phone, '20') === 0 )
            {
                $phone = substr($phone, 2);
            }
        }
        if (strpos($phone, '0') !== false)
        {
            if ( strpos($phone, '0') === 0 )
            {
                $phone = substr($phone, 1);
            }
        }

        $phone = '+20' . $phone;


        $validator = Validator::make(['phone'=>$phone], [
            'phone'  =>  'required|unique:users|numeric|regex:/(01)[0-9]{9}/'
        ], [
            'phone.required'  =>  session()->get('lang') === 'ar' ? __('ar.err_required') : 'Please type your phone first',
            'phone.unique'  =>  session()->get('lang') === 'ar' ? __('ar.err_unique') : 'The phone you entered is already in use',
            'phone.numeric'  =>  session()->get('lang') === 'ar' ? __('ar.err_regex') : 'The phone you entered is invalid',
            'phone.regex'  =>  session()->get('lang') === 'ar' ? __('ar.err_regex') : 'The phone you entered is invalid',
        ]);
        
        if ($validator->passes()){
            $verify_code = rand(100000, 999999);
            $user = auth()->user();
            $user->phone = $phone;
            $user->phone_added_at = time();
            $user->verify_phone_code = $verify_code;
            $user->save();
            $sid    = env( 'TWILIO_SID' );
            $token  = env( 'TWILIO_TOKEN' );
            $client = new Client($sid, $token);
            $client->messages->create($phone, [
                'from'  => env( 'TWILIO_FROM' ),
                'body'  => $verify_code
            ]);
            return redirect('/verifyphone' . '/' . $user->id);
        } else {
            $err = json_decode($validator->messages())->phone[0];
            return back()->with('error', $err);
        }
    }
    public function verify_phone_view($user_id = null)
    {
        if ($user_id){
            $user = User::where('id', $user_id)->get()->first();
            if ($user){
                if (!$user->phone_verified_at && $user_id == auth()->user()->id)
                {
                    $page_details = [
                        'page_name'             => '| verify phone',
                        'css_file_name'         => 'login',
                        'js_file_name'          => 'verifyPhone',
                        'use_jquery'            => true,
                        'use_bootstrap_scripts' => true,
                        'use_nav_bar'           => true,
                        'use_footer'            => false
                    ];
                    return view('VerifyPhone', compact('page_details' ,'user_id'));
                }
            }
        }
        return redirect('/');
    }

    public function verify_phone(Request $request)
    {
        $user = auth()->user();
        if ( $request->code == $user->verify_phone_code )
        {
            $user->verify_phone_code = null;
            $user->phone_verified_at = time();
            $user->save();
            return redirect('/')->with('status', session()->get('lang') === 'ar' ? __('ar.verify_phone_success') : 'Phone verified successfully');
        } else
        {
            return back()->with('error', session()->get('lang') === 'ar' ? __('ar.verify_phone_invalid_code') : 'Invalid code');
        }
    }

    public function Resend_code()
    {
        $user           = auth()->user();
        $user_number    = $user->phone;
        $sid            = env( 'TWILIO_SID' );
        $token          = env( 'TWILIO_TOKEN' );
        $client         = new Client($sid, $token);
        $client->messages->create($user_number, [
            'from'  =>  env( 'TWILIO_FROM' ),
            'body'  =>  $user->verify_phone_code
        ]);

        return back()->with('success', session()->get('lang') === 'ar' ? __('ar.verify_phone_message-sent') : 'Message sent!');
    }

    public function recover_account(Request $request)
    {
        $input = $request->input;
        if (is_numeric($input))
        {
            $phone = $input;
            if (strpos($phone, '+20') !== false)
            {
                if ( strpos($phone, '+20') === 0 )
                {
                    $phone = substr($phone, 3);
                }
            }
            if (strpos($phone, '20') !== false)
            {
                if ( strpos($phone, '20') === 0 )
                {
                    $phone = substr($phone, 2);
                }
            }
            if (strpos($phone, '0') !== false)
            {
                if ( strpos($phone, '0') === 0 )
                {
                    $phone = substr($phone, 1);
                }
            }

            $phone = '+20' . $phone;


            $validator = Validator::make(['phone'=>$phone], [
                'phone'  =>  'required|numeric|regex:/(01)[0-9]{9}/'
            ], [
                'phone.required'  =>  session()->get('lang') === 'ar' ? __('ar.err_required') : 'Please type your phone first',
                'phone.numeric'  =>  session()->get('lang') === 'ar' ? __('ar.err_regex') : 'The phone you entered is invalid',
                'phone.regex'  =>  session()->get('lang') === 'ar' ? __('ar.err_regex') : 'The phone you entered is invalid',
            ]);

            if ($validator->passes())
            {
                $user = User::where('phone', $phone)->get()->first();
                if (!$user)
                {
                    return back()->with( 'error', session()->get('lang') === 'ar' ? __('ar.recover_account_phone_err') : 'The phone you entered not found' );
                }
                
                $check_if_user_id_exists = AccountRecover::where('user_id', $user->id)->get()->first();

                if ($check_if_user_id_exists)
                {
                    return redirect('/recoveraccount')->with([
                        'success' => session()->get('lang') === 'ar' ? __('ar.recover_err_message_sent') : 'Message already sent! please check your phone.',
                        'user'  => $check_if_user_id_exists->user_id()->first()->id,
                    ]);
                }

                $twilio_sid        = env( 'TWILIO_SID' );
                $twilio_token      = env( 'TWILIO_TOKEN' );
                $number     = $user->phone;
                $code       = rand(100000, 999999);
                $client     = new Client($twilio_sid, $twilio_token);
                $client->messages->create($number, [
                    'from'  =>  env( 'TWILIO_FROM' ),
                    'body'  =>  'Your reset code is ' . $code
                ]);
                AccountRecover::create([
                    'user_id'  =>  $user->id,
                    'code'     =>  $code
                ]);
                return redirect('/recoveraccount')->with('user', $user->id);
            } else{
                return back()->with( 'error', session()->get('lang') === 'ar' ? __('ar.recover_account_phone_err') : 'The phone you entered not found' );
            }
        } else
        {
            $email     = $input;
            $validator = Validator::make(['email' => $email], [
                'email'  =>  'required|email'
            ]);

            if ($validator->fails())
            {
                return back()->with('error', session('lang') === 'ar' ? __('ar.email_invalid') : 'Invalid e-mail.');
            }

            $user = User::where('email', $email)->get()->first();
            
            if ($user)
            {
                $recover_request = AccountRecover::where('user_id', $user->id)->get()->first();
                if ($recover_request)
                {
                    $recover_request->delete();
                }
                $code  = rand(100000, 999999);
                $token = \Str::random(200);
                AccountRecover::create([
                    'user_id' => $user->id,
                    'code'    => $code,
                    'recover_token'   => $token,
                ]);

                Mail::to( $user->email )->send( new ResetMail($user, $token, $code) );
                return redirect('/recoveraccount')->with('user', $user->id)->with('email', 'e-mail');

            }
            return back()->with('error', session('lang') === 'ar' ? __('ar.email_not_found') : 'E-mail not found.');
            
        }
    }

    public function check_reset_email($token = null, Request $request)
    {
        if ($token)
        {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string'
            ]);
            $user_id = $request->user_id;
            $recover_request = AccountRecover::where('user_id', $request->user_id)->get()->first();
            
            if ($validator->fails() || !$recover_request) { return redirect('/'); }
            
            if ($token !== $recover_request->recover_token) { return redirect('/'); }
            
            $page_details = [
                'page_name'             => '| reset account',
                'css_file_name'         => 'login',
                'js_file_name'          => 'newpassword',
                'use_jquery'            => true,
                'use_bootstrap_scripts' => false,
                'use_nav_bar'           => true,
                'use_footer'            => false,
            ];
            $from_email = 'action=' . url('/recoveraccount');
            return view('NewPassword', compact('page_details', 'user_id', 'from_email'));
        }
    }

    public function check_recover_code(Request $request)
    {
        //return $request;
        $roles = [
            'code'  => 'required|numeric',
            'user'  => 'string'
        ];

        $messages = [
            'code.required' => session()->get('lang') === 'ar' ? __('ar.verify_phone_title') : 'Enter security code.',
            'code.numeric'  => session()->get('lang') === 'ar' ? __('ar.verify_phone_invalid_code') : 'Invalid code.',
        ];

        
        $validator = Validator::make($request->all(), $roles, $messages);

        if ( $validator->fails() )
        {
            $error = json_decode($validator->messages())->code[0];
            return back()->with([
                'user'      => $request->user_id,
                'error'     => $error,
            ]);
        }

        $user_id         = $request->user_id;
        $recover_request = AccountRecover::where('user_id', $user_id)->get()->first();
        if ($request->code === $recover_request->code)
        {
            $page_details = [
                'page_name'             => '| reset account',
                'css_file_name'         => 'login',
                'js_file_name'          => 'newpassword',
                'use_jquery'            => true,
                'use_bootstrap_scripts' => false,
                'use_nav_bar'           => true,
                'use_footer'            => false,
            ];

            return view('NewPassword', compact('page_details', 'user_id'));
        }
        else
        {
            return back()->with([
                'user'      => $user_id,
                'error'     => session()->get('lang') === 'ar' ? __('ar.verify_phone_invalid_code') : 'Invalid code.',
            ]);
        }

    }


    public function resend_recover_code($id)
    {
        $recover_request = AccountRecover::where('user_id', $id)->get()->first();
        if ($recover_request)
        {
            $user           = $recover_request->user_id()->first();
            $user_number    = $user->phone;
            $sid            = env( 'TWILIO_SID' );
            $token          = env( 'TWILIO_TOKEN' );
            $client         = new Client($sid, $token);
            $client->messages->create($user_number, [
                'from'  =>  env( 'TWILIO_FROM' ),
                'body'  =>  $recover_request->code
            ]);
            return back()->with([
                'user'      => $user->id,
                'success'   => session()->get('lang') === 'ar' ? __('ar.verify_phone_message-sent') : 'Message sent!'
            ]);
        }
        return redirect('/forgot');
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'new_password'  => 'required|string|min:8'
        ]);

        $recover_request = AccountRecover::where('user_id', $request->user_id)->get()->first();
        $user            = $recover_request->user_id()->first();
        $recover_request->delete();
        $user->password = bcrypt($request->new_password);
        $user->save();
        auth()->loginUsingId($user->id);
        return redirect('/');
        
    }

}
