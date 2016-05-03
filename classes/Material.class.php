<?php

require_once("base.class.php");

class Material extends base {

    public function __construct($campos = array()) {
        parent::__construct();
        $this->tabela = "material";
        if (sizeof($campos) <= 0) {
            $this->campos_valores = array(
                "nome" => NULL,
                "tipo" => NULL
            );
        } else {
            $this->campos_valores = $campos;
        }
        $this->campo_pk = "id";
    }

}

?>