<?php

    class RestoryObservacao
    {
        private $db_origem;
        private $db_destino;
        public $observacao_origem = [];

        public function __construct($base_origem = null, $base_destino = null)
        {
            if ($base_origem != null && $base_destino != null)
            {
                $this->db_origem = $base_origem;
                $this->db_destino = $base_destino;
            }
            else
            {
                print PHP_EOL . "Erro ao criar objeto Observacao" . PHP_EOL;
            }

        }

        public function getObservacaoOrigem()
        {
            $array = [];
            $query_select = $this->db_origem->query("SELECT * FROM vendas AS v WHERE v.id = '" . $this->id . "'");

            if ($query_select != "")
            {
                while ($data = $query_select->fetch(PDO::FETCH_OBJ))
                {
                    array_push($array, [
                        'usuario_id' => $data->usuario_id,
                        'observacao' => $data->observacao
                    ]);
                }
            }

            $this->observacao_origem = $array;

        }

        public function getDados()
        {
            $query_select = $this->db_origem->query("SELECT * FROM observacoes AS o WHERE o.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            return $query_select;
        }

        public function limparDestino()
        {
            try {
                $query_delete = $this->db_destino->prepare("DELETE FROM observacaos WHERE venda_id = :venda_id");
                $query_delete->bindValue(':venda_id', $this->venda_id);
                $query_delete->execute();
            }
            catch (PDOException $e)
            {
                print PHP_EOL . "ERRO EM OBSERVACAO: " . $e->getMessage() . PHP_EOL;
            }

        }

        public function atualizar()
        {
            $query_select = $this->getDados();

            while ($data = $query_select->fetch(PDO::FETCH_OBJ)) {

                if (is_null($data->visita_id))
                {
                    $plugin = false;
                }
                else
                {
                    $plugin = true;
                }

                $query_insert = $this->db_destino->prepare("INSERT INTO `observacaos`(`venda_id`, `visita_id`, `usuario_id`, `observacao`, `plugin`) VALUES(:venda_id, :visita_id, :usuario_id, :observacao, :plugin)");
                $query_insert->bindValue(':venda_id', $data->venda_id);
                $query_insert->bindValue(':visita_id', $data->visita_id);
                $query_insert->bindValue(':usuario_id', $data->usuario_id);
                $query_insert->bindValue(':observacao', $data->observacao);
                $query_insert->bindValue(':plugin', $plugin);
                $query_insert->execute();
            }
        }
    }

?>