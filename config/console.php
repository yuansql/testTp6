<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
use app\command\Login;
use app\command\Order;
use app\command\Task;
use app\command\TestLog;


return [
    // 指令定义
    'commands' => [
    	'task' => Task::class,
    	'test_log' => TestLog::class,
	    'login_msg' => Login::class,
	    'order_msg' => Order::class,
    ],
];
