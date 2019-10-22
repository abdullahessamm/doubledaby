@include('assets.header')


    <div class="login-bg">
        <img src="http://www.tysonkilmer.com/wp-content/uploads/2015/03/journey-login-background.png" alt="login background">
    </div>

    <div class="login-container">
        <form style = "{{ session()->get('lang') === 'ar' ? 'direction:rtl' : '' }}" method="POST">
            <h2 class="text-center"> {{ session()->get('lang') === 'ar' ? __('ar.navbarLinks')['login'] : 'Login' }} </h2>
            @csrf
            <div class="alert alert-danger text-center" style=" {{ session()->has('mustLogin') ? 'display:block;opacity:1' : '' }} " role="alert">
                {{ session()->get('mustLogin') }}
            </div>
            <div> <input type="text" name="username" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.username') : 'Username' }}..."> </div>
            <div> <input type="password" name="password" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.password') : 'Password' }}..."> </div>
            <div class="text-center button"> <button name="btn" type="submit" class="btn btn-danger"> <span> <i class="fas fa-sign-in-alt"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.login') : 'Login' }} </button> </div>
            <div class="text-center forgot"> <a href=" {{ url('/forgot') }} "> {{ session()->get('lang') === 'ar' ? __('ar.forgot') : 'Forgot your password?' }} </a> </div>
        </form>
    </div>

<script>
    window.onload = function () {

    function showFlashAlert (value = null) {
        document.querySelector('.alert').style.display = 'block';
        document.querySelector('.alert').innerText = value;
        setTimeout(()=>document.querySelector('.alert').style.opacity = '1', 200);
        setTimeout(()=>{
            document.querySelector('.alert').style.opacity = '0';
            setTimeout(()=>document.querySelector('.alert').style.display = 'none', 500);
        }, 6000);
    }

    var form  = document.querySelector('.login-container form'),
        xhttp = new XMLHttpRequest();
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        e.target.btn.innerHTML = `<div class="spinner-border text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                    </div> {{ session()->get('lang') === 'ar' ? __('ar.loading') : 'Loading' }}
                                `;
        e.target.btn.setAttribute('disabled', 'true');
        var username = e.target.username.value,
            password = e.target.password.value,
            token = e.target._token.value;
        xhttp.open('post', `{{ url('/login') }}?username=${username}&password=${password}&_token=${token}`);
        xhttp.send();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var response = JSON.parse(this.response);
                if (response.status === 'error') {
                    let err = "{{ session()->get('lang') === 'ar' ? __('ar.some_thing_error') : 'Some thing error' }}!";
                    showFlashAlert(err);
                    e.target.btn.innerHTML = `<span> <i class="fas fa-sign-in-alt"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.login') : 'Login' }}`;
                    e.target.btn.removeAttribute('disabled');
                } else if (response.status === "Username Error") {
                    let err = "{{ session()->get('lang') === 'ar' ? __('ar.username_error') : 'Username error' }}!";
                    showFlashAlert(err);
                    e.target.btn.innerHTML = `<span> <i class="fas fa-sign-in-alt"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.login') : 'Login' }}`;
                    e.target.btn.removeAttribute('disabled');
                } else if ( response.status === "Password Error" ) {
                    let err = "{{ session()->get('lang') === 'ar' ? __('ar.password_error') : 'Password error' }}!";
                    showFlashAlert(err);
                    e.target.btn.innerHTML = `<span> <i class="fas fa-sign-in-alt"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.login') : 'Login' }}`;
                    e.target.btn.removeAttribute('disabled');
                } else {
                    window.location.reload();
                }
            } else if (this.readyState === 4 && this.status === 500) {
                let err = "{{ session()->get('lang') === 'ar' ? __('ar.server_error') : 'Server error' }}!";
                showFlashAlert(err);
                e.target.btn.innerHTML = `<span> <i class="fas fa-sign-in-alt"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.login') : 'Login' }}`;
                e.target.btn.removeAttribute('disabled');
            }
        };
    });
};
</script>
</body>