<?php

use App\Feed;
use App\Post;
use App\User;
use App\UserFeed;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $admin = User::create([
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
            'real_name' => 'Ron Rider',
        ]);

        $cbsFinance = Feed::create(['url' => 'http://www.cnbc.com/id/10000664/device/rss/rss.html']);
        $cbsBusiness = Feed::create(['url' => 'http://www.cnbc.com/id/10001147/device/rss/rss.html']);
        $fanGraphs = Feed::create(['url' => 'http://www.fangraphs.com/blogs/feed/']);

        UserFeed::create([
            'title' => 'CBS Finance',
            'user_id' => $admin->id,
            'feed_id' => $cbsFinance->id,
        ]);

        UserFeed::create([
            'title' => 'CBS Business',
            'user_id' => $admin->id,
            'feed_id' => $cbsBusiness->id,
        ]);

        UserFeed::create([
            'title' => 'FanGraphs',
            'user_id' => $admin->id,
            'feed_id' => $fanGraphs->id,
        ]);

        Artisan::call('passport:install');

        Post::reguard();
        Artisan::call('load:posts', ['ids' => '1,2,3']);
    }
}
