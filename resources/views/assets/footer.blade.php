@if($page_details['use_footer'])
    <section class="footer">
        <div class="upper">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-left upper-left">
                        <h2> Double<span>Daby</span> </h2>
                        <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae porro nostrum fuga temporibus qui, quis voluptatem. Laudantium cumque ad explicabo temporibus delectus eum, soluta nisi, ullam facilis provident, aspernatur consequatur. </p>
                        
                    </div>
                    <div class="col-md-4 text-center upper-center">
                        <h2> Links </h2>
                        <ul>
                            <li>
                                <a href="{{ url('/') }}"> {{session()->get('lang') === 'ar' ? __('ar.navbarLinks')['home'] : 'Home Page'}} </a>
                            </li>
                            <li>
                                <a href="{{ url('/about') }}"> {{session()->get('lang') === 'ar' ? __('ar.navbarLinks')['about'] : 'About Page'}} </a>
                            </li>
                            <li>
                                <a href="{{ url('/contact') }}"> {{session()->get('lang') === 'ar' ? __('ar.navbarLinks')['contactus'] : 'Contact us page'}} </a>
                            </li>
                            <li>
                                <a href="{{ session()->get('lang') === 'ar' ? url('lang/en') : url('lang/ar') }}"> {{ session()->get('lang') === 'ar' ? 'English' : 'العربية' }} </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-right upper-right">
                        <h2> Follow us on </h2>
                        <div class="profiles">
                            <div><a href="#"><i class="fab fa-facebook-f"></i> Facebook</a></div>
                            <div><a href="#"><i class="fab fa-instagram"></i> Instagram</a></div>
                            <div><a href="#"><i class="fab fa-twitter"></i> Twitter</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lower text-center">
            <span><i class="far fa-copyright"></i> 2020 copyright </span>
            <a href=" {{ url('/') }} "> DoubleDaby.com </a>
            <span> | By Abdullah Essam </span>
        </div>
    </section>
@endif

@if($page_details['use_jquery'])
    <script src="{{ asset('js/jQuery.js') }}"></script>
@endif
@if($page_details['use_bootstrap_scripts'])
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endif
@if($page_details['js_file_name'] != '')
    <script src="{{ asset('js/' . $page_details['js_file_name'] . '.js') }}"></script>
@endif
</body>
</html>
