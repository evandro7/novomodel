<?php
namespace novomodel;
//include_once 'libs/conexao.php';
//include_once 'libs/conexao.class.php';
use libs\conexao;

abstract class model {
	private $tabela;
	private $ordemPor='';
	private $agruparPor='';
	private $limite='';
	private $onde='';
	private $bd;
	private $RegAf;
	private $campo;
	private $valor;
	private $totalReg;
	private $ultimoid;
	private $referenceM;
	protected $_referenceMap = array();
	protected $_dependentTables = array();
	
	function __construct($tabela=''){
		$conn = conexao::getInstancia();
		$this->bd = $conn->getDB();
		$this->tabela=$tabela;
	}
		
	function ordemPor($ordem=null){
		$this->ordemPor=($ordem!==null)? ' ORDER BY '.$ordem : '';
		return $this;
	}
		
	function agruparPor($grupo=null){
		$this->agruparPor=($grupo!==null)? ' GROUP BY '.$grupo : '';
		return $this;
	}
		
	function limite($limite=null, $inicio=null){
		$this->limite=($limite!==null)? ' LIMIT '.$limite : '';
		$this->limite.=($inicio!==null)? ' OFFSET '.$inicio  : '';
		return $this;
	}
		
	function onde($onde=null){
		$this->onde=($onde!==null)? "WHERE ".$onde : '';
		return $this;
	}
	
	function parcial($campo,$parcial=null){
		$this->onde=($parcial!==null)? 'WHERE '.$campo." LIKE '%".$parcial."%'" : '';
		return $this;
	}
		
	function tabela($tabela){
		$this->tabela=$tabela;
		return $this;
	}
	
	function define($define,$valor){
		is_array($define) ? $this->campo=$define : $this->campo[]=$define;
		is_array($valor) ? $this->valor=$valor : $this->valor[]=$valor;
		return $this;
	}
	
	function selecionar($fields='*'){
		$retorno = array();
		$sql = sprintf('SELECT %s FROM %s %s %s %s %s', $fields, $this->tabela, $this->onde, $this->ordemPor,$this->limite, $this->agruparPor);
		//$query = $this->bd->prepare($sql);
		try {
			//$query->execute();
			$query=$this->bd->query($sql);
			$resultado = $query;
			return $resultado->fetchAll(PDO::FETCH_OBJ); 
		
			if ($query->fetchColumn() == 0){
				throw new Exception("Os campos de senha não coincidem.");
			}
			unset($query,$sql,$retorno);
		}
		catch (Exception $e){
			echo "Não foram encontrados registros em $this->tabela";
		}
	}
			
	function selecionaid($fields='*'){
		$sql = sprintf('SELECT %s FROM %s %s %s %s', $fields, $this->tabela, $this->onde, $this->ordemPor,$this->limite);
		//$query = $this->bd->prepare($sql);
		echo $sql;
		try{
			//$query->execute();
			$query=$this->bd->query($sql);
			$query->setFetchMode(PDO::FETCH_ASSOC);
			return $query->fetchAll();
			
			if ($query->fetchColumn() == 0){
				throw new Exception("Os campos de senha não coincidem.");
			}
			unset($query,$sql);
		}
		catch (Exception $e){
			//echo "Não foram encontrados registros em $this->tabela";
		}
	}
	
	function consulta($sql){
		$this->bd->exec($sql);
	}
	
	function atual(){
		return $this;
	}
	
	function atualizar(){
	
		$upcampo ='';
		foreach($this->campo as $param){
			$upcampo.=$param.' = ?, ';
		}
		$parametros=$this->valor;
		$upcampo=substr($upcampo,0,-2);
		$sqlUp = sprintf('UPDATE %s SET %s %s', $this->tabela, $upcampo, $this->onde);
		try{
			$queryAtual = $this->bd->prepare($sqlUp);
			$queryAtual->execute($parametros);
			$this->RegAf=$queryAtual->rowCount();
			$this->campo=null;
			$this->valor=null;
			
		} catch (PDOException $e) {
				echo htmlentities('Houve algum erro com a atualização: '. $e->getMessage());
				
		}
	}
	
