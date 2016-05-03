$(document).ready(function() {
    function relatorio() {
        $(".container").empty();
        $(".container").append("\
<form class='form-horizontal' name='fnova_tabela' method='POST' role='form'>\n\
<div class='form-group'>\n\
    <div class='row'>\n\
        <div class='col-xs-3'><blockquote><p>Relatório</p></blockquote></div>\n\
        <div class='col-xs-6'>\n\
            <div class='input-group'>\n\
                <span class='input-group-addon'>Data</span><input type='text' class='form-control input-lg' id='datepicker'>\n\
            </div>\n\
        </div>\n\
    </div>\n\
    <div class='row'>\n\
        <div class='col-xs-12' style=' text-align: center'>\n\
            <div class='btn-group' role='group'>\n\
                <button type='button' class='btn btn-default input-lg' id='btn_por_resumo'>Resumo</button>\n\
                <button type='button' class='btn btn-default input-lg' id='btn_por_entrada'>Entrada</button>\n\
                <button type='button' class='btn btn-default input-lg' id='btn_por_cliente'>Cliente</button>\n\
                <button type='button' class='btn btn-default input-lg' id='btn_por_material'>Material</button>\n\
                <button type='button' class='btn btn-default input-lg' id='btn_por_peso_final'>Peso</button>\n\
                <button type='button' class='btn btn-default input-lg' id='btn_por_mat_peso'>Material x Peso</button>\n\
                <button type='button' class='btn btn-default input-lg' id='outros'>Outros</button>\n\
          </div>\n\
        </div>\n\
    </div>\n\
</div>\n\
</form>");
        $("#datepicker").datepicker();
        $("#datepicker").datepicker("option", "dateFormat", "yy-mm-dd");
        $btns = $("#btn_por_entrada, #btn_por_cliente, #btn_por_material, #btn_por_peso_final, #btn_por_mat_peso, #btn_por_resumo, #outros");
        $btns.click(function() {
            gera($(this).attr("id"));
            $btns.removeClass();
            $btns.addClass("btn btn-default input-lg");
            $(this).removeClass();
            $(this).addClass("btn btn-info input-lg");
        }).attr("disabled", "disabled");
        $("#datepicker").on('change', function() {
            gera($("#btn_por_resumo").attr("id"));
            $btns.removeAttr("disabled");
        });
        $("#outros").on('click', function() {
            $("table").remove();
            $(".container").append("<table width=100%></table>");
            $("table").append("<tr><td style='text-align: center'><img src='./controle/grafico.php?data=" + $('#datepicker').val() + "&acao=G1' / ></td></tr>");
            $("tr").append("<td style='text-align: center'><img src='./controle/grafico/g1.png' / ></td>");
        });
    }
    function gera($a) {
        $.ajax({
            type: "POST",
            url: "./controle/relatorio.php",
            data: {acao: $a, data: $("#datepicker").val()},
            beforeSend: function() {
                $("table").remove();
            },
            error: function(data) {
                alert("ERRO RELATORIO ".$a);
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                $(".titulo_relatorio").append("<button type='button' class='btn btn-link' id='pdf' relat=''>Salvar</button>");
                $("#pdf").on("click", function() {
                    window.open('./controle/pdf.php');
                })
            }
        })
    }
    $("#relatorio").click(function() {
        $("li").removeClass("active");
        $(this).parent("li").addClass("active");
        relatorio($(this).attr("id"));
    });
});