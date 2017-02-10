<?php
namespace OCA\Report\Db;

use OCP\IDBConnection;

class FilesFinder {

	private $db;

	public function __construct(IDBConnection $db) {
		$this->db = $db;
	}

	public function findAll() {
		$sql = 'SELECT storage, path, permissions FROM `*PREFIX*filecache` WHERE name<>"" AND path LIKE "files/%" ORDER BY storage';
		//$sql = 'SELECT * FROM `*PREFIX*filecache` WHERE path LIKE "files%"';
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		$table = $stmt->fetchAll();

		$stmt->closeCursor();
		return $table;
	}
	
	public function findAllMounts() {
		$sql = 'SELECT * FROM `*PREFIX*mounts` WHERE mount_point NOT LIKE "/%/%/"';
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		$table = $stmt->fetchAll();
		
		$stmt->closeCursor();
		return $table;
	}
	
	public function findAllUsers() {
		$sql = 'SELECT CASE WHEN displayname IS NOT NULL THEN displayname ELSE uid END AS displayname, uid FROM `*PREFIX*users`';
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		$table = $stmt->fetchAll();
		
		$stmt->closeCursor();
		return $table;
	}

}