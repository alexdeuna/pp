<?php

require_once("base.class.php");

class Entrada extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "entrada";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "id_mat" => NULL,
                "id_cli" => NULL,
                "valor" => NULL,
                "peso" => NULL,
                "dt" => NULL
            );
        } else {
            $this->campos_valores = $campos;
        }
        $this->campo_pk = "id";
    }

}

?>