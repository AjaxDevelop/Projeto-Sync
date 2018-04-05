<?php
    # Incluir dependencias
    include_once 'config.php';

    $root_class = ROOT_PATH . '/classes/*.php';

    foreach (glob($root_class) as $filename)
    {
        include $filename;
    }

    # Resgata os dados da requisição
    $action = strtoupper($argv[1]);
    $start = $argv[2];
    $end =  $argv[3];

    if (($action == "PRO" || $action == "TEST") && (is_numeric($start) && is_numeric($end)))
    {
        # index
        print "\n**************************************";
        print "\n*        Atualizacao Iniciada        *";
        print "\n**************************************\n";

        # Instânciar bases de dados
        switch ($action)
        {
            case 'PRO':
                $db_host_origen = DB_HOST_ORIGEN_PRO;
                $db_base_origen = DB_BASE_ORIGEN_PRO;
                $db_user_origen = DB_USER_ORIGEN_PRO;
                $db_pass_origen = DB_PASS_ORIGEN_PRO;
                $db_host_destino = DB_HOST_DESTINY_PRO;
                $db_base_destino = DB_BASE_DESTINY_PRO;
                $db_user_destino = DB_USER_DESTINY_PRO;
                $db_pass_destino = DB_PASS_DESTINY_PRO;
                break;

            case 'TEST':
                $db_host_origen = DB_HOST_ORIGEN_TEST;
                $db_base_origen = DB_BASE_ORIGEN_TEST;
                $db_user_origen = DB_USER_ORIGEN_TEST;
                $db_pass_origen = DB_PASS_ORIGEN_TEST;
                $db_host_destino = DB_HOST_DESTINY_TEST;
                $db_base_destino = DB_BASE_DESTINY_TEST;
                $db_user_destino = DB_USER_DESTINY_TEST;
                $db_pass_destino = DB_PASS_DESTINY_TEST;
                break;

            default:
                $db_host_origen = DB_HOST_ORIGEN_TEST;
                $db_base_origen = DB_BASE_ORIGEN_TEST;
                $db_user_origen = DB_USER_ORIGEN_TEST;
                $db_pass_origen = DB_PASS_ORIGEN_TEST;
                $db_host_destino = DB_HOST_DESTINY_TEST;
                $db_base_destino = DB_BASE_DESTINY_TEST;
                $db_user_destino = DB_USER_DESTINY_TEST;
                $db_pass_destino = DB_PASS_DESTINY_TEST;
        }

        $db_origem = Connect::conexao($db_host_origen, $db_base_origen, $db_user_origen, $db_pass_origen);
        $db_destino = Connect::conexao($db_host_destino, $db_base_destino, $db_user_destino, $db_pass_destino);

        # Index inicial
        $index = $start;

        while ($index <= $end)
        {
            $venda_id = $index;

            # Verifica se existe uma venda na horigem com o ID informado
            $venda_obj = new Venda($db_origem, $db_destino, $venda_id);

            if ($venda_obj->eVendaOrigem())
            {
                # Listar Classes a serem atualizadas.
                $class_list = [
                    'Venda',
                    'Observacao',
                    'Linha',
                    'Plano_Produto_Venda',
                    'Cliente_Venda',
                    'Endereco',
                    'Contato'
                ];

                # Desabilitar a verificação de restrição por chave
                $db_destino->query("SET FOREIGN_KEY_CHECKS=0;");

                # Percorrer lista de classes
                foreach ($class_list as $key => $class)
                {
                    # Instânciar classe
                    $obj = new $class($db_origem, $db_destino, $venda_id);

                    # Limpar
                    //print PHP_EOL . "Limpar " . $class . "s...";
                    $obj->limparDestino();

                    # Destruir objeto
                    unset($obj);
                }

                foreach ($class_list as $key => $class)
                {
                    # Instânciar classe
                    $obj = new $class($db_origem, $db_destino, $venda_id);

                    # Atualizar
                    //print PHP_EOL . "Atualizando " . $class . "s...";
                    $obj->atualizar();

                    # Destruir objeto
                    unset($obj);
                }

                # Habilitar a verificação de restrição por chave
                $db_destino->query("SET FOREIGN_KEY_CHECKS=1;");

                print("true");
            }
            else
            {
                print("false");
            }

            print PHP_EOL . $index . PHP_EOL;

            ++$index;

        }

        # End
        print "\n\n**************************************";
        print "\n* Atualizacao Finalizada com sucesso *";
        print "\n**************************************\n";

    }
    else
    {
        print PHP_EOL . "ERRO => Paramero invalido!";
    }

?>