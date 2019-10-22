@include('assets.header')


<div class="contact-cover">
    <img src=" {{ asset('images/background.jpg') }} " alt="Cover image">
</div>

<div class="contact-container">
        @if(session()->has('status'))
            <div class="alert alert-success" role="alert">
                We receved your message successfuly. Thank you!
            </div>
        @endif
    <form method="post">
        <div class="row">
            <div class="col-sm-6 left-grid">
                <img src="https://tech-echo.com/wp-content/uploads/2016/04/apple-iphone.jpg">
                <div class="left-grid-container">
                    <h2> Contact us </h2>
                    <div class="social">
                        <div class="facebook"> <i class="fab fa-facebook-square"></i> <a href="https://www.facebook.com/abdo.elnegm.779"> facebook </a> </div>
                        <div class="twitter"> <i class="fab fa-twitter"></i> <a href="#"> twitter </a> </div>
                        <div class="instagram"> <i class="fab fa-instagram"></i> <a href="#"> instagram </a> </div>
                        <div class="whatsapp"> <i class="fab fa-whatsapp"></i> <a> 01151316180 </a> </div>
                    </div>
                    <div class="location"></div>
                </div>
            </div>
            <div class="col-sm-6 right-grid">
                <div class="right-grid-container">
                    @csrf
                    <div class="client-name">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="first_name" @error('first_name') class="input-error" @enderror placeholder="First name..." required>
                                    @error('first_name')
                                        <div class="error"> *{{ $message }} </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 last">
                                    <input type="text" @error('last_name') class="input-error" @enderror name="last_name" placeholder="Last name..." required>
                                    @error('last_name')
                                        <div class="error"> *{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="email">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="email" name="email" @error('email') class="input-error" @enderror placeholder="Email..." required>
                                    @error('email')
                                        <div class="error"> *{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="title">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="text" name="title" @error('title') class="input-error" @enderror placeholder="Title..." required>
                                    @error('title')
                                        <div class="error"> *{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="subject">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <textarea name="subject" @error('subject') class="input-error" @enderror placeholder="Subject..." maxlength="200"></textarea>
                                    @error('subject')
                                        <div class="error"> *{{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit">
                        <div class="container">
                            <div class="row text-center">
                                <div class="col-sm-12"> <input type="submit" value="Send" class="btn btn-danger"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('assets.footer')