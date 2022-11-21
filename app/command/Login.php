<?php
declare (strict_types=1);

namespace app\command;

use PhpAmqpLib\Connection\AMQPSSLConnection;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Login extends Command
{
	protected function configure()
	{
		// 指令配置
		$this->setName('login_msg')
			->setDescription('the login_msg command');
	}
	
	protected function execute(Input $input, Output $output)
	{
		$rwTimeout = 200;
		$heartBeat = 60;
		//建立connction
		$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'root', '123456', 'vhost', false, 'AMQPLAIN', null, 'en_US', 10.0, $rwTimeout, null, false,$heartBeat);
		//Channel
		$channel = $connection->channel();
	
		//声明队列名为：task_queue
		$queue_name = 'login_msg';
		
		$channel->queue_declare($queue_name, false, true, false, false);
		
		$callback = function ($msg) use ($output) {
			$output->writeln($msg->body);
			//写入数据库
			//todo
			echo 'received = ', $msg->body . "\n";
			//确认消息已被消费，从生产队列中移除
			$msg->ack();
		};
		
		//设置消费成功后才能继续进行下一个消费
		$channel->basic_qos(null, 1, null);
		
		//开启消费no_ack=false,设置为手动应答
		$channel->basic_consume($queue_name, '', false, false, false, false, $callback);
		
		
		//不断的循环进行消费
		while ($channel->is_open()) {
			$channel->wait();
		}
		
		//关闭连接
		$channel->close();
		$connection->close();
	}
}
