$(document).ready(function() {
    function verde() {
        back = "#dff0d8";
        font = "#3c763d";
        border = "#d6e9c6";
        $("span[class='input-group-addon']").css({"background-color": back, "color": font, "border-color": border});
        $('blockquote').css("border-left-color", back);
    }
    function azul() {
        back = "#d9edf7";
        font = "#31708f";
        border = "#bce8f1";
        $("span[class='input-group-addon']").css({"background-color": back, "color": font});
        $('blockquote').css("border-left-color", back);
    }
    function amarelo() {
        back = "#fcf8e3";
        font = "#8a6d3b";
        border = "#faebcc";
        $("span[class='input-group-addon']").css({"background-color": back, "color": font, "border-color": border});
        $('blockquote').css("border-left-color", back);
    }
    function vermelho() {
        back = "#f2dede";
        font = "#a94442";
        border = "#ebccd1";
        $("span[class='input-group-addon']").css({"background-color": back, "color": font, "border-color": border});
        $('blockquote').css("border-left-color", back);
    }
    function totalPagar() {
        $pagar = 0;
        $a = "<table class='table table-hover table-striped table-bordered' style='margin-top: 10px'><thead><tr><th>Material</th><th>Peso</th><th>Valor/KG </th><th>Valor Pago</th></tr></thead><tbody>";
        $('.vale').each(function(index, value) {
            $a += "<tr><td>" + $(this).find('td.material_apagar').text() + "</td><td>" + $(this).find('td.peso_apagar').text() + "</td><td>" + $(this).find('td.valor_apagar').text() + "</td><td>" + $(this).find('td.pago_apagar').text() + "</td></tr>";
            $pagar =  $pagar + parseFloat($(this).find('td.pago_apagar').text().substr(3));
        });
        $a += "<tr style='background-color: #fdd'><td colspan='4'>" + $pagar.toFixed(2) + "</td></tr></tbody></table>";
        jAlert($a, 'Total a Pagar');
        $("#popup_ok").click(function() {
            $(".vale").removeClass('vale');
            $(".material_apagar").removeClass('material_apagar');
            $(".peso_apagar").removeClass('peso_apagar');
            $(".valor_apagar").removeClass('valor_apagar');
            $(".pago_apagar").removeClass('pago_apagar');
        })
    }
    function excluirEntrada() {
        $(".excluir_entrada").on("click", (function() {
//            alert($(this).attr('id'));

            $linha = $(this)
            jConfirm('ATENÇÃO!!! <br> DESEJA EXCLUIR REALMENTE?', 'Excluir Entrada', function(r) {
                if (r) {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "excluir_entrada",
                            id: $linha.attr('id')},
                        beforeSend: function() {
//                                                    $(".container").empty();
                        },
                        error: function(data) {
                            alert("ERRO excluir_entrada");
                        },
                        success: function(data, textStatus, jqXHR) {
//                    alert(data);
                            $linha.parents("tr").remove();
                        }
                    });

                    jAlert('ENTRADA EXCLUIDA', 'Excluir Entrada');
                }
            });
        }));
    }
    $("#entrada").click(function() {
        $("li").removeClass("active");
        $(this).parent("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "entrada1"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO ENTRADA1");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                function idCliente(id) {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "entrada2",
                            idCliente: id
                        },
                        beforeSend: function() {
                            $(".container_entrada").children("div").remove("div");
                        },
                        error: function(data) {
                            alert("ERRO AO SELECIONAR CLIENTE NA ENTRADA");
                        },
                        success: function(data, textStatus, jqXHR) {
                            $(".container_entrada").append(data);
                            $("#id_cliente").val(id).attr("selected", true);
                            azul();
                            $("input.maskDinheiro").maskMoney({decimal: ".", thousands: "", precision: 3});
                            excluirEntrada();
                            $("#salvar_entrada").on("click", function() {
                                $.ajax({
                                    type: "POST",
                                    url: "./controle/controle.php",
                                    data: {acao: "salvar_entrada",
                                        idCliente: $("#id_cliente").val(),
                                        Ferro: [$("#KgFerro").attr("idMaterial"), $("#KgFerro").val(), $("#valorFerro").attr("valor")],
                                        Chapa: [$("#KgChapa").attr("idMaterial"), $("#KgChapa").val(), $("#valorChapa").attr("valor")],
                                        Papelão: [$("#KgPapelão").attr("idMaterial"), $("#KgPapelão").val(), $("#valorPapelão").attr("valor")],
                                        Pet: [$("#KgPet").attr("idMaterial"), $("#KgPet").val(), $("#valorPet").attr("valor")],
                                        Plástico: [$("#KgPlástico").attr("idMaterial"), $("#KgPlástico").val(), $("#valorPlástico").attr("valor")],
                                        Cobre: [$("#KgCobre").attr("idMaterial"), $("#KgCobre").val(), $("#valorCobre").attr("valor")],
                                        Mel: [$("#KgMel").attr("idMaterial"), $("#KgMel").val(), $("#valorMel").attr("valor")],
                                        Metal: [$("#KgMetal").attr("idMaterial"), $("#KgMetal").val(), $("#valorMetal").attr("valor")],
                                        Perfil: [$("#KgPerfil").attr("idMaterial"), $("#KgPerfil").val(), $("#valorPerfil").attr("valor")],
                                        Alumínio: [$("#KgAlumínio").attr("idMaterial"), $("#KgAlumínio").val(), $("#valorAlumínio").attr("valor")],
                                        Latinha: [$("#KgLatinha").attr("idMaterial"), $("#KgLatinha").val(), $("#valorLatinha").attr("valor")],
                                        Bloco: [$("#KgBloco").attr("idMaterial"), $("#KgBloco").val(), $("#valorBloco").attr("valor")],
                                        Antimônio: [$("#KgAntimônio").attr("idMaterial"), $("#KgAntimônio").val(), $("#valorAntimônio").attr("valor")],
                                        Aço: [$("#KgAço").attr("idMaterial"), $("#KgAço").val(), $("#valorAço").attr("valor")],
                                        Motor: [$("#KgMotor").attr("idMaterial"), $("#KgMotor").val(), $("#valorMotor").attr("valor")],
                                        Chumbo: [$("#KgChumbo").attr("idMaterial"), $("#KgChumbo").val(), $("#valorChumbo").attr("valor")],
                                        LimMetal: [$("#KgLimMetal").attr("idMaterial"), $("#KgLimMetal").val(), $("#valorLimMetal").attr("valor")],
                                        RadMetal: [$("#KgRadMetal").attr("idMaterial"), $("#KgRadMetal").val(), $("#valorRadMetal").attr("valor")],
                                        RadBloco: [$("#KgRadBloco").attr("idMaterial"), $("#KgRadBloco").val(), $("#valorRadBloco").attr("valor")],
                                        PapelBR: [$("#KgPapelBR").attr("idMaterial"), $("#KgPapelBR").val(), $("#valorPapelBR").attr("valor")],
                                        OffSet: [$("#KgOffSet").attr("idMaterial"), $("#KgOffSet").val(), $("#valorOffSet").attr("valor")],
                                        Placa: [$("#KgPlaca").attr("idMaterial"), $("#KgPlaca").val(), $("#valorPlaca").attr("valor")],
                                        Bateria: [$("#KgBateria").attr("idMaterial"), $("#KgBateria").val(), $("#valorBateria").attr("valor")],
                                        RadAlumínio: [$("#KgRadAlumínio").attr("idMaterial"), $("#KgRadAlumínio").val(), $("#valorRadAlumínio").attr("valor")],
                                        Magnésio: [$("#KgMagnésio").attr("idMaterial"), $("#KgMagnésio").val(), $("#valorMagnésio").attr("valor")]
                                    },
                                    beforeSend: function() {
                                        $("form input:text").val('');
                                    },
                                    error: function(data) {
                                        alert("ERRO salvar_entrada");
                                    },
                                    success: function(data, textStatus, jqXHR) {
                                        $("tr").attr('style', '');
                                        $("tbody").prepend(data);
                                        totalPagar(data);
                                        excluirEntrada();
                                    }
                                });
                            });
                        }
                    });
                }
                idCliente(1);
                $("#id_cliente").on("change", function() {
                    idCliente($("#id_cliente").val());
                });
            }
        });
    });
