@include('assets.header')


    <div class="login-bg">
        <img src="http://www.tysonkilmer.com/wp-content/uploads/2015/03/journey-login-background.png" alt="login background">
    </div>

    <div class="login-container {{session()->get('lang') === 'ar' ? 'text-right' : ''}}">
        <form style = "{{ session()->get('lang') === 'ar' ? 'direction:rtl' : '' }}" method="POST" action=" {{ url('/verifyphone') }} ">
            @csrf
            <label for="inputPassword5">{{session()->get('lang') === 'ar' ? __('ar.verify_phone_title') : 'Enter security code'}}</label>
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
            <a href="{{url('/resendcode')}}" class="resend-code"> {{ session()->get('lang') === 'ar' ? __('ar.verify_phone_resend_code') : 'Resend code.' }} </a>
            <input type="submit" class="btn btn-danger verify-submit" value="{{session()->get('lang') === 'ar' ? __('ar.verify_phone_submit') : 'Submit'}}">
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{session()->get('lang') === 'ar' ? __('ar.check_phone') : 'Please check your phone for a text message with your code. Your code is 6 characters in length.'}}
            </small>
        </form>
    </div>

@include('assets.footer')