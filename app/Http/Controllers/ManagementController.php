<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function index()
    {
        // return all users data
        return view('admin.management.index');
    }

    // search using ajax request
    public function search(Request $request)
    {
        if (!$request->ajax()) return 404;
    }
}
