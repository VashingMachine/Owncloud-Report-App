<?php
namespace OCA\Report\Service;

use OCP\Files\Folder;
use OCP\IUser;
use OCP\IServerContainer;
use OCP\Share\IManager;
use OCP\IURLGenerator;
use OC\Files\Node\File;
use OC\Files\Node\Node;


class ReportService{
	private $serverContainer;
	private $users;
	private $usersFolders;
	private $userManager;
	private $groupManager;
	private $shareManager;
	private $urlGenerator;
	private $shares;
	private $groups;
	
	public function __construct(IServerContainer $serverContainer, IManager $shareManager, IURLGenerator $urlGenerator){
		$this->serverContainer = $serverContainer;
		$this->shareManager = $shareManager;
		$this->userManager = $this->serverContainer->getUserManager();
		$this->groupManager = $this->serverContainer->getGroupManager();
		$this->urlGenerator = $urlGenerator;
		
		$this->users = $this->getUsers(); //this need to be execeute first
		$this->groups = $this->getUsersByGroups();
		$this->shares = $this->getSharesByUsers();
		$this->usersFolders = $this->getUsersFolders();
	}
	
	public function generateReport(){
		return $this->constructReport();
	}
	
	public function getUsersByGroups(){
		$table = [];
		foreach ($this->groupManager->search() as $group){
			$table[$group->getGID()] = [];
			foreach ($group->getUsers() as $user){
				array_push($table[$group->getGID()], $user->getDisplayName());
			}
		}
		return $table;
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
	
	private function searchInShares($userId, Node $file=null){
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
		$decode = ['0' => 'nie', '1' => 'tak', null => 'nie'];
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
				
				$path = $folder->getRelativePath($file->getPath());
				
				$info = pathinfo($path);
				$link = $this->urlGenerator->linkToRoute(
						'files.view.index',
						[
								'dir' => $info['dirname'],
								'scrollto' => $info['basename'],
						]
						);
				
				$path_and_link = ['path' => $file->getPath(),
								  'link' => $link];
				
				$publicLink = null;
				$shareGroup = [$this->userManager->get($userId)->getDisplayName()];
				$permissions = [$this->permissionResolver($file->getPermissions())];
				
				foreach ($this->searchInShares($userId, $file) as $share){
					$sharemember = $share->getSharedWith();
					if(is_null($sharemember)){
						$sharemember = 'link';
						$publicLink = $this->urlGenerator->getAbsoluteURL('/index.php/s/') . $share->getToken();
					}
					array_push($shareGroup, $sharemember);
					array_push($permissions, $this->permissionResolver($share->getPermissions()));
				}
				
				array_push($table, ['owner' => $this->userManager->get($userId)->getDisplayName(),
									'name' => $file->getName(),
									'link/path' => $path_and_link,
									'shareGroup' => $shareGroup,
									'permissions' => $permissions,
									'publicLink' => $publicLink
				]);
			}
		}
		return $table;
	}
	
	
	
	
}