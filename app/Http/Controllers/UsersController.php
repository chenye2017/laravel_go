<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    //
    public function create()
    {

        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        //验证数据的正确性
        $this->validate($request, [
           'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:255',
            'password'=>'required|confirmed|min:6'
        ]);

        //保存数据
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        //保存成功信息
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

        //实现登陆
        Auth::login($user);

        //重定向
        return redirect()->route('users.show', [$user]);

        return ;
    }
}