// CLIENTE ###############################################################
    $("#novo_cliente").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "novo_cliente"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO novo_cliente");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                tel();
                $("#salvar_cliente").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "salvar_cliente",
                            nome: $("#nome").val(),
                            end: $("#end").val(),
                            tel: $("#tel").val(),
                            obs: $("#obs").val()
                        },
                        beforeSend: function() {
                            //ZERAR CAMPOS
                        },
                        error: function(data) {
                            alert("ERRO salvar_cliente");
                        },
                        success: function(data, textStatus, jqXHR) {
                            alert(data);
                        }
                    });
                });
            }
        });
    });
    $("#atualizar_cliente").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "atualizar_cliente"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO atualizar_cliente");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                $("#salvar_atualizar_cliente").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "salvar_atualizar_cliente",
                            idCliente: $("#id_cliente").val()
                        },
                        beforeSend: function() {
                            //ZERAR CAMPOS
                        },
                        error: function(data) {
                            alert("ERRO salvar_atualizar_cliente");
                        },
                        success: function(data, textStatus, jqXHR) {
                            alert("Cliente Atualizado!");
                        }
                    });
                });
            }
        });
    });

// TABELA CLIENTE###############################################################
    function idCliente(id) {
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "consulta_tabela_cliente",
                idCliente: id
            },
            beforeSend: function() {
            },
            error: function(data) {
                alert("ERRO AO SELECIONAR CLIENTE NA CONSULTA TABELA");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container form").append(data);
                $("#id_cliente").val(id).attr("selected", true);
                $(".valor").each(function() {
                    $(this).text('R$ 0.00');
                });
                $("input:hidden").each(function() {
                    $("#valor" + $(this).attr('nome')).text('R$ ' + $(this).attr('valor'));
                });
                $("form input:hidden").remove();
                verde();
            }
        });
    }
    $("#nova_tabela_cliente").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "nova_tabela_cliente"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO NOVA_TABELA_CLIENTE");
            },
            success: function(data, textStatus, jqXHR) {
                //$(location).attr('href', 'default.html');
                $(".container").append(data);
                $("input.maskDinheiro").maskMoney({decimal: ".", thousands: "", precision: 2});
                idCliente(1);
                $("#id_cliente").on("change", function() {
                    idCliente($("#id_cliente").val());
                });
                $("#salvar_tabela_cliente").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "salvar_tabela_cliente",
                            idCliente: $("#id_cliente").val(),
                            Ferro: [$("#Ferro").attr("idMaterial"), $("#Ferro").val()],
                            Chapa: [$("#Chapa").attr("idMaterial"), $("#Chapa").val()],
                            Papelão: [$("#Papelão").attr("idMaterial"), $("#Papelão").val()],
                            Pet: [$("#Pet").attr("idMaterial"), $("#Pet").val()],
                            Plástico: [$("#Plástico").attr("idMaterial"), $("#Plástico").val()],
                            Cobre: [$("#Cobre").attr("idMaterial"), $("#Cobre").val()],
                            Mel: [$("#Mel").attr("idMaterial"), $("#Mel").val()],
                            Metal: [$("#Metal").attr("idMaterial"), $("#Metal").val()],
                            Perfil: [$("#Perfil").attr("idMaterial"), $("#Perfil").val()],
                            Alumínio: [$("#Alumínio").attr("idMaterial"), $("#Alumínio").val()],
                            Latinha: [$("#Latinha").attr("idMaterial"), $("#Latinha").val()],
                            Bloco: [$("#Bloco").attr("idMaterial"), $("#Bloco").val()],
                            Antimônio: [$("#Antimônio").attr("idMaterial"), $("#Antimônio").val()],
                            Aço: [$("#Aço").attr("idMaterial"), $("#Aço").val()],
                            Motor: [$("#Motor").attr("idMaterial"), $("#Motor").val()],
                            Chumbo: [$("#Chumbo").attr("idMaterial"), $("#Chumbo").val()],
                            LimMetal: [$("#LimMetal").attr("idMaterial"), $("#LimMetal").val()],
                            RadMetal: [$("#RadMetal").attr("idMaterial"), $("#RadMetal").val()],
                            RadBloco: [$("#RadBloco").attr("idMaterial"), $("#RadBloco").val()],
                            PapelBR: [$("#PapelBR").attr("idMaterial"), $("#PapelBR").val()],
                            OffSet: [$("#OffSet").attr("idMaterial"), $("#OffSet").val()],
                            Placa: [$("#Placa").attr("idMaterial"), $("#Placa").val()],
                            Bateria: [$("#Bateria").attr("idMaterial"), $("#Bateria").val()],
                            RadAlumínio: [$("#RadAlumínio").attr("idMaterial"), $("#RadAlumínio").val()],
                            Magnésio: [$("#Magnésio").attr("idMaterial"), $("#Magnésio").val()]
                        },
                        beforeSend: function() {
                            $("form input:text").val('');
                        },
                        error: function(data) {
                            alert("ERRO salvar_tabela_cliente");
                        },
                        success: function(data, textStatus, jqXHR) {
                            idCliente($("#id_cliente").val());
                            alert("Cadastrado!");
                        }
                    });
                });
            }
        });
    });

