<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagementController extends Controller
{
    public function index()
    {
        // dd(User::all());
        return view('admin.management.index');
    }

    // search using ajax request
    public function search(Request $request)
    {
        if (!$request->ajax()) return 404;
    }
}
