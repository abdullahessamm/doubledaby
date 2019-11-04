<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{config('app.name')}} {{ $page_details['page_name'] }} </title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans+Narrow&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href=" {{ asset('css/bootstrap.min.css') }} ">
    @if($page_details['use_nav_bar'])
        <link rel="stylesheet" href=" {{ asset('css/header.css') }} ">
        <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="{{ asset('css/' . $page_details['css_file_name'] . '.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
</head>
<body>

    @auth
    @php
        $has_phone = auth()->user()->phone;
    @endphp
    @if(!$has_phone)
    <div class="add-phone" style=" {{session()->get('lang') === 'ar' ? 'direction:rtl' : ''}} ">
            <div class="container-fluid"> 
                <div class="row">
                    <div class="col-md-6 {{session()->get('lang') === 'ar' ? 'text-right' : ''}}">
                        {{session()->get('lang') === 'ar' ? __('ar.title') : 'Add your phone for better service'}}
                    </div>
                    <div class="col-md-6 {{session()->get('lang') === 'ar' ? '' : 'text-right'}}">
                        <form method="POST" action="{{ url('/addphone') }}">
                            @csrf
                            <span> {{ session()->get('error') }} </span>
                            <input type="text" name="phone" placeholder="{{ session()->get('lang') === 'ar' ? 'الهاتف' : 'phone' }}..."/>
                            <span class="close-upper"> <i class="fas fa-times"></i> </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endauth
    @if($page_details['use_nav_bar'])
    <section class="navigation-bar text-center">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">Double <span> Daby </span></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto {{ session()->get('lang') }}">
                        <li class="nav-item {{ !$page_details['page_name'] ? 'active' : '' }} ">
                            <a class="nav-link" href=" {{url('/')}} ">
                                {{ session()->get('lang') === 'ar' ? __('ar.navbarLinks')['home'] : 'Home' }} 
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $page_details['page_name'] === ' ' ? 'active' : '' }}">
                            <a class="nav-link" href=" {{url('/contact')}} ">{{ session()->get('lang') === 'ar' ? __('ar.navbarLinks')['contactus'] : 'Contact us' }}</a>
                        </li>
                        <li class="nav-item {{ $page_details['page_name'] === '| Blogs' ? 'active' : '' }}">
                            <a class="nav-link" href="#">{{ session()->get('lang') === 'ar' ? __('ar.navbarLinks')['about'] : 'Blogs' }}</a>
                        </li>
                        @guest
                        @if($page_details['page_name'] !== '| Login')
                            <li class="nav-item">
                                <a class="nav-link" href=" {{ url('/login') }} "> <i class="fas fa-sign-in-alt"></i> {{ session()->get('lang') === 'ar' ? __('ar.navbarLinks')['login'] : 'Login' }} </a>
                            </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href=" {{ url('/signup') }} "> <i class="fas fa-user-plus"></i> {{ session()->get('lang') === 'ar' ? __('ar.navbarLinks')['signup'] : 'Signup' }}</a>
                        </li>
                        @endif
                        @endguest
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ auth()->user()->full_name }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @if(auth()->user()->level === 'admin')
                                        <a class="dropdown-item" href="{{url('dashboard')}}"> Dashboard </a>
                                    @endif
                                    
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{url('/logout')}}" style="color:#f00"> Logout </a>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
            
    </section>

    @endif
