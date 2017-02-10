<?php
namespace OCA\Report\Service;

use OC\Encryption\Keys\Storage;
use OCP\Files\Folder;
use OCP\IServerContainer;

class ReportService{
	private $container;
	
	public function __construct(IServerContainer $serverContainer){
		$this->finder = $finder;
		$this->container = $serverContainer;
	}
	
	public function generateReport(){
		return [['1', '2'],['3','4']];
	}
	
}