<?php

require_once("base.class.php");

class Cliente extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "cliente";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "nome" => NULL,
                "end" => NULL,
                "tel" => NULL,
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