<?php
require_once("banco.class.php");
abstract class base extends banco {
	public $tabela = NULL;
	public $campos_valores = array();
	public $campo_pk = NULL;
	public $valor_pk = NULL;
	public $extra_select = NULL;
	
	//ADICIONA CAMPOS NO ARREY
	public function addCampo($campo=NULL,$valor=NULL){
		if($campo!=NULL){
			$this->campos_valores[$campo] = $valor;
		}
	}//addCampo
	
	//DELETA CAMPOS NO ARREY
	public function delCampo($campo=NULL,$valor=NULL){
		if(array_key_exists($campo,$this->campos_valores)){
			unset($this->campos_valores[$campo]);
		}
	}//delCampo
	
	public function setValor($campo=NULL,$valor=NULL){
		if($campo!=NULL && $valor!=NULL){
			$this->campos_valores[$campo] = $valor;
		}
	}//setValor
	
	public function getValor($campo=NULL){
		if($campo!=NULL && array_key_exists($campo,$this->campos_valores)){
			return $this->campos_valores[$campo];
		}else{
			return FALSE;
		}
	}//getValor
}//fim
?>