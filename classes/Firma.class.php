<?php

require_once("base.class.php");

class Firma extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "firma";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "saida" => NULL,
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