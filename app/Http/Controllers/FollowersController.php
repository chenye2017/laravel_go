<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    //关注就相当于在第三张表里面增加一条数据，所以是post
    public function store(Request $req)
    {
        $my = Auth::user();
        if ($my->id != $req->id) {
            $my->follow($req->user);
        } else {
            return redirect('/');
        }
        return redirect()->back();
    }

    //取消关注就相当于删除这条记录，所以是delete
    public function destroy(Request $req)
    {
        $my = Auth::user();
        if ($my->id != $req->id) {
            $my->unfollow($req->user);
        } else {
            return redirect('/');
        }

        return redirect()->back();
    }
}
