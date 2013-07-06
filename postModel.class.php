<?php
	include_once("config.php");

	use libs\model;
	//include_once 'model.class.php';
	Class postModel extends model {
		private $campos=array();
		private $bd;
		public $id;
		public $tb_tipo_id;
		public $tb_usuarios_id;
		public $titulo;
		public $dtpost;
		public $conteudo;
		public $post_sup;
		public $slug;
		public $anexo;
	
		

		function __construct(){
			$this->bd = parent::__construct('tb_posts');
			$this->setCampos();
			
		}

		function setColunas(){
			foreach ($this->campos as $camp){
				if ($this->$camp != null){parent::define($camp,$this->$camp);}
			}
		}

		function salvar(){
			$this->setColunas();
			parent::save();
		}

		function editar(){
			$this->setColunas();
			parent::atualizar();
		}

		function setCampos(){

			$this->campos[]='id';
			$this->campos[]='tb_tipo_id';
			$this->campos[]='tb_usuarios_id';
			$this->campos[]='titulo';
			$this->campos[]='dtpost';
			$this->campos[]='conteudo';
			$this->campos[]='post_sup';
			$this->campos[]='slug';
		}
	}
	
	
	
	Class anexopostModel extends model {
		private $campos=array();
		private $bd;
		public $id;
		public $posts_id;
		public $tipo;
		public $anexo;
		
		
		//protected $_referenceMap = array();
		//protected $_dependentTables = array();
		
		protected  $_referenceMap = 
			array(
				'refTableClass' => 'tb_posts',
				'refColumns' => 'id',
				'columns' => 'posts_id',
			);
		
		function __construct(){
			$this->bd = parent::__construct('tb_anexopost');
			$this->setCampos();
			//parent::referenceM = $this->referenceMap;
			
			
		}

		function setColunas(){
			foreach ($this->campos as $camp){
				if ($this->$camp != null){parent::define($camp,$this->$camp);}
			}
		
		}

		public function salvar(){
			$this->setColunas();
			parent::save();
		}
		
		
		
		function editar(){
			$this->setColunas();
			parent::atualizar();
		}

		function setCampos(){
			$this->campos[]='id';
			$this->campos[]='posts_id';
			$this->campos[]='tipo';
			$this->campos[]='anexo';
		}
		function busca($cod){
			return $this->onde($cod)->selecionaid();
		}
		
	}
	
	
	class anexoPostCtrl{
		public $anexo;
		function __construct(){
			
			//parent::referenceM = $this->referenceMap;
			$this->anexo= new anexopostModel();
			
		}
		
		
	}

	
	$post = new postModel();
	$anexo = new anexopostModel();
	
	
	
	//$banco = new model;
	//$anexo->paret();
	$anexos = $anexo->busca("id = 5");
	//print_r($anexos);
	//$re = $anexo->findParentRow('anexos');

	
	//echo '<br /> Anexo do Post: '. $anexos->$key;

	
	
	$post->tb_tipo_id=1;
	$post->tb_usuarios_id='E';
	$post->titulo="vando1";
	$post->post_sup='0';
	//$post->slug='Zau';
	$post->dtpost=date('Y-m-d');
	//$post->dtpost=251111;
	$post->conteudo="Teste de zau";
	
	try{
		$post->initrans();
		$post->salvar();
		$anexo->posts_id =$post->ultimoid();
		
		$anexo->anexo="Novo texto";
		$anexo->tipo="0123456789";
		echo '<pre>';
		//print_r($post);
		echo '</pre>';
		
		$nn = 1/0;
		$anexo->salvar();
		$post->fimtrans();
	} catch (Exception $e){
		$post->errotrans();
		echo "Erro na transação: ".$e;
	
	} 
	//$post->slug=$res->slug($_POST['titulo']);
	

 ?> 