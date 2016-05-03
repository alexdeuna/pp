<?php

require_once("base.class.php");

class Saida extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "saida";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "id_mat" => NULL,
                "td_for" => NULL,
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