// FORNECEDOR ###############################################################
    $("#novo_fornecedor").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "novo_fornecedor"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO novo_cliente");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                tel();
                $("#salvar_fornecedor").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "salvar_fornecedor",
                            nome: $("#nome").val(),
                            end: $("#end").val(),
                            tel: $("#tel").val(),
                            obs: $("#obs").val()
                        },
                        beforeSend: function() {
                            $("input:text").val('');
                        },
                        error: function(data) {
                            alert("ERRO salvar_fornecedor");
                        },
                        success: function(data, textStatus, jqXHR) {
                            alert(data);
                        }
                    });
                });
            }
        });
    });
    $("#atualizar_fornecedor").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "atualizar_fornecedor"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO atualizar_fornecedor");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                $("#salvar_atualizar_fornecedor").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "salvar_atualizar_fornecedor",
                            idCliente: $("#id_cliente").val()
                        },
                        beforeSend: function() {
                            //ZERAR CAMPOS
                        },
                        error: function(data) {
                            alert("ERRO salvar_atualizar_fornecedor");
                        },
                        success: function(data, textStatus, jqXHR) {
                            alert("Fornecedor Atualizado!");
                        }
                    });
                });
            }
        });
    });

// TABELA FORNECEDOR ###############################################################
    function idFornecedor(id) {
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "consulta_tabela_fornecedor",
                idFornecedor: id
            },
            beforeSend: function() {
            },
            error: function(data) {
                alert("ERRO AO SELECIONAR FORNECEDOR NA CONSULTA TABELA");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container form").append(data);
                $("#id_fornecedor").val(id).attr("selected", true);
                $(".valor").each(function() {
                    $(this).text('R$ 0.00');
                });
                $("input:hidden").each(function() {
                    $("#valor" + $(this).attr('nome')).text('R$ ' + $(this).attr('valor'));
                });
                $("form input:hidden").remove();
                amarelo();
            }
        });
    }
    $("#nova_tabela_fornecedor").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "nova_tabela_fornecedor"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO NOVA_TABELA_FORNECEDOR");
            },
            success: function(data, textStatus, jqXHR) {
                //$(location).attr('href', 'default.html');
                $(".container").append(data);
                $("input.maskDinheiro").maskMoney({decimal: ".", thousands: "", precision: 2});
                idFornecedor(1);
                $("#id_fornecedor").on("change", function() {
                    idFornecedor($("#id_fornecedor").val());
                });
                $("#salvar_tabela_fornecedor").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "salvar_tabela_fornecedor",
                            idFornecedor: $("#id_fornecedor").val(),
                            Ferro: [$("#Ferro").attr("idMaterial"), $("#Ferro").val()],
                            Chapa: [$("#Chapa").attr("idMaterial"), $("#Chapa").val()],
                            Papelão: [$("#Papelão").attr("idMaterial"), $("#Papelão").val()],
                            Pet: [$("#Pet").attr("idMaterial"), $("#Pet").val()],
                            Plástico: [$("#Plástico").attr("idMaterial"), $("#Plástico").val()],
                            Cobre: [$("#Cobre").attr("idMaterial"), $("#Cobre").val()],
                            Mel: [$("#Mel").attr("idMaterial"), $("#Mel").val()],
                            Metal: [$("#Metal").attr("idMaterial"), $("#Metal").val()],
                            Perfil: [$("#Perfil").attr("idMaterial"), $("#Perfil").val()],
                            Alumínio: [$("#Alumínio").attr("idMaterial"), $("#Alumínio").val()],
                            Latinha: [$("#Latinha").attr("idMaterial"), $("#Latinha").val()],
                            Bloco: [$("#Bloco").attr("idMaterial"), $("#Bloco").val()],
                            Antimônio: [$("#Antimônio").attr("idMaterial"), $("#Antimônio").val()],
                            Aço: [$("#Aço").attr("idMaterial"), $("#Aço").val()],
                            Motor: [$("#Motor").attr("idMaterial"), $("#Motor").val()],
                            Chumbo: [$("#Chumbo").attr("idMaterial"), $("#Chumbo").val()],
                            LimMetal: [$("#LimMetal").attr("idMaterial"), $("#LimMetal").val()],
                            RadMetal: [$("#RadMetal").attr("idMaterial"), $("#RadMetal").val()],
                            RadBloco: [$("#RadBloco").attr("idMaterial"), $("#RadBloco").val()],
                            PapelBR: [$("#PapelBR").attr("idMaterial"), $("#PapelBR").val()],
                            OffSet: [$("#OffSet").attr("idMaterial"), $("#OffSet").val()],
                            Placa: [$("#Placa").attr("idMaterial"), $("#Placa").val()],
                            Bateria: [$("#Bateria").attr("idMaterial"), $("#Bateria").val()],
                            RadAlumínio: [$("#RadAlumínio").attr("idMaterial"), $("#RadAlumínio").val()],
                            Magnésio: [$("#Magnésio").attr("idMaterial"), $("#Magnésio").val()]
                        },
                        beforeSend: function() {
                            $("form input:text").val('');
                        },
                        error: function(data) {
                            alert("ERRO salvar_tabela_fornecedor");
                        },
                        success: function(data, textStatus, jqXHR) {
                            idFornecedor($("#id_fornecedor").val());
                            alert("Cadastrado!");
                        }
                    });
                });
            }
        });
    });
    $("#consulta_tabela_fornecedor").click(function() {
        $("li").removeClass("active");
        $(this).parents("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "consulta_tabela"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO consulta_tabela_fornecedor");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                function idFornecedor(id) {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "consulta_tabela_fornecedor2",
                            idCliente: id
                        },
                        beforeSend: function() {
                            $(".container_tabela").children("div").remove("div");
                        },
                        error: function(data) {
                            alert("ERRO AO SELECIONAR FORNECEDOR NA CONSULTA TABELA");
                        },
                        success: function(data, textStatus, jqXHR) {
                            $(".container_tabela").append(data);
                        }
                    });
                }
                idFornecedor(1);
                $("#id_fornecedor").on("change", function() {
                    idFornecedor($("#id_fornecedor").val());
                });
            }
        });
    });

