@include('assets.header')


    <div class="login-bg">
        <img src="http://www.tysonkilmer.com/wp-content/uploads/2015/03/journey-login-background.png" alt="login background">
    </div>

    
    <div class="login-container {{session()->get('lang') === 'ar' ? 'text-right' : ''}}">
        <form style = "{{ session()->get('lang') === 'ar' ? 'direction:rtl' : '' }}" method="POST">
            @csrf
            <label for="inputPassword5">{{session()->get('lang') === 'ar' ? __('ar.recover_account_title') : 'Type your e-mail or phone'}}</label>
            <input type="text" placeholder="{{session()->get('lang') === 'ar' ? __('ar.recover_account_placeholder') : 'E-mail or phone'}}" id="inputPassword5" class="form-control {{session()->has('error') ? 'recover-input-error' : ''}}" name="input" aria-describedby="passwordHelpBlock">
            @if(session()->has('error'))
                <div class="verify-alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session()->get('error') }}
                </div>
            @endif
            <input type="submit" class="btn btn-danger verify-submit" value="{{session()->get('lang') === 'ar' ? __('ar.verify_phone_submit') : 'Submit'}}">
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{session()->get('lang') === 'ar' ? __('ar.recover_account_note') : 'We will send a message to your e-mail or phone that contains recovering data.'}}
            </small>
        </form>
    </div>

@include('assets.footer')