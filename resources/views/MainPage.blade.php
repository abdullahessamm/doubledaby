<?php
    //Get session language
    $session_lang = session()->get('lang');
?>


@include('assets.header')

@if(session()->get('lang') === 'ar')
    <style>
        .direction{direction:rtl}
    </style>
@endif

<!-- Cove section -->
<section class="cover-container">
    <div class="over-cover">
        <div class="cover-upper">
                
            <h4 class="{{session()->get('lang') === 'ar' ? 'direction' : ''}}">
                @if(session()->get('lang') === 'ar')
                    @lang('ar.homeHeader')
                @else
                    <span> If </span> you are reading this words thats's mean that you are in the right place
                @endif
            </h4>
        </div>
        <div class="cover-footer">
            <div></div>
        </div>
    </div>
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src=" {{asset('images/cover.jpg')}} " class="d-block w-100 cover-img" alt="Cover">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-container {{session()->get('lang') === 'ar' ? 'direction' : ''}}">
    <section class="features text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <i class="fas fa-thumbs-up fa-5x"></i>
                    <div class="disc">
                        <h2> {{session()->get('lang') === 'ar' ? __('ar.features')['styles'] : 'Latest styles'}} </h2>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae nam repudiandae laboriosam consectetur illo earum, blanditiis eius veritatis suscipit quaerat officiis iure aliquam modi, unde ducimus iste quasi delectus saepe?
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-truck fa-5x"></i>
                    <div class="disc">
                        <h2> {{session()->get('lang') === 'ar' ? __('ar.features')['delivery'] : 'Delivery service'}} </h2>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae nam repudiandae laboriosam consectetur illo earum, blanditiis eius veritatis suscipit quaerat officiis iure aliquam modi, unde ducimus iste quasi delectus saepe?
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-money-check-alt fa-5x"></i>
                    <div class="disc">
                        <h2> {{session()->get('lang') === 'ar' ? __('ar.features')['payment'] : 'Online payment'}} </h2>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae nam repudiandae laboriosam consectetur illo earum, blanditiis eius veritatis suscipit quaerat officiis iure aliquam modi, unde ducimus iste quasi delectus saepe?
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<section class="who-are-we">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
               <div class="define"></div>
               <div class="cont text-center">
                    <h2> {{session()->get('lang') === 'ar' ? __('ar.why') : 'Why DoubleDaby'}} </h2>
                    <p class="lead"> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolores similique error, rerum aspernatur doloremque numquam nihil earum animi voluptatum impedit blanditiis quo neque eligendi magni, vitae velit! Assumenda, quibusdam nemo? </p>
                    <a class="btn btn-danger" title="Contact us" href="{{ url('contact') }}"> {{session()->get('lang') === 'ar' ? __('ar.feedback') : 'Give us feedback'}} </a>
                </div>
            </div>
            <div class="col-md-5 text-right">
                <img src="https://cdn1.iconfinder.com/data/icons/ninja-4/512/ninja_avatar_emoji_emoticon_thumbs_up_wink-512.png" alt="avatar">
            </div>
        </div>
    </div>
</section>

<section class="latest text-center">
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-md-4 col-sm-6 text-center">
                <div class="card" style="width: 18rem;">
                    <img src=" {{ asset('images/card1.jpg') }} " class="card-img-top" alt="card">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-danger" style="direction: {{ session()->get('lang') === 'ar' ? 'rtl' : 'ltr' }}"> {{session()->get('lang') === 'ar' ? __('ar.order') : 'Order Now!'}} </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('images/card2.jpg') }}" class="card-img-top" alt="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn btn-danger" style="direction: {{ session()->get('lang') === 'ar' ? 'rtl' : 'ltr' }}"> {{session()->get('lang') === 'ar' ? __('ar.order') : 'Order Now!'}} </a>
                        </div>
                    </div>
            </div>
            <div class="col-md-4 col-sm-6 text-center">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('images/card3.jpg') }}" class="card-img-top" alt="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn btn-danger" style="direction: {{ session()->get('lang') === 'ar' ? 'rtl' : 'ltr' }}"> {{session()->get('lang') === 'ar' ? __('ar.order') : 'Order Now!'}} </a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>
<img src=" {{asset('images/background.jpg')}} " class="bg-img" alt="background">

@include('assets.footer')