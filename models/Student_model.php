<?php
	require_once('Database.php');
	
	class Student_model extends Database {
		public function saveStudent($data) {
            $stmt = $this->conn->prepare("INSERT INTO students (fn, name, presentation_time, post_id) VALUES (?, ?, ?, ?)");
            $stmt->execute(array($data['fn'], $data['name'], $data['presentation_time'], $data['post_id']));
        }
		
		public function updateStudent($data) {
			$stmt = $this->conn->prepare("UPDATE students SET name = ?, presentation_time = ? WHERE fn = ?");
            $stmt->execute(array($data['name'], $data['presentation_time'], $data['fn']));
		}
		
		public function updateStudentPost($data) {
			$stmt = $this->conn->prepare("UPDATE students SET post_id = ? WHERE fn = ?");
            $stmt->execute(array($data['post_id'], $data['fn']));
		}
		
		public function getStudent($fn) {
			$stmt = $this->conn->prepare("SELECT fn, name, presentation_time, post_id FROM students WHERE fn = ?");
            $stmt->execute(array($fn));
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		
		public function getAllStudents() {
			$stmt = $this->conn->prepare("SELECT fn, name, presentation_time FROM students");
            $stmt->execute();
			
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function getStudentRankings() {
			$stmt = $this->conn->prepare("SELECT fn, name, presentation_time, post_id, created_time,
										  message, picture, likes, comments, auto_generated
										  FROM students LEFT JOIN posts ON students.post_id = posts.id");
            $stmt->execute();
			
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function hasStudents() {
			$stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM students");
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
			return ($result['total'] > 0);
		}
		
		public function truncateStudents() {
			$stmt = $this->conn->prepare("TRUNCATE students");
            $stmt->execute();
		}
	}
?>