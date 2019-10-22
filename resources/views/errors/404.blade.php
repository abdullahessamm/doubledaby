@php

$page_details = [
        'page_name'             => '| Server Error',
        'css_file_name'         => 'errors/500',
        'js_file_name'          => '',
        'use_jquery'            => true,
        'use_bootstrap_scripts' => true,
        'use_nav_bar'           => true,
        'use_footer'            => false,
    ];

@endphp

@include('assets.header')
    
    <div class="error-500-container">
        <h3> <span>404 |</span> Page not found </h3>
        <p>
            <strong> "{{ substr($_SERVER['REQUEST_URI'], 1) }}" </strong> you are looking for not found.
        </p>
    </div>

@include('assets.footer')