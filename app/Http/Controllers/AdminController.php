<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $admin = DB::table('admins')
            ->where(['username' => $request->username])
            ->first();

        if($admin == null || !Hash::check($request->password, $admin->password))
        {
            return response("login failed", 400);
        }

        return response($admin->id, 200);
    }
}
