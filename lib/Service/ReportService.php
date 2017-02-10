<?php
namespace OCA\Report\Service;

use OC\Encryption\Keys\Storage;
use OCP\Files\Folder;
use OCP\IServerContainer;

class ReportService{
	private $container;
	private $rootFolder;
	private $userFolder;
	private $usermanager;
	private $userIdTable;
	
	public function __construct(IServerContainer $serverContainer){
		$this->finder = $finder;
		$this->container = $serverContainer;
		$this->rootFolder = $this->container->getRootFolder();
		$this->userFolder = $this->container->getUserFolder();
		
		$temp = [];
		$this->usermanager = $this->container->getUserManager()->callForAllUsers(function(&$temp) {
			arr
		});
	}
	
	public function generateReport(){
		$table = $this->userFolder->search("");
		$temp;
		foreach ($table as $index => $record){
			$temp[$index] = $record->getPath();
		}
		return $temp;
	}
	
}