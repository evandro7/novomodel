<?php
//include_once 'conexao.class.php';
include_once 'conexao.class.php';
class DAO {
	private $tabela;
	private $ordemPor='';
	private $agruparPor='';
	private $limite='';
	private $onde='';
	private $bd;
	private $RegAf;
	private $nomes;
	private $valor;
	private $totalReg;
	private $ultimoid;
	
	function __construct($tabela){
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
		is_array($define) ? $this->nomes=$define : $this->nomes[]=$define;
		is_array($valor) ? $this->valor=$valor : $this->valor[]=$valor;
		return $this;
	}
	
	function selecionar($fields='*'){
		$retorno = array();
		$sql = sprintf('SELECT %s FROM %s %s %s %s %s', $fields, $this->tabela, $this->onde, $this->ordemPor,$this->limite, $this->agruparPor);
		$query = $this->bd->prepare($sql);
		try{
			$query->execute();
			$resultado = $query;
			//$this->totalReg = $query->fetchColumn();	
			return $resultado->fetchAll(PDO::FETCH_OBJ); 
		
			if ($query->fetchColumn() == 0){
				throw new Exception("Os campos de senha nуo coincidem.");
			}
		}
		catch (Exception $e){
			//echo "Nуo foram encontrados registros em $this->tabela";
		}
	}
		
		
	function selecionaid($fields='*'){
		$retorno = array();
		$sql = sprintf('SELECT %s FROM %s %s %s %s', $fields, $this->tabela, $this->onde, $this->ordemPor,$this->limite);
		$query = $this->bd->prepare($sql);
		//echo $sql;
		try{
			$query->execute();
			$resultado = $query;
			return $resultado->fetch(PDO::FETCH_OBJ); 
			if ($query->fetchColumn() == 0){
				throw new Exception("Os campos de senha nуo coincidem.");
			}
		}
		catch (Exception $e){
			//echo "Nуo foram encontrados registros em $this->tabela";
		}
	}
	
	function consulta($sql){
		$this->bd->exec($sql);
	}
	
	function atualizar(){
	
		$defin='';
		foreach($this->nomes as $param){
			$defin.=$param.' = ?, ';
		}
		$parametros=$this->valor;
		$defini=substr($defin,0,-2);
		$sqlUp = sprintf('UPDATE %s SET %s %s', $this->tabela, $defini, $this->onde);
		try{
			$queryAtual = $this->bd->prepare($sqlUp);
			$queryAtual->execute($parametros);
			$this->RegAf=$queryAtual->rowCount();
			$this->nomes=null;
			$this->valor=null;
			$bd=null;
		} catch (PDOException $e) {
				echo htmlentities('Houve algum erro com a atualizaчуo: '. $e->getMessage());
				
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
			echo "Nуo foram encontrados registros em $this->tabela";
		}
	}
	
	function save(){
		
		$campos='';
		$valores='';
		//$this->setColunas();	
		foreach($this->nomes as $param){
			$campos.=$param.', ';
			$valores.='?, ';
			}
	
		$parametros=$this->valor;
		
		$valores=substr($valores,0,-2);
		$campos=substr($campos,0,-2);
		
		$sqlIns = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->tabela, $campos, $valores);
		//echo $sqlIns;
		try{
			$queryAtual = $this->bd->prepare($sqlIns);
			$queryAtual->execute($parametros);
			
			$this->RegAf=$queryAtual->rowCount();
			$this->ultimoid = lastInsertId();
			
			
		} catch (PDOException $e) {
			echo htmlentities('Houve algum erro com a Inserчуo: '. $e->getMessage());
		}
		
	}
	
	function getUltimoid(){
		/*$sql = sprintf("select max(id) from %s", $this->tabela);
		$uid = $this->bd->query($sql);
		print_r($uid);*/
		return 	$this->ultimoid;	
	
	}
	
	function excluir(){
			
			$parametros=$this->valor;
			$sqlDel = sprintf('DELETE FROM %s %s', $this->tabela, $this->onde);
			try{
				$queryDel = $this->bd->prepare($sqlDel);
				$queryDel->execute();
				$this->RegAf=$queryDel->rowCount();
				
			} catch (PDOException $e) {
				echo htmlentities('Houve algum erro com a Exclusуo: '. $e->getMessage());
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
	
}

?>