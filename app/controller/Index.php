<?php
namespace app\controller;

use app\BaseController;
use test\testTp;

class Index extends BaseController
{
    public function index()
    {
        return '11122';
    }

    public function hello($name = 'ThinkPHP611')
    {
        return 'hello,' . $name;
    }
    
    public function testTp()
    {
    	bind('t', testTp::class);
	    $cache = app('t')->getS1();
    	echo $cache;
    }
}
