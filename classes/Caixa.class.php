<?php

require_once("base.class.php");

class Caixa extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "caixa";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "entrada" => NULL,
                "obs" => NULL,
                "dt" => NULL
            );
        } else {
            $this->campos_valores = $campos;
        }
        $this->campo_pk = "id";
    }

}

?>