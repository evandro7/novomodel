<?php
include_once("config.php");

use controllers\usuarioDAO;
use controllers\alunoDAO;
use modelo\usuarios;
use modelo\aluno;

	$ud = new usuarioDAO();
	$a = new alunoDAO();


	$usuario = new usuarios();
	$usuario->nome = "Zau";
	$usuario->email = "fdggs@ggf.com.br";
	//$ud->salvar($usuario);
	
	$aluno = new aluno();
	$aluno->nome='Ev';
	//$a->salvar($aluno);

	
	
	
	
	
	$class  =  get_class($usuario); 
	$ref = new ReflectionClass( $class ); 
	
	
	$campos[]=$ref->getProperties();
	
	
	echo '<pre>';
	print_r($campos[0]);
	echo '</pre>';
	
	
		foreach ($campos[0] as $k){
			$prop = $ref->getProperty($k->name);
			$prop->setAccessible(true);
			 echo "Campo: " . $prop->getName().' - valor: '.$prop->getValue($usuario) . "<br>";
	
			//echo 'K '.$k->name.'<br>';
			$campos .=$k.', ';
		
		}
	
	$prop = $ref->getProperty("nome");
	$prop->setAccessible(true);
	
//	 echo "Campo: " . $prop->getName().' - valor: '.$prop->getValue($usuario) . "\n";  // OK!
		
	
	
	
	
	//$ud->salvar($usuario);
	//echo $usuario->nome;
	
?>