<?php
	require_once('Database.php');
	
	class Student_model extends Database {
		public function saveStudent($data) {
            $stmt = $this->conn->prepare("INSERT INTO students (fn, name, presentation_time, post_id) VALUES (?, ?, ?, ?)");
            $stmt->execute(array($data['fn'], $data['name'], $data['presentation_time'], $data['post_id']));
        }
		
		public function updateStudent($data) {
			$stmt = $this->conn->prepare("UPDATE students SET name = ?, presentation_time = ?, post_id = ? WHERE fn = ?");
            $stmt->execute(array($data['name'], $data['presentation_time'], $data['post_id'], $data['fn']));
		}
		
		public function updateStudentPost($data) {
			$stmt = $this->conn->prepare("UPDATE students SET post_id = ? WHERE fn = ?");
            $stmt->execute(array($data['post_id'], $data['fn']));
		}
		
		public function getStudent($fn) {
			$stmt = $this->conn->prepare("SELECT fn, name, presentation_time, post_id FROM electives WHERE fn = ?");
            $stmt->execute(array($fn));
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
		}
	}
?>