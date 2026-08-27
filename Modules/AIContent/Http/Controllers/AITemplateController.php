<?php

namespace Modules\AIContent\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AITemplateController extends Controller
{
    public function index()
    {
        return view('aicontent::index');
    }

    public function create()
    {
        return view('aicontent::create');
    }


    public function show($id)
    {
        return view('aicontent::show');
    }


    public function edit($id)
    {
        return view('aicontent::edit');
    }
}
