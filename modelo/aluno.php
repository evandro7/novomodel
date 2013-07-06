<?php

namespace modelo;
//use libs\serial;


/** 
  * @Table("tb_usuarios")
 */
class aluno extends usuarios {
	public  $teste;
	//private $id;
	private $nome;
	private $email;
		
	
	
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}
}

?>