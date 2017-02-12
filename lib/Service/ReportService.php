<?php
namespace OCA\Report\Service;

use OCP\Files\Folder;
use OCP\IUserManager;
use OCP\IUser;
use OCP\IServerContainer;
use OCP\Share\IManager;


class ReportService{
	private $serverContainer;
	private $users;
	private $usersFolders;
	private $userManager;
	private $shareManager;
	private $shares;
	
	public function __construct(IServerContainer $serverContainer, IManager $shareManager){
		$this->serverContainer = $serverContainer;
		$this->shareManager = $shareManager;
		$this->userManager = $this->serverContainer->getUserManager();
		$this->users = $this->getUsers(); //this need to be execeute first
		$this->shares = $this->getSharesByUsers();
		$this->usersFolders = $this->getUsersFolders();
	}
	
	private function getSharesByUsers(){
		$this->shares = [];
		foreach($this->users as $user){
			foreach([\OCP\Share::SHARE_TYPE_GROUP, \OCP\Share::SHARE_TYPE_USER, \OCP\Share::SHARE_TYPE_LINK, \OCP\Share::SHARE_TYPE_REMOTE] as $shareType) {
				$offset = 0;
				while (true) {
					$sharePage = $this->shareManager->getSharesBy($user->getUID(), $shareType, null, true, 50, $offset);
					if (empty($sharePage)) {
						break;
					}
					$this->shares = array_merge($this->shares, $sharePage);
					$offset += 50;
				}
			}
		}
		
		return $this->shares;
	}
	
	private function getUsers(){
		$users = [];
		$this->serverContainer->getUserManager()->callForAllUsers(function(IUser $user) use (&$users) {
				array_push($users, $user);
		});
		return $users;
	}
	
	private function getUsersFolders(){
		$folders = [];
		foreach ($this->users as $user){
			$folders[$user->getUID()] = $this->serverContainer->getUserFolder($user->getUID());
		}
		return $folders;
	}
	
	private function searchInShares($userId, $file=null){
		$this->shares = [];
		foreach([\OCP\Share::SHARE_TYPE_GROUP, \OCP\Share::SHARE_TYPE_USER, \OCP\Share::SHARE_TYPE_LINK, \OCP\Share::SHARE_TYPE_REMOTE] as $shareType) {
			$offset = 0;
			while (true) {
				$sharePage = $this->shareManager->getSharesBy($userId, $shareType, $file, true, 50, $offset);
				if (empty($sharePage)) {
					break;
				}
				$this->shares = array_merge($this->shares, $sharePage);
				$offset += 50;
			}
		}
		return $this->shares;
	}
	
	private function permissionResolver($number){
		$temp = decbin($number);
		$temp = array_reverse(str_split($temp));
		$decode = ['0' => 'no', '1' => 'yes', null => 'no'];
		return [
				'read' => $decode[$temp[0]],
				'update' => $decode[$temp[1]],
				'create' => $decode[$temp[2]],
				'delete' => $decode[$temp[3]],
				'share' => $decode[$temp[4]]
		];
	}
	
	private function constructReport(){
		$table = [];
		foreach ($this->usersFolders as $userId => $folder){
			foreach($folder->search() as $file){
				
				if($file->isShared()){
					continue;
				}
				
				$shareGroup = [$this->userManager->get($userId)->getDisplayName()];
				$permissions = [$this->permissionResolver($file->getPermissions())];
				
				foreach ($this->searchInShares($userId, $file) as $share){
					$sharemember = $share->getSharedWith();
					if(is_null($sharemember)){
						$sharemember = 'link';
					}
					array_push($shareGroup, $sharemember);
					array_push($permissions, $this->permissionResolver($share->getPermissions()));
				}
				
				array_push($table, ['owner' => $this->userManager->get($userId)->getDisplayName(),
									'name' => $file->getName(),
									'link/path' => $file->getPath(),
									'shareGroup' => $shareGroup,
									'permissions' => $permissions
				]);
			}
		}
		return $table;
	}
	
	
	public function generateReport(){
		return $this->constructReport();
	}
	
}