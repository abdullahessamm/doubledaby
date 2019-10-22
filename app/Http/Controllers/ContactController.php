<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactMessages;

class ContactController extends Controller
{
    public function __construct()
    {}

    public function index(Request $request)
    {
        $request->validate([
            'first_name'        => 'required|string|min:3|max:20',
            'last_name'         => 'required|string|min:3|max:20',
            'email'             => 'required|email|max:100',
            'title'             => 'required|string|min:3|max:100',
            'subject'           => 'required|string|min:10|max:200'
        ], [
            'first_name.min'    => "This field has errors.",
            'first_name.max'    => "This field has errors.",
            'last_name.min'     => "This field has errors.",
            'last_name.min'     => "This field has errors.",
            'title.min'         => "This field can't be less than 3 charactars.",
            'title.max'         => "This field can't be more than 100 charactars.",
            'subject.min'       => "This field can't be less than 10 charactars.",
            'subject.max'       => "This field can't be more than 200 charactars.",                                    
        ]);

        $full_name = ucfirst($request->first_name) . ' ' . ucfirst($request->last_name);
        ContactMessages::create([
            'full_name'     => $full_name,
            'email'         => $request->email,
            'title'         => $request->title,
            'subject'       => $request->subject
        ]);

        return back()->with('status', 'success');

    }
}
