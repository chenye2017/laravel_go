<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
           'only'=>['create']
        ]);
    }

    //注册页表单展示
    public function create()
    {
        return view('sessions.create');
    }

    //实际的登录逻辑,用户登录
    public function store(Request $request)
    {
        //验证参数
        $credentials = $this->validate($request, [
            'email' =>'required|max:255|email',
            'password'=>'required'
        ]);

        //是否匹配
        if (Auth::attempt($credentials, $request->has('remember'))) {
            session()->flash('success', '欢迎回来');

            return redirect()->intended(route('users.show', [Auth::user()]));
        } else {
            session()->flash('danger', '邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    //用户注销
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '退出成功');
        return redirect()->route('users.create');
    }
}
