<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Log;

class Task extends Command
{
	protected function configure()
	{
		//设置名称为task
		$this->setName('task')
			//增加一个命令参数
			->addArgument('action', Argument::OPTIONAL, 'action', '')
			->addArgument('force', Argument::OPTIONAL, 'force', '');
	}
	
	protected function execute(Input $input, Output $output)
	{
		//获取输入参数
		$action = trim($input->getArgument('action'));
		$force = trim($input->getArgument('force'));
		
		//配置任务，每隔20秒访问2次网站
//		$task = new \EasyTask\Task();
//		$task->setRunTimePath('./runtime/');
//		$task->addFunc(function () {
//			$url = 'https://www.gaojiufeng.cn/?id=327';
//			file_get_contents($url);
//		}, 'request', 20, 2);;
		
		$task = new \EasyTask\Task();
		$task->addFunc(function () {
			$cmd = './public/shell/test.sh';
			$ret2 = exec($cmd);
			Log::write($ret2,'error');
			Log::write(1111,'error');
		}, 'exec', 2);
		$task->setDaemon(true);//守护进程
		
		
		// 根据命令执行
		if ($action == 'start') {
			$task->start();
		} elseif ($action == 'status') {
			$task->status();
		} elseif ($action == 'stop') {
			$force = ($force == 'force'); //是否强制停止
			$task->stop($force);
		} else {
			exit('Command is not exist');
		}
	}
}
