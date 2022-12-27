<?php 
    class database{
        public $que;
        private $hostname='localhost';
        private $username='root';
        private $password='password';
        private $dbname='ecommerce';
        private $result=array();
        private $mysqli='';
        public $sql;

        public function __construct(){
            $this->mysqli = new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
        }

        public function showCategoryWiseItems(){
            $query = "SELECT cat.Name, COUNT(icr.categoryId) as totalItems 
            FROM ecommerce.category cat
            JOIN Item_category_relations icr ON icr.categoryId = cat.Id
            GROUP BY icr.categoryId ORDER BY totalItems DESC";

            $this->sql = $result = $this->mysqli->query($query);
        }

        public function __destruct(){
            $this->mysqli->close();
        }
    }
?>