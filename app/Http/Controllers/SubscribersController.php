<?php

namespace App\Http\Controllers;

use App\Mail\SubsEmail;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class SubscribersController extends Controller
{
    public function sub(Request $request)
    {
        $this->validate($request, [
           'email' => 'required|email|unique:subscriptions'
        ]);

        $subs = Subscription::add($request->get('email'));
        $subs->generateToken();

//        Mail::to($subs)->send(new SubsEmail($subs));

        return redirect()->back()->with('status', 'Please check your e-mail!');
    }

    public function verify($token)
    {
        $subs = Subscription::where('token', $token)->firstOrFail();
        $subs->token = null;
        $subs->save();

        return redirect('/')->with('status', 'Your e-mail is comfird');
    }
}
