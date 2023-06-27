<?php
namespace app\controller;

use app\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use test\testTp;
use think\facade\Db;

class Index extends BaseController
{
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
	
	public function dl_index()
	{
		$spreadsheet = IOFactory::load(__DIR__.'/../../exel/6.191/phone.xlsx');
		
		$worksheet = $spreadsheet->getActiveSheet();
		$highestRow = $worksheet->getHighestRow();
		$highestColumn = $worksheet->getHighestColumn();
		
		$data = array();
		
		for ($row = 1; $row <= $highestRow; $row++) {
			$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			$data[] = $rowData[0];
		}
		
//		$selectUser = Db::table('cloud_user')->where(['tel'=>13918971056])->find();
//		if ($selectUser){
//			if ($selectUser['is_member'] == 1 && $selectUser['is_pay_member'] == 2){
//				echo $selectUser['tel'].'是会员';
//				echo '<br>';
//			}else{
//				Db::table('cloud_user')->where(['id'=>$selectUser['id']])->update(['is_member'=>1,'pay_member_start_time'=>time(),'is_pay_member'=>2]);
//				$findVIP = Db::table('cloud_install_vip_backup')->where(['datetime'=>'20230613','tel'=>$selectUser['tel']])->find();
//				if (!$findVIP){
//					Db::table('cloud_install_vip_backup')->insert(['user_id'=>$selectUser['id'],'tel'=>$selectUser['tel'],'datetime'=>'2023063','created_time'=>time()]);
//				}
//			}
//		}else{
//			echo $selectUser['tel'].'没有注册';
//		}

//		foreach ($data as $row) {
//			foreach ($row as $cell) {
//				echo $cell;
//				echo '<br>';
//			}
//		}
//        die();
//		 输出数据
		foreach ($data as $row) {
			foreach ($row as $cell) {
				$selectUser = Db::table('cloud_user')->where(['tel'=>$cell])->find();
				if ($selectUser){
					if ($selectUser['is_member'] == 1 && $selectUser['is_pay_member'] == 2){
						echo $selectUser['tel'].'是会员';
						echo '<br>';
					}else{
						Db::table('cloud_user')->where(['id'=>$selectUser['id']])->update(['is_member'=>1,'pay_member_start_time'=>time(),'is_pay_member'=>2]);
						$findVIP = Db::table('cloud_install_vip_backup')->where(['datetime'=>date('Ymd'),'tel'=>$cell])->find();
						if (!$findVIP){
							Db::table('cloud_install_vip_backup')->insert(['user_id'=>$selectUser['id'],'tel'=>$cell,'datetime'=>date('Ymd'),'created_time'=>time()]);
						}
					}
				}else{
					echo $cell.'没有注册';
					echo '<br>';
				}
			}
		}
		echo "成功";
	}
	
}
