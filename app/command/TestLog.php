<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Log;

class TestLog extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('test_log')
            ->setDescription('the testLog command');
    }

    protected function execute(Input $input, Output $output)
    {
	    //配置任务，每隔20秒访问2次网站
		$task = new \EasyTask\Task();
		$task->setRunTimePath('./runtime/');
		$task->addFunc(function () {
			Log::write('测试'.'===='.date("Y-m-d"),'error');
		}, 'exec', 2);
	    $task->setDaemon(true);//守护进程
		$task->start();
    }
}
