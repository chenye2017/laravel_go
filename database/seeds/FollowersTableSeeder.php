<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::all();
        $first_user = User::first();
        $id = $first_user->id;
        $other_users = $users->slice(1);

        //这个用户关注其他所有的用户
        $other_users_id = $other_users->pluck('id')->toArray();

        $first_user->follow($other_users_id);

        //所有的用户都关注他
        foreach ($other_users as $u_key=>$u_value) {
            $u_value->follow($id);
        }
    }
}
