<?php
	namespace controllers;
	use modelo\aluno;
	use libs\model2;
	
	class alunoDAO extends model2{
		
		function salvar (aluno $u){
			$this->tabela('tb_alunos');
			$this->save($u);
		}
	}
?>