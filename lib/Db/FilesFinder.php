<?php
namespace OCA\Report\Db;

use OCP\IDBConnection;

class FilesFinder {

	private $db;

	public function __construct(IDBConnection $db) {
		$this->db = $db;
	}

	public function findAll() {
		$sql = 'SELECT * FROM `*PREFIX*filecache`';
		$stmt = $this->db->prepare($sql);
		$stmt->execute();

		$table = $stmt->fetchAll();

		$stmt->closeCursor();
		return $table;
	}

}