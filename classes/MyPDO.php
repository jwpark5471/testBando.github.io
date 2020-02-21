<?php
    namespace classes;
    use \PDO;
    class MyPDO extends PDO{
        private $host = '121.78.147.67';
        private $user = 'mprd';
        private $password = 'mprd1004';
        private $database = '2020misexpo';
        private $aes_key = 'mprdMISE';

        private $options = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => FALSE
        );

        function __construct(){
            parent::__construct("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->user, $this->password , $this->options);
            $this->exec("set names utf8");
        }

        function getAES_KEY(){
            return $this->aes_key;
        }
    }
 ?>
