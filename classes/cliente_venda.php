<?php

    class Cliente_Venda
    {
        private $db_origem;
        private $db_destino;
        private $row;
        public $cliente_id;
        public $venda_id;
        public $venda;

        public function __construct($base_origem = null, $base_destino = null, $venda_id = null)
        {
            if ($base_origem != null && $base_destino != null && $venda_id != null)
            {
                $this->venda_id = $venda_id;
                $this->db_origem = $base_origem;
                $this->db_destino = $base_destino;
            }
            else
            {
                print PHP_EOL . "Erro ao criar objeto Cliente" . PHP_EOL;
            }

        }

        public function getClientesVendasOrigem()
        {
            $query_select = $this->db_origem->query("SELECT * FROM clientes_vendas AS cliente_venda INNER JOIN clientes AS cliente ON (cliente_venda.cliente_id = cliente.id) WHERE cliente_venda.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            $clientes_vendas = [];
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                array_push($clientes_vendas, ['venda_id' => $data->venda_id, 'cliente_id' => $data->cliente_id, 'pessoa_id' => $data->pessoa_id]);
            }

            if ($amount == 0)
            {
                return false;
            }

            return $clientes_vendas;

        }

        public function getClienteIdOrigem()
        {
            $query_select = $this->db_destino->query("SELECT * FROM vendas AS v WHERE v.id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            $cliente_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $cliente_id = $data->cliente_id;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $cliente_id;

        }

        public function limparDestino()
        {
            try {
                $query_delete = $this->db_destino->prepare("DELETE FROM clientes_vendas WHERE venda_id = :venda_id");
                $query_delete->bindValue(':venda_id', $this->venda_id);
                $query_delete->execute();
            }
            catch (PDOException $e)
            {
                print PHP_EOL . "ERRO EM CLIENTE VENDA: " . $e->getMessage() . PHP_EOL;
            }

        }

        public function atualizar()
        {
            # Id do Cliente
            $cliente_id = $this->getClienteIdOrigem();

            # Atualizar Clientes Vendas
            $query_insert_cliente = $this->db_destino->prepare("INSERT INTO `clientes_vendas`(`cliente_id`, `venda_id`) VALUES(:cliente_id, :venda_id)");
            $query_insert_cliente->bindValue(':cliente_id', $cliente_id);
            $query_insert_cliente->bindValue(':venda_id', $this->venda_id);
            $query_insert_cliente->execute();
        }


    }

?>