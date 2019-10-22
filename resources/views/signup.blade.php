@include('assets.header')


    <div class="login-bg">
        <img src="http://www.tysonkilmer.com/wp-content/uploads/2015/03/journey-login-background.png" alt="login background">
    </div>

    <div class="login-container">
        <form style = "{{ session()->get('lang') === 'ar' ? 'direction:rtl' : '' }}" method="POST">
            <h2 class="text-center"> {{ session()->get('lang') === 'ar' ? __('ar.create_new_account') : 'Create new account' }} </h2>
            @csrf
            <div class="alert alert-danger text-center" role="alert">
                ...
            </div>
            <div class="full-name"> <input type="text" name="full_name" class="remove-border" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.full_name') : 'Full name' }}..." required> </div>
            <div> <input type="email" name="email" class="remove-border" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.email') : 'E-mail' }}..." required> </div>
            <div> <input type="text" name="username" class="remove-border" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.username') : 'Username' }}..." required> </div>
            <div class="passwords"> <input type="password" name="password" class="remove-border" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.new_password') : 'New password' }}..." required> <input type="password" name="confirm_password" class="remove-border" placeholder="{{ session()->get('lang') === 'ar' ? __('ar.confirm_password') : 'Confirm password' }}..." required> </div>
            <div class="gender">
                <h5 class=""> {{ session()->get('lang') === 'ar' ? __('ar.gender') : 'Gender' }}: </h5>
                <div>
                    <div><input type="radio" name="gender" value="male" id="male"> <label for="male"> {{ session()->get('lang') === 'ar' ? __('ar.male') : 'Male' }} </label></div>
                    <div><input type="radio" name="gender" value="female" id="female"> <label for="female"> {{ session()->get('lang') === 'ar' ? __('ar.female') : 'Female' }} </label></div>
                </div>
            </div>
            <div class="text-center button"> <button name="btn" type="submit" class="btn btn-danger"> <span> <i class="fas fa-user-plus"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.create_new_account') : 'Signup' }} </button> </div>
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

    function show_error(ele, err_msg, Class) {
        if (document.querySelector('.'+Class))
        {
            document.querySelector('.'+Class).remove();
        }
        var err_ele = document.createElement('div');
        err_ele.innerText = err_msg;
        err_ele.classList.add(Class);
        err_ele.style.cssText = 'color: #ff0011; margin-bottom: 5px';
        ele.style.borderColor = '#f00';
        ele.style.boxShadow = '0px 1px 10px #f00';
        if ( Class === 'password-err' || Class === 'confpassword-err' ) {
            ele = document.querySelector('.passwords');
        }
        
        ele.parentNode.insertBefore(err_ele, ele);
    }

    function clear_errors(Classes) {
        Classes.forEach(Class => {
            if ( document.querySelector('.' + Class) ){
                document.querySelector('.' + Class).remove();
            }
        });
        document.querySelectorAll('.remove-border').forEach(element=>{
            element.style.cssText = " border-color:#aaa; box-shadow:none; ";
        });
        document.querySelector('.login-container .gender > h5').classList.remove('gender-err');
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
        var full_name               = e.target.full_name.value,
            email                   = e.target.email.value,
            Username                = e.target.username.value,
            password                = e.target.password.value,
            confirm_password        = e.target.confirm_password.value,
            gender                  = e.target.gender.value,
            _token                  = e.target._token.value,
            data                    = `gender=${gender}&full_name=${full_name}&email=${email}&username=${Username}&password=${password}&confirm_password=${confirm_password}&_token=${_token}`;
        xhttp.open('post', `{{ url('/signup') }}?${data}`);
        xhttp.send();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                var errors = ['fullname-err', 'username-err', 'email-err', 'password-err', 'confpassword-err'];
                clear_errors(errors);
                e.target.password.value = '';
                e.target.confirm_password.value = '';
                var response = JSON.parse(this.response);
                if (response.status === 'error') {
                    if (response.validator_messages) {
                        if (response.validator_messages.full_name) {
                            show_error(e.target.full_name, response.validator_messages.full_name[0], 'fullname-err');
                        }
                        if (response.validator_messages.email) {
                            show_error(e.target.email, response.validator_messages.email[0], 'email-err');
                        }
                        if (response.validator_messages.username) {
                            show_error(e.target.username, response.validator_messages.username[0], 'username-err');
                        }
                        if (response.validator_messages.password) {
                            show_error(e.target.password, response.validator_messages.password[0], 'password-err');
                        }
                        if (response.confpassword_err) {
                            show_error(e.target.confirm_password, response.confpassword_err, 'confpassword-err');
                        }
                        if (response.validator_messages.gender) {
                            document.querySelector('.login-container .gender > h5').classList.add('gender-err');
                        }
                    }
                    e.target.btn.innerHTML = `<span> <i class="fas fa-user-plus"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.create_new_account') : 'Signup' }}`;
                    e.target.btn.removeAttribute('disabled');
                } else {
                    window.location.reload();
                }
            } else if (this.readyState === 4 && this.status === 500) {
                alert('Server error');
                e.target.btn.innerHTML = `<span> <i class="fas fa-user-plus"></i> </span> {{ session()->get('lang') === 'ar' ? __('ar.create_new_account') : 'Signup' }}`;
                e.target.btn.removeAttribute('disabled');
            }
        };
    });
};
</script>
</body>