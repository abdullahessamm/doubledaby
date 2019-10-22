@include('assets.header')
@php
    $user       = App\User::where('id', session()->get('user'))->get()->first();
    $full_name  = $user->full_name;
    $device_en  = session('email') ?? 'phone';
    $device_ar  = session()->has('email') ? 'يرجى الرجوع إلى بريدك الإلكترونى للبحث عن رسالة نصية تحتوي على الرمز. يتكون الرمز من ‏٦‏ أرقام.' : 'يرجى الرجوع إلى هاتفك للبحث عن رسالة نصية تحتوي على الرمز. يتكون الرمز من ‏٦‏ أرقام.';
@endphp 

    <div class="login-bg">
        <img src="http://www.tysonkilmer.com/wp-content/uploads/2015/03/journey-login-background.png" alt="login background">
    </div>

    <div class="login-container {{session()->get('lang') === 'ar' ? 'text-right' : ''}}">
        <form style = "{{ session()->get('lang') === 'ar' ? 'direction:rtl' : '' }}" method="POST" >
            @csrf
            <input type="hidden" name="user_id" value=" {{ session('user') }} ">
            <label class="enter-code" for="inputPassword5"> {{ '@' . $full_name }}</label>
            <input type="text" placeholder="Code..." id="inputPassword5" class="form-control" name="code" aria-describedby="passwordHelpBlock">
            @if(session()->has('error'))
                <div class="verify-alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session()->get('error') }}
                </div>
            @endif
            @if(session()->has('success'))
                <div class="verify-alert-success">
                    <i class="fas fa-check-circle"></i> {{ session()->get('success') }}
                </div>
            @endif
            <a href="{{url('recover/resendcode')}}/{{ session('user') }}" class="resend-code"> {{ session()->get('lang') === 'ar' ? __('ar.verify_phone_resend_code') : 'Resend code to phone.' }} </a>
            <input type="submit" class="btn btn-danger verify-submit" value="{{session()->get('lang') === 'ar' ? __('ar.verify_phone_submit') : 'Submit'}}">
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{session()->get('lang') === 'ar' ? $device_ar : 'Please check your ' . $device_en . ' for a text message with your code. Your code is 6 characters in length.'}}
            </small>
        </form>
    </div>

@include('assets.footer')