	function totalLinhas(){
		$sqltotal = sprintf('SELECT COUNT(*) FROM %s %s', $this->tabela, $this->onde);
		$query = $this->bd->prepare($sqltotal);
		try{
			$query->execute();
			$resultado = $query;
			return $resultado->fetchColumn();	
		}
		catch (Exception $e){
			echo "Não foram encontrados registros em $this->tabela";
		}
	}
	
	public function save(){
		
		$svcampos='';
		$valores='';
		//$this->setColunas();	
		//print_r($this->campo);
		foreach($this->campo as $param){
			$svcampos.=$param.', ';
			$valores.='?, ';
		}
		
		echo $svcampos.' - '.$valores.'<br>';
	
		$parametros = $this->valor;
		
		$valores=substr($valores,0,-2);
		$svcampos=substr($svcampos,0,-2);
		
		$sqlIns = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->tabela, $svcampos, $valores);
		echo $sqlIns;
		
		try{
			$queryAtual = $this->bd->prepare($sqlIns);
			$queryAtual->execute($parametros);
			$this->RegAf=$queryAtual->rowCount();
			unset($queryAtual,$svcampos,$valores,$sqlIns,$parametros);
		} catch (PDOException $e) {
			echo htmlentities('Houve algum erro com a Inserção: '. $e->getMessage());
		}
	}
	
	function ultimoid($sequencia=''){
		/*$sql = sprintf("select max(id) from %s", $this->tabela);
		$uid = $this->bd->query($sql);
		print_r($uid);*/
		
		return  $this->bd->lastInsertId($sequencia);	
		
	
	}
	
		
	function initrans(){
		$this->bd->beginTransaction();
	}
	function fimtrans(){
		$this->bd->commit();
	}
	function errotrans(){
		$this->bd->rollBack();
	}
	
	function excluir(){
			
			$parametros=$this->valor;
			$sqlDel = sprintf('DELETE FROM %s %s', $this->tabela, $this->onde);
			try{
				$queryDel = $this->bd->prepare($sqlDel);
				$queryDel->execute();
				$this->RegAf=$queryDel->rowCount();
				
			} catch (PDOException $e) {
				echo htmlentities('Houve algum erro com a Exclusão: '. $e->getMessage());
			}
		}
		
	function getTotalReg(){
		return $this->totalReg;
	}
		
	function confirma(){
		if ($this->RegAf != 0){ 
			return true;
		}
	}
	
	
	function contadorOld($page='home'){
		
		if (!isset($_COOKIE[$page])){
			setcookie($page,"$page"."visita",time()+(1*360));
			
			$sqlPesq = "SELECT count(*) from contador WHERE dia = now()";
			$countdia = $this->bd->query($sqlPesq);
			
			
			$sqlCount = "UPDATE contador SET visitas = (visitas+1) where pagina='$page'";
			$count = $this->bd->exec($sqlCount);
		}
			
	}
	
	function contador($page='home'){
		
		if (!isset($_COOKIE[$page])){
			setcookie($page,"$page"."visita",time()+(8*360));
			$sqlCount = "INSERT INTO contador (id, dia, pagina) values (0, now(), '$page')";
			$count = $this->bd->exec($sqlCount);
		}
	}
	
	function paret(){
		print_r($this->referenceMap);
	}
	
	function findParentRow($coluna){
		//print_r($this->_referenceMap);
		//return array($co->$coluna, $this);
		//return $co->$coluna;
		//echo $coluna;
		$referencia=$this->_referenceMap;
		$fields='*';
		$fields2=$referencia['columns'];
		$campobusca = $referencia['columns'];
		
		$sql = sprintf('SELECT %s FROM %s %s %s %s', $fields, $referencia['refTableClass'], 'WHERE '.$referencia['refColumns'].' = '.$campobusca, $this->ordemPor,$this->limite);
		echo $sql;
		$query = $this->bd->prepare($sql);
		try{
			$query->execute();
			$resultado = $query;
			return $resultado->fetchAll(PDO::FETCH_OBJ); 
		
			if ($query->fetchColumn() == 0){
				throw new Exception("Os campos de senha não coincidem.");
			}
			unset($query,$sql,$retorno);
		}
		catch (Exception $e){
			echo "Não foram encontrados registros em $this->tabela";
		}
	}
	
	function __call($metodo, $argumento){
		
	}
	
}

?>