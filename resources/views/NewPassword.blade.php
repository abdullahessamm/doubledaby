@include('assets.header')
@php
    $user       = App\User::where('id', $user_id)->get()->first();
    $full_name  = $user->full_name;
@endphp 

    <div class="login-bg">
        <img src="http://www.tysonkilmer.com/wp-content/uploads/2015/03/journey-login-background.png" alt="login background">
    </div>

    <div class="login-container {{session()->get('lang') === 'ar' ? 'text-right' : ''}}">
        <form style = "{{ session()->get('lang') === 'ar' ? 'direction:rtl' : '' }}" method="POST" {{ $from_email ?? '' }}>
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="user_id" value=" {{ $user_id }} ">
            <label class="enter-code" for="inputPassword5"> {{ '@' . $full_name }}</label>
            <input type="password" placeholder=" {{ session('lang') === 'ar' ? __('ar.new_password') : 'New password' }}... " id="inputPassword5" class="form-control" name="new_password" aria-describedby="passwordHelpBlock">
            <input type="submit" class="btn btn-danger verify-submit" value="{{session()->get('lang') === 'ar' ? __('ar.verify_phone_submit') : 'Submit'}}" disabled style="cursor: not-allowed">
            <small id="passwordHelpBlock" class="form-text text-muted">
                {{session()->get('lang') === 'ar' ? __('ar.password_note') : 'Note : password must contain at least 8 charactars.'}}
            </small>
        </form>
    </div>

@include('assets.footer')