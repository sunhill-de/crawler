<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SearchObjectsTableSeeder extends Seeder {
	
	public function run() {
	    DB::table('objects')->truncate();
	    DB::table('objects')->insert([
	        ['id'=>1,'classname'=>"dummy",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>2,'classname'=>"dummy",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>3,'classname'=>"dummy",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>4,'classname'=>"dummy",'created_at'=>'2019-05-15 10:00:00'],      
	        ['id'=>5,'classname'=>"searchtestA",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>6,'classname'=>"searchtestA",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>7,'classname'=>"searchtestA",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>8,'classname'=>"searchtestA",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>9,'classname'=>"searchtestA",'created_at'=>'2019-05-15 10:00:00'],	        
	        ['id'=>10,'classname'=>"searchtestB",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>11,'classname'=>"searchtestB",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>12,'classname'=>"searchtestB",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>13,'classname'=>"searchtestB",'created_at'=>'2019-05-15 10:00:00'],
	        ['id'=>14,'classname'=>"searchtestB",'created_at'=>'2019-05-15 10:00:00'],	        
	        ['id'=>15,'classname'=>"searchtestC",'created_at'=>'2019-05-15 10:00:00'],
	    ]);
	}
}