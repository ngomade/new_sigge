<?php

namespace App\Http\Controllers\concours;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{

    public function store(Request $request)
    {
        $res = Message::create($request->all());
        return response()->json("OK");
    }
}
