<?php
	namespace libs;
	use Exception;
	
	class serial {
		
		function __get( $propriedade ){
			$metodo = "get{$propriedade}";
			if (method_exists( $this, $metodo )) {
				return $this->$metodo();
			}
		}
			
		function __set( $propriedade, $valor ){
			$metodo = "set{$propriedade}";
			
			/*
				try {
				return $this->$metodo($valor); }
				catch(Exception $e){
					echo "Exceзгo pega: ",  $e->getMessage(), "\n";
				}
			
			*/
			if (method_exists( $this, $metodo )) {
				try {
					return $this->$metodo($valor); }
				catch(Exception $e){
					echo "Exceзгo pega: ",  $e->getMessage(), "\n";
				}
			}  else {
				throw new Exception('O mйtodo ' . $propriedade . ' nгo existe!');
			}
		}
	}
?>