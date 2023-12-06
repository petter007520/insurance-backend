<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建10条测试数据
        $articles = factory(\App\Article::class,10)->make();

        \App\Article::insert( $articles->toArray() );

    }
}
