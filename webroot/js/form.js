$(document).ready(function(){

    //Open Modal
    $('#myModal').modal('show');

    $('body').on('click', '#BTNsync', function(){
        // GET id
        var venda_id = $("#venda_id").val();

        if (venda_id != "")
        {
            //Bloquear botão
            $('#BTNsync').prop('disabled', true);

            sincronizar(venda_id);

        }
        else
        {
            //Retornar mensage.
            var mensagem = "Por favor, informe o ID da Venda a ser atualizada!";
            exibirLog(mensagem, false);

        }

        console.log("Venda ID: " + venda_id);
    });

    var sincronizar = function(venda_id)
    {
        //Dados da requisição
        var dados = {
            id: venda_id
        };

        //Atualizar venda (Ajax)
        $.ajax({
            method: 'get',
            url: basePath + "/sync.php",
            data: 'id=' + venda_id,
            success: function (resposta) {
                //Liberar botão
                $('#BTNsync').prop('disabled', false);

                if (resposta == "true" || resposta == true)
                {
                    //Retornar mensage.
                    var mensagem = "Venda atualizada com sucesso!";
                    exibirLog(mensagem, true);
                }
                else
                {
                    //Retornar mensage.
                    var mensagem = "O servidor não conseguiu processar a sua solicitação! Por favor, entre em contato com a equipe de suporte.**";
                    exibirLog(mensagem, false);
                }

                console.log(resposta);

            },
            error: function(response) {
                //Liberar botão
                $('#BTNsync').prop('disabled', false);

                //Retornar mensage.
                var mensagem = "O servidor não conseguiu processar a sua solicitação! Por favor, entre em contato com a equipe de suporte.**";
                exibirLog(mensagem, false);

                console.log(response);

            }
        });
    }

    var exibirLog = function(mensagem, tipo)
    {
        //Verifica o tipo de log a ser exibido.
        if (tipo == true) {
            var modal = 'myModalSuccess',
                box = 'alertSuccess',
                log = 'logSuccess';
        } else {
            var modal = 'myModalLog',
                box = 'alertLog',
                log = 'log';
        }

        //Retornar mensage.
        $('#'+log).text(mensagem);
        $('.'+box).show();

        //Exibir modal.
        $('#'+modal).modal("show");

    };

});
