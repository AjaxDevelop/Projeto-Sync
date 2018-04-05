<?php

    include_once 'cliente_venda.php';

    class Linha
    {
        private $db_origem;
        private $db_destino;
        public $venda_id;
        private $cliente_venda;

        public function __construct($base_origem = null, $base_destino = null, $venda_id = null)
        {
            if ($base_origem != null && $base_destino != null && $venda_id != null)
            {
                $this->venda_id = $venda_id;
                $this->db_origem = $base_origem;
                $this->db_destino = $base_destino;
                $this->cliente_venda = new Cliente_Venda($base_origem, $base_destino, $venda_id);
            }
            else
            {
                print PHP_EOL . "Erro ao criar objeto Linha" . PHP_EOL;
            }

        }

        public function getDados()
        {
            $query_select = $this->db_origem->query("SELECT * FROM linhas AS l WHERE l.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            return $query_select;
        }

        public function limparDestino()
        {
            $query_select = $this->db_origem->query("SELECT * FROM linhas AS l WHERE l.venda_id = '" . $this->venda_id . "'");

            if ($query_select != "")
            {
                try {
                    while ($data = $query_select->fetch(PDO::FETCH_OBJ))
                    {
                        $query_delete = $this->db_destino->prepare("DELETE FROM linhas_pessoas WHERE linha_id = :linha_id");
                        $query_delete->bindValue(':linha_id', $data->id);
                        $query_delete->execute();

                        $query_delete = $this->db_destino->prepare("DELETE FROM linhas WHERE id = :id");
                        $query_delete->bindValue(':id', $data->id);
                        $query_delete->execute();
                    }
                }
                catch (PDOException $e)
                {
                    print PHP_EOL . "ERRO EM LINHA: " . $e->getMessage() . PHP_EOL;
                }
            }


        }

        public function atualizar()
        {
            # Dados do Cliente
            $clientes_vendas = $this->cliente_venda->getClientesVendasOrigem();
            $pessoa_id = $clientes_vendas[1]['pessoa_id'];

            $query_select = $this->getDados();

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                $query_insert_linhas = $this->db_destino->prepare("INSERT INTO linhas(`venda_id`, `tipo`, `perfil`, `numero`, `modalidade`, `iccid`, `cpf`) VALUES(:venda_id, :tipo, :perfil, :numero, :modalidade, :iccid, :cpf)");
                $query_insert_linhas->bindValue(':venda_id', $this->venda_id);
                $query_insert_linhas->bindValue(':tipo', $data->tipo);
                $query_insert_linhas->bindValue(':perfil', $data->perfil);
                $query_insert_linhas->bindValue(':numero', $data->numero);
                $query_insert_linhas->bindValue(':modalidade', $data->modalidade);
                $query_insert_linhas->bindValue(':iccid', $data->iccid);
                $query_insert_linhas->bindValue(':cpf', $data->cpf);
                $query_insert_linhas->execute();

                $last_id = $this->db_destino->lastInsertId();

                $query_insert = $this->db_destino->prepare("INSERT INTO linhas_pessoas(`linha_id`, `pessoa_id`) VALUES(:linha_id, :pessoa_id)");
                $query_insert->bindValue(':linha_id', $last_id);
                $query_insert->bindValue(':pessoa_id', $pessoa_id);
                $query_insert->execute();
            }

        }

    }

?>