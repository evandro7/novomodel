<?php
	namespace controllers;
	use modelo\usuarios;
	use libs\model2;
	
	class usuarioDAO extends model2{
		
		function salvar (usuarios $u){
			$this->tabela('tb_usuarios');
			$this->save($u);
		}
	}
?>