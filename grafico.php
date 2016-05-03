<?php
require_once("./classes/Caixa.class.php");
require_once("./classes/Cliente.class.php");
require_once("./classes/Entrada.class.php");
require_once("./classes/Firma.class.php");
require_once("./classes/Material.class.php");
require_once("./classes/Fornecedor.class.php");
require_once("./classes/Saida.class.php");
require_once("./classes/Ctabela.class.php");
require_once("./classes/Ftabela.class.php");
require_once("./grafico/phplot.php");

extract($_GET);
$hoje = date("Y") . "-" . date("m") . "-" . date("d");

if ($_GET['acao'] == 'G1') {
    $entrada = new Entrada();
    $sql = "select m.nome as material, sum(e.peso) as peso, ROUND(avg(e.valor),2) as valor
            from entrada e, material m 
            where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_GET['data'] . "'  
            and e.id_mat = m.id
            group by m.nome
            order by  m.nome";
    $entrada->selecionaLivre($sql);
    $data = array();
    while ($d = $entrada->retornaDados()) {
        $data[] = array($d->material, $d->peso);
    }
    gera($data, "Entrada Material ");
} else if ($_GET['acao'] == 'G2') {
    $entrada = new Entrada();
    $sql = "select m.nome as material, sum(e.peso) as peso, ROUND(avg(e.valor),2) as valor
            from entrada e, material m 
            where DATE_FORMAT(e.dt, '%Y-%m-%d') = '" . $_GET['data'] . "'  
            and e.id_mat = m.id
            group by m.nome
            order by  m.nome";
    $entrada->selecionaLivre($sql);
    $data = array();
    while ($d = $entrada->retornaDados()) {
        $data[] = array($d->material, $d->peso);
    }
    gera($data, "Entrada x Peso Final ");
}

function gera($data, $t, $nome) {
    $plot = new PHPlot(600, 400);
    $plot->SetImageBorderType('plain'); // Improves presentation in the manual
    $plot->SetTitle($t . $_GET['data']);
//$plot->SetBackgroundColor('gray');
//$plot->SetPlotAreaBgImage('images/drop.png', 'centeredtile');
    $plot->SetPlotAreaWorld(0);
    $plot->SetYTickPos('none');
//$plot->SetXTickPos('none');
//$plot->SetXTickLabelPos('none');
    $plot->SetXDataLabelPos('plotin');
//$plot->SetDrawXGrid(FALSE);
    $plot->SetDataColors('purple');
//$plot->SetShading(4);
    $plot->SetDataValues($data);
    $plot->SetDataType('text-data-yx');
    $plot->SetPlotType('bars');
    $plot->SetBackGroundColor("white");

//    $plot->SetDataColors('RoyalBlue2');

    $plot->SetIsInline(true);
    $plot->SetOutputFile('./controle/grafico/' + $nome + '.png');
    $plot->DrawGraph();
}
?>
<!--<html>
    <img src='grafico.php?acao=G1' />
</html>-->
