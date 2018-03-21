<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except'=>['show', 'create', 'store', 'index']
        ]);

        $this->middleware('guest', [
           'only'=>['create']
        ]);
    }

    //用户登录页面
    public function create()
    {

        return view('users.create');
    }

    //用户详细信息展示
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //用户注册逻辑
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

    //用户信息修改页面
    public function edit(User $user)
    {
        //确定是否是当前用户
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //用户信息修改逻辑
    public function update(User $user, Request $request)
    {
        //确定是否是当前用户
        $this->authorize('update', $user);

        $this->validate($request, [
           'name'=>'required|max:50',
            'password'=>'nullable|confirmed|min:6'
        ]);

        //更新数据
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = $request->password;
        }
        $user->update($data);

        session()->flash('success', '更新个人资料成功');

        return redirect()->route('users.show', $user);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', ['users'=>$users]);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        Session()->flash('success', '删除用户成功');
        return redirect('/users');
    }

}
