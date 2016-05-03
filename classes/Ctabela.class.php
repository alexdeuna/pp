<?php

require_once("base.class.php");

class Ctabela extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "ctabela";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "id_cli" => NULL,
                "id_mat" => NULL,
                "valor" => NULL,
                "dt" => NULL
            );
        } else {
            $this->campos_valores = $campos;
        }
        $this->campo_pk = "id";
    }

}

?>