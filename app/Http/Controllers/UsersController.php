<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except'=>['show', 'create', 'store', 'index', 'confirmEmail', 'sendEmailConfirmationTo']
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
        //所有的微博
        $statuses = $user->statuses()
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('users.show', compact('user', 'statuses'));
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
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~先去激活邮箱吧再来登陆');

        //实现登陆
        //Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        //发送邮件

        //重定向
        return redirect()->route('login');

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

    public function confirmEmail(Request $req)
    {
        $user = User::where('active_token', $req->token)->findOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        session()->flash('success', '账户激活成功');
        return redirect('users.show', ['user'=>$user->id]);
    }

    protected function sendEmailConfirmationTo($user)
    {
        $view = 'email.confirm';
        $data = compact('user');
        $from = 'cy@1967196626.com'; //.env文件里面已经配置了
        $name = 'yufatang';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function followings(User $user)
    {
        $users = $user->following()->paginate(30);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }





}
