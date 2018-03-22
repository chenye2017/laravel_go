<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($user) {
           $user->active_token = str_random(30);
        });
    }

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->email)));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    /*public function getAllUsers()
    {
        return self::all();
    }*/

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    //根据倒叙取出该用户发的所有微博
    public function feed()
    {
        //所有关注的人
        $followings = $this->following->pluck('id')->toArray();
        array_push($followings, $this->id);
        return Status::whereIn('user_id', $followings)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    //获取所有的粉丝
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    //获取所有的追随者
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    //关注别人
    public function follow($users_id)
    {
        if (!is_array($users_id)) {
            $users_id = compact('users_id');
        }
        $this->following()->sync($users_id, false);
    }

    //取消关注别人
    public function unfollow($users_id)
    {
        if (!is_array($users_id)) {
            $users_id = compact('users_id');
        }
        $this->following()->detach($users_id);
    }

    //我的关注者里面是否有这个人
    public function isFollowing($user_id)
    {
        return $this->following()->get()->contains($user_id);
    }
}
