<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Status;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => []
        ]);
    }

    //发微博
    public function store(Request $req)
    {
        //验证数据
        $this->validate($req, [
           'content' => 'required|min:3|max:50'
        ]);

        $content = $req->content;
        //插入数据
        Auth::user()->statuses()->create(compact('content'));

        return redirect('/');
    }

    //删除微博
    public function destroy(Status $status)
    {

        $this->authorize('destroy', $status);

        $status->delete();
        session()->flash('success', '删除微博成功');
        return redirect()->back();
    }
}
