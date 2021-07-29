<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =[
            ['name'=>'alex','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'alex@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'alexander','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'alexander@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'Gorge','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'Gorge@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'Josh','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'Josh@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'Tyler','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'shuTylerrricanex@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'Bline','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'Bline@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'Sylveer','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'Sylveer@gmail.com','type'=>'student','status'=>'1'],
            ['name'=>'Syner','avatar_image'=>'user.png','password'=>hash::make('sarakorn'),'email'=>'Syner@gmail.com','type'=>'student','status'=>'1']
        ];

        foreach($users as $user){
            User::create($user);
        }
}

}
