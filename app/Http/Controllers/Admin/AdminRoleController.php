<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'role']);
            return $next($request);
        });

    }
    function list()
    {
        session(['sub_module_active' => 'role.list']);
        return view('admin.role.list');
    }
    function add()
    {
        session(['sub_module_active' => 'role.add']);
        return view('admin.role.add');
    }
}