// CAIXA ###############################################################
    $("#caixa").click(function() {
        $("li").removeClass("active");
        $(this).parent("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "caixa"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO CAIXA");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                $("input.maskDinheiro").maskMoney({decimal: ".", thousands: "", precision: 2});
                $('form').bind("keypress", function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                $("#entrada_caixa_salvar").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "entrada_caixa",
                            entrada: $("#entrada_caixa").val(),
                            obs: $("#obs_caixa").val()
                        },
                        beforeSend: function() {
                            $("#entrada_caixa").val('');
                            $("#obs_caixa").val('');
                        },
                        error: function(data) {
                            alert("ERRO ENTRADA_CAIXA");
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (data) {
                                $(".success").after(data);
                                excluir();
                            }
                        }
                    });
                });
                excluir();
                function excluir() {
                    $(".excluir_caixa").on("click", function() {
                        id = $(this).attr("idEntrada");
                        $.ajax({
                            type: "POST",
                            url: "./controle/controle.php",
                            data: {acao: "excluir_entrada_caixa",
                                id: id
                            },
                            error: function(data) {
                                alert("ERRO EXCLUIR_CAIXA");
                            },
                            success: function(data, textStatus, jqXHR) {
                                $("#entrada" + id).remove();

                            }
                        });
                    });
                }
            }
        });
    });
