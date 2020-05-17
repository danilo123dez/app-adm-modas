<?php

namespace App\Http\Controllers;

use App\Mail\esqueciMinhaSenha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function esqueciSenha(Request $request){
        Mail::to('danfranceschi231@gmail.com')->send(new esqueciMinhaSenha($request->all()));
    }
}
