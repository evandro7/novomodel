<?php
namespace libs;
use PDO;

class conexao {
	// Produчуo	
 	private $host = 'localhost';
	private $db = 'portal';
	private $user = 'root';
	private $pass = '';
	
	
	//publicaчуo
	/*
	private $host = 'mysql12.uni5.net';
	private $db = 'transparenciac';
	private $user = 'transparenciac';
	private $pass = 'smtp00pmvc';*/
	
	
	private $bd;
	
	private static $_instancia = null;
	
	
	private function __construct(){
		$dsn = 'mysql:host='.$this->host.';dbname='.$this->db;
	
		try {
			$this->bd = new PDO($dsn,$this->user,$this->pass);	
			$this->bd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			//echo 'Conexao criada';
		} catch (PDOException $e) {
			echo htmlentities('Houve algum erro com a conexуo com o banco de dados: '. $e->getMessage());
		}
	}
	
	function getDB(){
		return $this->bd;
		}
		
		
	public static function getInstancia(){
		if(!isset(self::$_instancia)){
			self::$_instancia = new self();
		}
		return self::$_instancia;
	}
 
	
	public function __clone(){
		trigger_error('Clone nao permitido.', E_USER_ERROR);
	}
	
	
	
	function initrans1(){
		$this->bd->beginTransaction();
	}
	
	function fimtrans1(){
		$this->bd->commit();
	}
	
	function errotrans1(){
		$this->bd->rollBack();
	}
	
}

?>