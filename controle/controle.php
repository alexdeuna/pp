<?php

//ini_set("display_errors", 0);
header("Content-Type: text/plain; charset=UTF-8", true);
require_once("../classes/Caixa.class.php");
require_once("../classes/Firma.class.php");
require_once("../classes/Cliente.class.php");
require_once("../classes/Entrada.class.php");
require_once("../classes/Material.class.php");
require_once("../classes/Fornecedor.class.php");
require_once("../classes/Saida.class.php");
require_once("../classes/Ctabela.class.php");
require_once("../classes/Ftabela.class.php");
require_once("../classes/Peso.class.php");

extract($_POST);
$hoje = date("Y") . "-" . date("m") . "-" . date("d");


// ENTRADA ##################################################################################################
if ($_POST['acao'] == 'entrada1') {
    echo "<form  class='form-horizontal' name='fnova_tabela' id='entrada_form' action='' method='POST' role='form'>
           <div class='form-group row'>
           <div class='col-xs-3'><blockquote><p>Entrada</p></blockquote></div>
           <div class='col-xs-6'>
                <div class='input-group'><span class='input-group-addon'>Cliente</span>
                            <select type='text' class='form-control input-lg' id='id_cliente'>";
    $cli = new Cliente();
    $cli->extra_select = "order by nome";
    $cli->selecionaTudo($cli);
//    echo "<option value=''>Selecione um Cliente</option>";
    while ($c = $cli->retornaDados()) {
        echo "<option value='$c->id'>$c->nome</option>";
    }
    echo"</select></div></div></div><div class='container_entrada'></div></form>";
} else if ($_POST['acao'] == 'entrada2') {
    $entrada = new Entrada();
    $sql = "select e.id, e.dt, c.nome as cliente, e.peso, m.nome as material from entrada e, cliente c, material m 
                where e.id_cli = c.id 
                and e.id_mat = m.id
                and e.dt > '$hoje'
                order by e.id desc";
    $entrada->selecionaLivre($sql);
    $saida = "<table class='table table-hover table-striped table-bordered' style='margin-top: 10px'><thead>
         <tr><th>Data</th><th>Cliente</th><th>Material</th><th>Peso</th><th></th></tr>
         </thead><tbody>";
    while ($e = $entrada->retornaDados()) {
        $saida .= "<tr><td>$e->dt</td><td>$e->cliente</td><td>$e->material</td><td>$e->peso kg</td><td><div class='text-center'><button type='button' class='btn btn-danger btn-xs excluir_entrada' id='$e->id'>excluir</button></div></td></tr>";
    }
    $saida .= "</tbody></table>";
    if ($_POST['idCliente']) {
        $material = new Material();
        $material->selecionaCampos($material);
        while ($m = $material->retornaDados()) {
            $sql = "select t.valor as valor, t.dt_update as dt from ctabela t, material m, cliente c
                where t.id_cli = " . $_POST['idCliente'] . "
                and c.id =t.id_cli
                and t.id_mat = $m->id
                and m.id = t.id_mat
                and t.dt_update = (select max(dt_update)from ctabela where id_mat = $m->id and id_cli = " . $_POST['idCliente'] . ")";
            $tabela = new Ctabela();
            $tabela->selecionaLivre($sql);
            while ($t = $tabela->retornaDados()) {
                echo "<div class='col-xs-3' style='padding: 0 10px 15px 0'>
                <!--<label>$m->nome</label>-->
                <div class='input-group'>
                   <span class='input-group-addon'>$m->nome</span>
                       <span class='input-group-addon' id='valor$m->nome' valor='$t->valor'>R$ $t->valor</span>
                    <input type='text' class='form-control input-lg maskDinheiro' id='Kg$m->nome' idMaterial='$m->id' placeholder='0.000 kg' maxlength='30'>
                   <!-- <input type='text' class='form-control input-sm' value='R$ $t->valor' disabled> -->
                    </div>
             </div>";
            }
        }
        echo "<div class='col-xs-12 text-center'><button type='button' class='btn btn-default' id='salvar_entrada'>Salvar</button></div>
              <div class='col-xs-12' id='lista'>$saida</div>";
    }
} else if ($_POST['acao'] == 'salvar_entrada') {
    foreach (array_keys($_POST) as $nome) {
        if ($nome == 'idCliente') {
            $idCliente = $_POST[$nome];
        }
    }
    foreach (array_keys($_POST) as $nome) {
        if (($nome != 'acao') && ($nome != 'idCliente')) {

            if ($_POST[$nome][1]) {
                $t = new Entrada();
                $t->setValor('id_cli', $idCliente);
                $t->setValor('id_mat', $_POST[$nome][0]);
                $t->setValor('valor', $_POST[$nome][2]);
                $t->setValor('peso', $_POST[$nome][1]);
                $t->delCampo('dt');
                $t->cadastrar($t);
                $vazio = '1';
            }
        }
    }
    if ($vazio) {
        $entrada = new Entrada();
        $sql = "select e.id, e.dt, c.nome as cliente, e.peso, m.nome as material from entrada e, cliente c, material m 
                where  e.id_cli = c.id 
                and e.id_mat = m.id
                and e.dt in (select max(dt) from entrada)
                order by  m.nome";
        $entrada->selecionaLivre($sql);
        while ($e = $entrada->retornaDados()) {
            echo "<tr style='background-color: #fdd'><td>$e->dt</td><td>$e->cliente</td><td>$e->material</td><td>$e->peso kg</td><td><div class='text-center'><button type='button' class='btn btn-danger btn-xs excluir_entrada' id='$e->id'>excluir</button></div></td></tr>";
        }
    } else {
        echo "Preencha pelo menos um campo!";
    };
} else if ($_POST['acao'] == "excluir_entrada") {
    $entrada = new Entrada();
    $entrada->valor_pk = $_POST['id'];
    $entrada->deletar($entrada);
//    echo $_POST['id'];

// CLIENTE ##################################################################################################
} else if ($_POST['acao'] == "novo_cliente") {
    echo "<form  class='form-horizontal' name='fnova_tabela' id='entrada_form' action='' method='POST' role='form'>
           <div class='form-group row'>
           <blockquote><p>Novo Cliente</p></blockquote> 
           <div class='col-xs-12'>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Nome</span>
                    <input type='text' class='form-control' id='nome' maxlength='100'>
                </div>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Endereço</span>
                    <input type='text' class='form-control' id='end' maxlength='300'>
                </div>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Telefone</span>
                    <input type='tel' class='form-control' id='tel' >
                </div>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Observações</span>
                    <textarea class='form-control'  rows='5' id='obs' maxlength='600' />
                </div>
            </div>
            </div>
            <div class='text-center'><button type='button' class='btn btn-default' id='salvar_cliente'>Salvar</button></div>
            </form>";
} else if ($_POST['acao'] == "salvar_cliente") {
    if ($_POST['nome']) {
        $c = new Cliente();
        $c->setValor('nome', $_POST['nome']);
        $c->setValor('end', $_POST['end']);
        $c->setValor('tel', $_POST['tel']);
        $c->setValor('obs', $_POST['obs']);
        $c->delCampo('dt');
        $c->cadastrar($c);
        echo "Cliente Cadastrado!";
    } else {
        echo "FAVOR INFORMAR NOME DE CLIENTE!!! ";
    }
} else if ($_POST['acao'] == "atualizar_cliente") {
    echo "atualizar_cliente";
} else if ($_POST['acao'] == "salvar_atualizar_cliente") {
    echo "salvar_atualizar_cliente";

// TABELA CLIENTE ##################################################################################################
} else if ($_POST['acao'] == 'nova_tabela_cliente') {
    $material = new Material();
    $material->selecionaCampos($material);
    echo "<form  class='form-horizontal' name='fnova_tabela' action='' method='POST' role='form'><div class='form-group row'><blockquote>
  <p>Nova Tabela Cliente</p></blockquote> <div class='col-xs-12' style='padding: 0 20px 25px 0'>
                <div class='input-group'><span class='input-group-addon'>Cliente</span>
                            <select type='text' class='form-control' id='id_cliente'>";
    $cli = new Cliente();
    $cli->extra_select = "order by nome";
    $cli->selecionaTudo($cli);
    while ($c = $cli->retornaDados()) {
        echo "<option value='$c->id'>$c->nome</option>";
    }
    echo"</select></div></div>";
    while ($m = $material->retornaDados()) {
        echo "<div class='col-xs-3' style='padding: 0 20px 25px 0'>
                <!--<label>$m->nome</label>-->
                <div class='input-group'>
                   <span class='input-group-addon'>$m->nome</span>
                   <span class='input-group-addon valor' id='valor$m->nome'></span>
                   <input type='text' class='form-control maskDinheiro' id='$m->nome' idMaterial='$m->id' placeholder='R$ 0.00' maxlength='30'>
                </div>
             </div>";
    }
    echo "</div><div class='text-center'><button type='button' class='btn btn-default' id='salvar_tabela_cliente'>Salvar</button></div></form>";
} else if ($_POST['acao'] == 'salvar_tabela_cliente') {
    foreach (array_keys($_POST) as $nome) {
        if ($nome == 'idCliente') {
            $idCliente = $_POST[$nome];
        }
    }
    foreach (array_keys($_POST) as $nome) {
        if (($nome != 'acao') && ($nome != 'idCliente')) {
            if ($_POST[$nome][1]) {
                $t = new CTabela();
                $t->setValor('id_cli', $idCliente);
                $t->setValor('id_mat', $_POST[$nome][0]);
                $t->setValor('valor', $_POST[$nome][1]);
                $t->delCampo('dt');
                $t->cadastrar($t);
            }
        }
    }
} else if ($_POST['acao'] == "consulta_tabela_cliente") {
    if ($_POST['idCliente']) {
        $material = new Material();
        $material->selecionaCampos($material);
        while ($m = $material->retornaDados()) {
            $sql = "select t.valor as valor, t.dt_update as dt from ctabela t, material m, cliente c
                where t.id_cli = " . $_POST['idCliente'] . "
                and c.id =t.id_cli
                and t.id_mat = $m->id
                and m.id = t.id_mat
                and t.dt_update = (select max(dt_update)from ctabela where id_mat = $m->id and id_cli = " . $_POST['idCliente'] . ")";
            $tabela = new Ctabela();
            $tabela->selecionaLivre($sql);
            while ($t = $tabela->retornaDados()) {
                echo " <input type = 'hidden' valor = '$t->valor' nome = '$m->nome'>";
            }
        }
    }
// FORNECEDOR ##################################################################################################
} else if ($_POST['acao'] == "novo_fornecedor") {
    echo "<form  class='form-horizontal' name='fnova_tabela' id='entrada_form' action='' method='POST' role='form'>
           <div class='form-group row'>
           <blockquote><p>Novo Fornecedor</p></blockquote> 
           <div class='col-xs-12'>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Nome</span>
                    <input type='text' class='form-control' id='nome' maxlength='100'>
                </div>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Endereço</span>
                    <input type='text' class='form-control' id='end' maxlength='300'>
                </div>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Telefone</span>
                    <input type='tel' class='form-control' id='tel'>
                </div>
                <div class='input-group' style='padding: 25px'>
                <span class='input-group-addon'>Observações</span>
                    <textarea class='form-control'  rows='5' id='obs' maxlength='600' />
                </div>
            </div>
            </div>
            <div class='text-center'><button type='button' class='btn btn-default' id='salvar_fornecedor'>Salvar</button></div>
            </form>";
} else if ($_POST['acao'] == "salvar_fornecedor") {
    if ($_POST['nome']) {
        $c = new Fornecedor();
        $c->setValor('nome', $_POST['nome']);
        $c->setValor('end', $_POST['end']);
        $c->setValor('tel', $_POST['tel']);
        $c->setValor('obs', $_POST['obs']);
        $c->delCampo('dt');
        $c->cadastrar($c);
        echo "Fornecedor Cadastrado!";
    } else {
        echo "FAVOR INFORMAR NOME DO FORNECEDOR!!! ";
    }
} else if ($_POST['acao'] == "atualizar_fornecedor") {
    echo "atualizar_fornecedor";
} else if ($_POST['acao'] == "salvar_atualizar_fornecedor") {
    echo "salvar_atualizar_fornecedor";

// TABELA FORNECEDOR ##################################################################################################
} else if ($_POST['acao'] == 'nova_tabela_fornecedor') {
    $material = new Material();
    $material->selecionaCampos($material);
    echo "<form  class='form-horizontal' name='fnova_tabela' action='' method='POST' role='form'><div class='form-group row'><blockquote>
  <p>Nova Tabela Fornecedor</p></blockquote> <div class='col-xs-12' style='padding: 0 20px 25px 0'>
                <div class='input-group'><span class='input-group-addon'>Fornecedor</span>
                            <select type='text' class='form-control' id='id_fornecedor'>";
    $for = new Fornecedor();
    $for->extra_select = "order by nome";
    $for->selecionaTudo($for);
    while ($f = $for->retornaDados()) {
        echo "<option value='$f->id'>$f->nome</option>";
    }
    echo"</select></div>
             </div>";
    while ($m = $material->retornaDados()) {
        echo "<div class='col-xs-3' style='padding: 0 20px 25px 0'>
                <!--<label>$m->nome</label>-->
                <div class='input-group'>
                   <span class='input-group-addon'>$m->nome</span>                       
                   <span class='input-group-addon valor' id='valor$m->nome'></span>
                    <input type='text' class='form-control maskDinheiro' id='$m->nome' idMaterial='$m->id' placeholder='R$ 0.00' maxlength='30'>
                </div>
             </div>";
    }
    echo "</div><div class='text-center'><button type='button' class='btn btn-default' id='salvar_tabela_fornecedor'>Salvar</button></div></form>";
} else if ($_POST['acao'] == 'salvar_tabela_fornecedor') {
    foreach (array_keys($_POST) as $nome) {
        if ($nome == 'idFornecedor') {
            $idFornecedor = $_POST[$nome];
        }
    }
    foreach (array_keys($_POST) as $nome) {
        if (($nome != 'acao') && ($nome != 'idFornecedor')) {
            if ($_POST[$nome][1]) {
                $t = new Ftabela();
                $t->setValor('id_for', $idFornecedor);
                $t->setValor('id_mat', $_POST[$nome][0]);
                $t->setValor('valor', $_POST[$nome][1]);
                $t->delCampo('dt');
                $t->cadastrar($t);
            }
        }
    }
} else if ($_POST['acao'] == "consulta_tabela_fornecedor") {
    if ($_POST['idFornecedor']) {
        $material = new Material();
        $material->selecionaCampos($material);
        while ($m = $material->retornaDados()) {
            $sql = "select t.valor as valor, t.dt_update as dt from ftabela t, material m, cliente c
                where t.id_for = " . $_POST['idFornecedor'] . "
                and c.id =t.id_for
                and t.id_mat = $m->id
                and m.id = t.id_mat
                and t.dt_update = (select max(dt_update)from ftabela where id_mat = $m->id and id_for = " . $_POST['idFornecedor'] . ")";
            $tabela = new Ftabela();
            $tabela->selecionaLivre($sql);
            while ($t = $tabela->retornaDados()) {
                echo " <input type = 'hidden' valor = '$t->valor' nome = '$m->nome'>";
            }
        }
    }
// CAIXA ##################################################################################################
} else if ($_POST['acao'] == "caixa") {
    $cx = new Caixa();
    $cx->extra_select = "where dt > CURDATE() ORDER BY dt DESC";
    $cx->selecionaCampos($cx);
    echo "<form  class='form-horizontal' name='fnova_tabela' action='' method='POST' role='form'>
            <div class='form-group row'>
             <div class='col-xs-3'><blockquote><p>Caixa</p></blockquote></div>
           <div class='col-xs-6'>
              <div class='input-group'>
                    <span class='input-group-addon'>Valor</span>
                    <input type='text' class='form-control input-lg maskDinheiro' id='entrada_caixa' placeholder='R$ 0.00' maxlength='30' />
                    <span class='input-group-addon'>Obs</span>
                    <input type='text' class='form-control input-lg' id='obs_caixa' placeholder='Descrição' maxlength='300' />
                    <span class='input-group-btn'>
                        <button type='button' class='btn btn-default input-lg' id='entrada_caixa_salvar'>Salvar</button>
                    </span>
                </div>
            </div>
            </div>
        </form>
        <table class='table table-hover table-bordered'>
                 <thead><tr class='success'><th>Data</th><th>Valor</th><th>Descrição</th><th></th></tr></thead><tbody>";
    while ($c = $cx->retornaDados()) {
        echo "<tr id='entrada$c->id'><td>$c->dt</td><td>R$ $c->entrada</td><td>$c->obs</td><td><div class='text-center'><button type='button' class='btn btn-danger btn-xs excluir_caixa' idEntrada = $c->id>excluir</button></div></td></tr>";
    }
    echo "</tbody></table>";
} else if ($_POST['acao'] == "entrada_caixa") {
    if ($_POST['entrada']) {
        $entrada = new Caixa();
        $entrada->addCampo('entrada', $_POST['entrada']);
        $entrada->addCampo('obs', $_POST['obs']);
        $entrada->delCampo('dt');
        $entrada->cadastrar($entrada);

        $cx = new Caixa();
        $cx->extra_select = "where dt = (select max(dt) from caixa)";
        $cx->selecionaCampos($cx);

        while ($c = $cx->retornaDados()) {
            echo "<tr id='entrada$c->id' class='warning'><td>$c->dt</td><td>R$ $c->entrada</td><td>$c->obs</td><td><div class='text-center'><button type='button' class='btn btn-danger btn-xs excluir_entrada' idEntrada = $c->id>excluir</button></div></td></tr>";
        }
    }
} else if ($_POST['acao'] == "excluir_entrada_caixa") {
    $entrada = new Caixa();
    $entrada->valor_pk = $_POST['id'];
    $entrada->deletar($entrada);

// FIRMA ##################################################################################################
} else if ($_POST['acao'] == "firma") {
    $fi = new Firma();
    $fi->extra_select = "where dt > CURDATE() ORDER BY dt DESC";
    $fi->selecionaCampos($fi);
    echo "<form  class='form-horizontal' name='fnova_tabela' action='' method='POST' role='form'>
            <div class='form-group row'>
             <div class='col-xs-3'><blockquote><p>Firma</p></blockquote></div>
           <div class='col-xs-6'>
              <div class='input-group'>
                    <span class='input-group-addon'>Valor</span>
                    <input type='text' class='form-control input-lg maskDinheiro' id='saida_firma' placeholder='R$ 0.00' maxlength='30' />
                    <span class='input-group-addon'>Obs</span>
                    <input type='text' class='form-control input-lg' id='obs_firma' placeholder='Descrição' maxlength='300' />
                    <span class='input-group-btn'>
                        <button type='button' class='btn btn-default input-lg' id='saida_firma_salvar'>Salvar</button>
                    </span>
                </div>
            </div>
            </div>
        </form>
        <table class='table table-hover table-bordered'>
                 <thead><tr class='danger'><th>Data</th><th>Valor</th><th>Descrição</th><th></th></tr></thead><tbody>";
    while ($f = $fi->retornaDados()) {
        echo "<tr id='saida$f->id'><td>$f->dt</td><td>R$ $f->saida</td><td>$f->obs</td><td><div class='text-center'><button type='button' class='btn btn-danger btn-xs excluir_firma' idSaida = $f->id>excluir</button></div></td></tr>";
    }
    echo "</tbody></table>";
} else if ($_POST['acao'] == "saida_firma") {
    if ($_POST['saida']) {
        $saida = new Firma();
        $saida->addCampo('saida', $_POST['saida']);
        $saida->addCampo('obs', $_POST['obs']);
        $saida->delCampo('dt');
        $saida->cadastrar($saida);

        $fi = new Firma();
        $fi->extra_select = "where dt = (select max(dt) from firma)";
        $fi->selecionaCampos($fi);

        while ($f = $fi->retornaDados()) {
            echo "<tr id='saida$f->id' class='warning'><td>$f->dt</td><td>R$ $f->saida</td><td>$f->obs</td><td><div class='text-center'><button type='button' class='btn btn-danger btn-xs excluir_firma' idSaida = $f->id>excluir</button></div></td></tr>";
        }
    }
} else if ($_POST['acao'] == "excluir_saida_firma") {
    $entrada = new Firma();
    $entrada->valor_pk = $_POST['id'];
    $entrada->deletar($entrada);
}

