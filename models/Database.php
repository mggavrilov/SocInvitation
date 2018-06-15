<?php 
    class Database {
        private $dbHost = "localhost";
        private $dbName = "socinvitation";
        private $dbUser = "root";
        private $dbPass = "";
        
        protected $conn;
        
        public function __construct() {
            try {
                $this->conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            } catch(PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }
?>