<?php
namespace OCA\Report\Service;

use OCA\Report\Db\FilesFinder;

class ReportService{
	private $finder;
	
	public function __construct(FilesFinder $finder){
		$this->finder = $finder;
	}
	
	public function generateReport(){
		$files = $this->finder->findAll();
		$mounts = $this->finder->findAllMounts();
		$users = $this->finder->findAllUsers();
		
		$storage_to_uid = array_column($mounts, 'user_id', 'storage_id');
		$uid_to_displayname = array_column($users, 'displayname', 'uid');
		$permissions = array_column($files, 'permissions');
		
		$table = ['xD'];
		
		
		foreach ($permissions as $index => $value){
			$permissions[$index] = $this->decodePermissions($value);
		}
		
		foreach ($files as $index => $file){
			$files[$index]['storage'] = $uid_to_displayname[$storage_to_uid[$files[$index]['storage']]]; 
			$files[$index]['read'] = $permissions[$index][0];
			$files[$index]['update'] = $permissions[$index][1];
			$files[$index]['create'] = $permissions[$index][2];
			$files[$index]['delete'] = $permissions[$index][3];
			$files[$index]['share'] = $permissions[$index][4];
			
		}
		
		return $files;
	}
	
	public function generateReportForUser($user){
		$files = $this->finder->findAll();
	}
	
	private function decodePermissions($permission){
		$values = decbin($permission);
		$val_array = str_split($values);
		
		foreach ($val_array as $index => $value){
			if($value === "1") {
				$val_array[$index] = 'yes';
			} else {
				$val_array[$index] = 'no';
			}
		}
		return array_reverse($val_array);
	}
}