<?php
declare (strict_types = 1);

namespace app\service;

use test\testTp as test;
use think\Service;

class FileSystemService extends Service
{

//	public $bind = [
//		't' => testTp::class
//	];
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
    	$this->app->bind('t',test::class);
    	//手动修改的
	    //echo 'FileSystemService-register()';
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
    	//手动修改的
	    //echo 'FileSystemService-boot()';
    }
}
