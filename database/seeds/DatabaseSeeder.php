<?php
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MsgStatusTableSeeder::class);
        $this->call(UserTableSeeder::class);
    }
}

class MsgStatusTableSeeder extends Seeder {

    public function run()
    {
        DB::table('mc_msg_status')->delete();
        
        DB::table('mc_msg_status')->insert([
            ['id' => 1, 'name' => 'отправлено'],
            ['id' => 2, 'name' => 'дошло до сервера'],
            ['id' => 3, 'name' => 'прочтено']
        ]);
    }

}

class UserTableSeeder extends Seeder {

    public function run()
    {
        User::create([
            'name' => 'TestUser1',
            'email' => 'TestUser1@test.ru',
            'password' => Hash::make('TestUser1'),
        ]);
        User::create([
            'name' => 'TestUser2',
            'email' => 'TestUser2@test.ru',
            'password' => Hash::make('TestUser2'),
        ]);
    }

}