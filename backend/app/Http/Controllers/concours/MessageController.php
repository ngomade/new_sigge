<?php

namespace App\Http\Controllers\concours;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $res = Message::create($request->all());

        return response()->json('OK');
    }
}
