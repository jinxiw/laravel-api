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
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        // 头像假数据
        $avatars = [
            'https://cdn.learnku.com/uploads/avatars/1_1530614766.png!/both/100x100',
            'https://cdn.learnku.com/uploads/avatars/3668_1555257478.jpg!/both/100x100',
            'https://cdn.learnku.com/uploads/avatars/51394_1571815824.jpeg!/both/100x100',
            'https://cdn.learnku.com/uploads/avatars/24725_1547444352.png!/both/100x100',
            'https://cdn.learnku.com/uploads/avatars/40964_1568177104.jpg!/both/100x100',
            'https://cdn.learnku.com/uploads/avatars/49795_1570000299.jpg!/both/100x100',
        ];

        // 生成数据集合
        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index)
            use ($faker, $avatars)
            {
                // 从头像数组中随机取出一个并赋值
                $user->avatar = $faker->randomElement($avatars);
            });

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Jay';
        $user->email = '1217429730@qq.com';
        $user->avatar = 'https://cdn.learnku.com//uploads/communities/WtC3cPLHzMbKRSZnagU9.png';
        $user->save();

        // 初始化用户角色，将 1 号用户指派为『站长』
        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
