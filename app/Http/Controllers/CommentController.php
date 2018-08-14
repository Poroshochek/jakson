<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        return redirect()->back()->with('status', 'Comment will be added after validation =)');
    }
}
