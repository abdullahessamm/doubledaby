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
        <h3> <span>500 |</span> Internal Server Error </h3>
        <p>
            We're sorry, our server encountered an internal error while processing your request. Please try again later while we fix this issue.
        </p>
    </div>

@include('assets.footer')