<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        $user = User::find(1);
        $user->name = 'cy';
        $user->email = '1967196626@qq.com';
        $user->password = bcrypt('password');
        $user->is_admin = true;
        $user->active_token = null;
        $user->active_status = true;
        $user->save();

    }
}
