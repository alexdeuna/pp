<?php

//ini_set("display_errors", 0);
header("Content-Type: text/plain; charset=UTF-8", true);
require_once("../classes/Caixa.class.php");
require_once("../classes/Cliente.class.php");
require_once("../classes/Entrada.class.php");
require_once("../classes/Firma.class.php");
require_once("../classes/Material.class.php");
require_once("../classes/Fornecedor.class.php");
require_once("../classes/Saida.class.php");
require_once("../classes/Ctabela.class.php");
require_once("../classes/Ftabela.class.php");

extract($_POST);
$saida = '';
// POR DATA ##################################################################################################
if ($_POST['acao'] == 'btn_por_entrada') {
    $dt = new Entrada();
    $sql = "select e.dt, e.id, e.id_cli, e.id_mat, c.nome as cliente, m.nome as material, e.peso, e.valor, ROUND(e.peso * e.valor,2) as total
          from entrada e, cliente c, material m 
          where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
          and e.id_cli = c.id 
          and e.id_mat = m.id
          order by  e.dt desc ";
    $dt->selecionaLivre($sql);
    $saida = "<table class='table table-hover table-striped table-bordered '><thead>
          <tr><th colspan=6 style='text-align: center' class='titulo_relatorio'>Relatório - Entrada</th></tr><tr>
          <th>Data</th><th>Cliente</th><th>Material</th><th>Peso KG</th><th>Valor R$</th><th>Total R$</th></tr>
          </thead><tbody>";
    while ($d = $dt->retornaDados()) {
        $saida .= "<tr><td>$d->dt</td><td>$d->cliente</td><td>$d->material</td><td>$d->peso</td><td>$d->valor</td><td>$d->total</td></tr>";
    }
    $saida .= "</tbody></table>";
} else if ($_POST['acao'] == 'btn_por_cliente') {
    $dt = new Entrada();
    $sql = "select DISTINCT(e.dt) as dt, c.nome as cliente
            from entrada e, cliente c, material m 
            where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
            and e.id_cli = c.id 
            and e.id_mat = m.id
            order by c.nome";
    $dt->selecionaLivre($sql);
    $saida = "<table class='table table-hover table-bordered'><thead>
          <tr><th style='text-align: center' class='titulo_relatorio'>Relatório - Cliente</th></tr>";
    while ($d = $dt->retornaDados()) {
        $saida .= "</thead><tbody><tr><td>
          <table class='table table-hover table-striped table-bordered'><thead><tr>
          <th colspan='2' class='warning'>$d->cliente<th colspan='2' class='warning text-right'>$d->dt</th></tr>
          <tr><th>Material</th><th>Peso KG</th><th>Valor R$</th><th>Total R$</th></tr>
          </thead><tbody>";
        $cli = new Entrada();
        $sql2 = "select e.dt, c.nome as cliente, m.nome as material, e.peso, e.valor, ROUND(e.peso * e.valor,2) as total
                from entrada e, cliente c, material m 
                where e.dt = '$d->dt'
                and e.id_cli = c.id 
                and e.id_mat = m.id
                order by m.nome";
        $cli->selecionaLivre($sql2);
        while ($c = $cli->retornaDados()) {
            $saida .= "<tr><td>$c->material</td><td>$c->peso</td><td>$c->valor</td><td>$c->total</td></tr>";
        }
        $saida .= "</tbody></table></td></tr>";
    }
    $saida .= "</tbody></table>";
} else if ($_POST['acao'] == 'btn_por_material') {
    $dt = new Entrada();
    $sql = "select m.nome as material, sum(e.peso) as peso, ROUND(avg(e.valor),2) as valor
            from entrada e, material m 
            where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
            and e.id_mat = m.id
            group by m.nome
            order by  m.nome";
    $dt->selecionaLivre($sql);
    $saida = "<table class='table table-hover table-striped table-bordered'><thead>
         <tr><th colspan=3 style='text-align: center' class='titulo_relatorio'>Relatório - Soma do material comprado</th></tr>
         <tr><th colspan=3>" . $_POST['data'] . "</tr>
         <tr><th>Material</th><th>Peso Total KG</th><th>Média Valor R$</th></tr>
         </thead><tbody>";
    while ($d = $dt->retornaDados()) {
        $saida .= "<tr><td>$d->material</td><td>$d->peso</td><td>$d->valor</td></tr>";
    }
    $saida .= "</tbody></table>";
} else if ($_POST['acao'] == 'btn_por_peso_final') {
    $dt = new Entrada();
    $sql = "select m.nome as material, p.peso
            from peso p, material m 
            where DATE_FORMAT(p.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
            and p.id_mat = m.id
            group by m.nome
            order by  m.nome";
    $dt->selecionaLivre($sql);
    $saida = "<table class='table table-hover table-striped table-bordered'><thead>
         <tr><th colspan=2 style='text-align: center' class='titulo_relatorio'>Relatório - Total de material pesado no fim do dia</th></tr>
         <tr><th colspan=2>" . $_POST['data'] . "</tr>
         <tr><th>Material</th><th>Peso Total KG</th></tr>
         </thead><tbody>";
    while ($d = $dt->retornaDados()) {
        $saida .= "<tr><td>$d->material</td><td>$d->peso</td></tr>";
    }
    $saida .= "</tbody></table>";
} else if ($_POST['acao'] == 'btn_por_mat_peso') {
    $dt = new Entrada();
    $sql = "select m.nome as material, sum(e.peso) as peso, ROUND(avg(e.valor),2) as valor, p.peso as peso_final
            from entrada e, material m, peso p
            where DATE_FORMAT(p.dt, '%Y-%m-%d') = '" . $_POST['data'] . "' 
            and e.id_mat = m.id
            and p.id_mat = e.id_mat
            group by m.nome
            order by  m.nome";
    $dt->selecionaLivre($sql);
    $saida = "<table class='table table-hover table-striped table-bordered'><thead>
         <tr><th colspan=4 style='text-align: center' class='titulo_relatorio'>Relatório - Comparação entre o peso comprado e o peso do fim do dia</th></tr>
         <tr><th colspan=4>" . $_POST['data'] . "</tr>
         <tr><th>Material</th><th>Peso Total KG</th><th>Média Valor R$</th><th>Peso Final KG</th></tr>
         </thead><tbody>";
    while ($d = $dt->retornaDados()) {
        $saida .= "<tr><td>$d->material</td><td>$d->peso</td><td>$d->valor</td><td>$d->peso_final</td></tr>";
    }
    $saida .= "</tbody></table>";
} else if ($_POST['acao'] == 'btn_por_resumo') {

    $saida = "<div class='row'>
                <div class='col-xs-12 danger'>
                    <table class='table table-bordered'>
                        <thead><tr><th style='text-align: center' class='titulo_relatorio'>Relatório - Resumo do Dia " . $_POST['data'] . "</th></tr></thead><tbody></tbody>
                    </table>
                </div>
             </div>
             <div class='row'>";

// CAIXA ###################################################################################################

    $caixa = new Caixa();
    $caixa->extra_select = "where DATE_FORMAT(dt, '%Y-%m-%d') = '" . $_POST['data'] . "'";
    $caixa->selecionaCampos($caixa);
    $saida .= "<div class='col-xs-3'><table class='table table-hover table-striped table-bordered'><thead>
                  <tr><th colspan='3' style='text-align: center'>Caixa</th></tr>
                  <tr><th>Hora</th><th>Valor</th><th>Descrição</th></tr>
               </thead><tbody>";
    while ($c = $caixa->retornaDados()) {
        $saida .="<tr><td>" . substr($c->dt, 11, 5) . "</td><td>$c->entrada</td><td>$c->obs</td></tr>";
    }

    $saida .= "</tbody></table></div>";

// FIRMA ###################################################################################################

    $firma = new Firma();
    $firma->extra_select = "where DATE_FORMAT(dt, '%Y-%m-%d') = '" . $_POST['data'] . "'";
    $firma->selecionaCampos($firma);
    $saida .= "<div class='col-xs-3'>
                    <table class='table table-hover table-striped table-bordered'><thead>
                    <tr><th colspan='3' style='text-align: center'>Firma</th></tr>
                     <tr><th>Hora</th><th>Valor</th><th>Descrição</th></tr>
                </thead><tbody>";
    while ($f = $firma->retornaDados()) {
        $saida .="<tr><td>" . substr($f->dt, 11, 5) . "</td><td>$f->saida</td><td>$f->obs</td></tr>";
    }

    $saida .= "</tbody></table></div>";

// SOBRA ###################################################################################################
    $firma = new Firma();
    $sql = "select sum(entrada) as valor from caixa where DATE_FORMAT(dt, '%Y-%m-%d') = '" . $_POST['data'] . "'
            union 
            select sum(saida) as valor from firma where DATE_FORMAT(dt, '%Y-%m-%d') = '" . $_POST['data'] . "'
            union
            select 
                (select sum(entrada) from caixa where DATE_FORMAT(dt, '%Y-%m-%d') = '" . $_POST['data'] . "') 
                - 
                (select sum(saida) from firma where DATE_FORMAT(dt, '%Y-%m-%d') = '" . $_POST['data'] . "') as valor";
    $firma->selecionaLivre($sql);
    $saida .= "<div class='col-xs-3'>
                    <table class='table table-hover table-striped table-bordered'><thead>
                    <tr><th colspan='3' style='text-align: center'>Sobra</th></tr>
                    <tr><th>Entrada</th><th>Saída</th><th>z</th></tr>
                    </thead><tbody><tr>";
    while ($f = $firma->retornaDados()) {
        $saida .="<td>$f->valor</td>";
    }
    $saida .= "</tr></tbody></table></div>";

// QTS CLIENTE ###################################################################################################

    $qts = new Entrada();
    $sql = "select c.nome as nome, count(c.nome) as qts
                from entrada e, cliente c
                where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
                and e.id_cli = c.id
                group by c.nome
                order by  c.nome";
    $qts->selecionaLivre($sql);
    $saida .= "<div class='col-xs-3'>
                <table class='table table-hover table-striped table-bordered'><thead>
                <tr><th colspan='2' style='text-align: center'>Qts material p/ cliente</th></tr>
                <tr><th>Cliente</th><th>Qts Material</th></tr>
                </thead><tbody>";

    while ($q = $qts->retornaDados()) {
        $saida .= "<tr><td>$q->nome</td><td>$q->qts</td></tr>";
    }
    $saida .= "</tbody></table></div>";


    $saida.="</div>";
// QTS CLIENTE E MATERIAL ###################################################################################################

    $dt1 = new Entrada();
    $sql = "select DISTINCT(e.dt) as dt, c.nome as cliente
            from entrada e, cliente c, material m 
            where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
            and e.id_cli = c.id 
            and e.id_mat = m.id
            order by e.dt";
    $dt1->selecionaLivre($sql);
    $saida .= "<div class='row'><div class='col-xs-6'><table class='table table-hover table-striped table-bordered'><thead><tr>
          <th>Hora</th><th>Cliente</th><th>Material KG</th></tr>
          </thead><tbody>";

    while ($d = $dt1->retornaDados()) {
        $saida .= "<tr><td>" . substr($d->dt, 11, 5) . "</td><td>$d->cliente</td><td>";
        $cli = new Entrada();
        $sql2 = "select e.dt, c.nome as cliente, m.nome as material, e.peso, e.valor, ROUND(e.peso * e.valor,2) as total
                from entrada e, cliente c, material m 
                where e.dt = '$d->dt'
                and e.id_cli = c.id 
                and e.id_mat = m.id
                order by m.nome";
        $cli->selecionaLivre($sql2);
        while ($c = $cli->retornaDados()) {
            $saida .= "$c->material:$c->peso ";
        }
    }
    $saida .= "</td></tr></tbody></table></div>";


// QTS MATERIAL ###################################################################################################

    $dt = new Entrada();
    $sql = "select m.nome as material, sum(e.peso) as peso, ROUND(avg(e.valor),2) as valor
            from entrada e, material m 
            where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
            and e.id_mat = m.id
            group by m.nome
            order by  m.nome";
    $dt->selecionaLivre($sql);
    $saida .= "<div class='col-xs-3'><table class='table table-hover table-striped table-bordered'><thead>
        <tr><th colspan='3' style='text-align: center'>Qts material</th></tr>
         <tr><th>Material</th><th>Total KG</th><th>Média R$</th></tr>
         </thead><tbody>";
    while ($d = $dt->retornaDados()) {
        $saida .= "<tr><td>$d->material</td><td>$d->peso</td><td>$d->valor</td></tr>";
    }
    $saida .= "</tbody></table></DIV>";

// QTS PESO FINAL ###################################################################################################

    $dt = new Entrada();
    $sql = "select m.nome as material, p.peso
            from peso p, material m 
            where DATE_FORMAT(p.dt, '%Y-%m-%d') = '" . $_POST['data'] . "'  
            and p.id_mat = m.id
            group by m.nome
            order by  m.nome";
    $dt->selecionaLivre($sql);
    $saida .= "<div class='col-xs-3'><table class='table table-hover table-striped table-bordered'><thead>
         <tr><th colspan=2 style='text-align: center'>Peso fim do dia</tr>
         <tr><th>Material</th><th>Total KG</th></tr>
         </thead><tbody>";
    while ($d = $dt->retornaDados()) {
        $saida .= "<tr><td>$d->material</td><td>$d->peso</td></tr>";
    }
    $saida .= "</tbody></table></DIV>";

    $saida .= "</div>"; //FECHA A DIV ROW
    $saida .= "<img src='grafico.php?data=" . $_POST['data'] . "&acao=G1' / >";
} else if ($_POST['acao'] == 'outros') {
    
}
session_start();
$_SESSION["relatorio"] = $saida;
echo $saida;

