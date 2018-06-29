<?php
	require_once('Database.php');
	
	class Setting_model extends Database {
		public function saveSetting($data) {
            $stmt = $this->conn->prepare("UPDATE settings SET value = ? WHERE name = ?");
            $stmt->execute(array($data['value'], $data['name']));
        }
		
		public function getSetting($name) {
			$stmt = $this->conn->prepare("SELECT value FROM settings WHERE name = ?");
            $stmt->execute(array($name));
			
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result['value'];
		}
		
		public function getAllSettings() {
			$stmt = $this->conn->prepare("SELECT * FROM settings WHERE readonly = 0");
            $stmt->execute();
			
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
?>