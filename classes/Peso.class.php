<?php

require_once("base.class.php");

class Peso extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "peso";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "id_mat" => NULL,
                "peso" => NULL,
                "status" => NULL,
                "dt" => NULL
            );
        } else {
            $this->campos_valores = $campos;
        }
        $this->campo_pk = "id";
    }

}

?>