// PESO FINAL DO DIA ##################################################################################################
if ($_POST['acao'] == 'peso_final') {
    echo "<form  class='form-horizontal' name='fnova_tabela' id='entrada_form' action='' method='POST' role='form'>
           <div class='form-group row'>
           <blockquote><p>Pesagem Final do Dia</p></blockquote> 
           <div class='col-xs-12'>
                <div class='input-group'>";
    $material = new Material();
    $sql = "select m.id, m.nome as nome, p.peso as peso, p.id as id_peso from peso p, material m where m.id = p.id_mat and DATE_FORMAT(p.dt, '%Y-%m-%d') = '$hoje'
            union
          select id, nome, null, null from material where id not in(select id_mat from peso where DATE_FORMAT(dt, '%Y-%m-%d') = '$hoje')
            order by id";
    $material->selecionaLivre($sql);
    while ($m = $material->retornaDados()) {
        echo "<div class='col-xs-3' style='padding: 0 20px 25px 0'>
                <div class='input-group '>
                   <span class='input-group-addon'>$m->nome</span>";
        if ($m->peso) {
            echo"<input type='text' class='form-control input-lg maskDinheiro' id='$m->nome' idMaterial='$m->id' placeholder='0.000 kg' maxlength='30' value='$m->peso' disabled='disabled'>
                 <span class='input-group-addon'><button type='button' class='close excluir_peso' id=$m->id_peso>&times;</button></span></div></div>";
        } else {
            echo"<input type='text' class='form-control input-lg maskDinheiro' id='$m->nome' idMaterial='$m->id' placeholder='0.000 kg' maxlength='30'></div></div>";
        }
    }
    echo "</div></form>";
} else if ($_POST['acao'] == 'salvar_peso') {
    $p = new Peso();
    $p->setValor('id_mat', $_POST["id"]);
    $p->setValor('peso', $_POST["valor"]);
    $p->setValor('status', '0');
    $p->delCampo('dt');
    $p->cadastrar($p);

    $idPeso = new Peso();
    $sql = "select max(id) as id from Peso";
    $idPeso->selecionaLivre($sql);
    while ($id = $idPeso->retornaDados()) {
        echo $id->id;
    }
} else if ($_POST['acao'] == 'excluir_peso') {
    $p = new Peso();
    $p->valor_pk = $_POST['id'];
    $p->deletar($p);
} else if ($_POST['acao'] == 'salvar_peso_lista') {
    $peso = new Peso();
    $sql = "select p.id, p.peso as peso, m.nome as material from material m, peso p where DATE_FORMAT(p.dt, '%Y-%m-%d') = '$hoje' and m.id = p.id_mat";
    $peso->selecionaLivre($sql);
    echo "<table class='table table-hover table-striped table-bordered'><thead>
         <tr><th>Material</th><th>Peso KG</th><th>&nbsp;</th></tr>
         </thead><tbody>";
    while ($p = $peso->retornaDados()) {
        echo "<tr><td>$p->material</td><td>$p->peso</td><td>excluir</td></tr>";
    }
    echo "</tbody></table>";
}    