// FIRMA ###############################################################
    $("#firma").click(function() {
        $("li").removeClass("active");
        $(this).parent("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "firma"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO FIRMA");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                $("input.maskDinheiro").maskMoney({decimal: ".", thousands: "", precision: 2});
                $('form').bind("keypress", function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                $("#saida_firma_salvar").on("click", function() {
                    $.ajax({
                        type: "POST",
                        url: "./controle/controle.php",
                        data: {acao: "saida_firma",
                            saida: $("#saida_firma").val(),
                            obs: $("#obs_firma").val()
                        },
                        beforeSend: function() {
                            $("#saida_firma").val('');
                            $("#obs_firma").val('');
                        },
                        error: function(data) {
                            alert("ERRO SAIDA_FIRMA");
                        },
                        success: function(data, textStatus, jqXHR) {
                            if (data) {
                                $(".danger").after(data);
                                excluir();
                            }
                        }
                    });
                });
                excluir();
                function excluir() {
                    $(".excluir_firma").on("click", function() {
                        id = $(this).attr("idSaida");
                        $.ajax({
                            type: "POST",
                            url: "./controle/controle.php",
                            data: {acao: "excluir_saida_firma",
                                id: id
                            },
                            error: function(data) {
                                alert("ERRO EXCLUIR_FIRMA");
                            },
                            success: function(data, textStatus, jqXHR) {
                                $("#saida" + id).remove();

                            }
                        });
                    });
                }
            }
        });
    });

    //   PESO FINAL DO DIA ####################################################
    $("#peso").click(function() {
        $("li").removeClass("active");
        $(this).parent("li").addClass("active");
        $.ajax({
            type: "POST",
            url: "./controle/controle.php",
            data: {acao: "peso_final"},
            beforeSend: function() {
                $(".container").empty();
            },
            error: function(data) {
                alert("ERRO PESO FINAL");
            },
            success: function(data, textStatus, jqXHR) {
                $(".container").append(data);
                $("input.maskDinheiro").maskMoney({decimal: ".", thousands: "", precision: 3});
                excluirPeso();
                $("input:text").on("change", function() { // INSSERT VALOR NA TABELA PESO
                    if ($(this).val()) {
//                        alert($(this).attr("idMaterial") + "," + $(this).val());
                        $material = $(this);
                        //                        $material = $(this).parent("div");
                        $.ajax({
                            type: "POST",
                            url: "./controle/controle.php",
                            data: {acao: "salvar_peso",
                                id: $(this).attr("idMaterial"),
                                valor: $(this).val()
                            },
                            beforeSend: function() {

                            },
                            error: function(data) {
                                alert("ERRO salvar_peso");
                            },
                            success: function(data, textStatus, jqXHR) {
                                $material.attr("disabled", "disabled").after("<span class='input-group-addon'><button type='button' class='close excluir_peso' id=" + data + ">&times;</button></span>");
                                excluirPeso();
                            }
                        });
                    }
                })
                function excluirPeso() {
                    $(".excluir_peso").on("click", function() {
                        $btn_excluir = $(this);
                        $.ajax({
                            type: "POST",
                            url: "./controle/controle.php",
                            data: {acao: "excluir_peso",
                                id: $btn_excluir.attr("id")
                            },
                            beforeSend: function() {
                            },
                            error: function(data) {
                                alert("ERRO excluir_peso");
                            },
                            success: function(data, textStatus, jqXHR) {
                                $btn_excluir.parent().siblings("input").attr("disabled", false).val("");
                                $btn_excluir.parent().remove();
                            }
                        });
                    })
                }
            }
        })
    });
    //DESABILITA A TECLA ENTER DE TODOS OS FORMS
    $('form').bind("keypress", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    function tel() {
        var SPMaskBehavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };
        $('#tel').mask(SPMaskBehavior, spOptions);
    }
});