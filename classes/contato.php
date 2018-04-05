<?php

    class Contato
    {
        private $db_origem;
        private $db_destino;

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
                print PHP_EOL . "Erro ao criar objeto Contato" . PHP_EOL;
            }

        }

        public function getDados()
        {
            $query_select = $this->db_origem->query("SELECT * FROM `contatos` AS c WHERE c.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            return $query_select;
        }

        public function getTipos()
        {
            $query_select = $this->db_destino->query("SELECT * FROM `contatostipos`");

            if ($query_select == "")
            {
                return false;
            }

            $tipos = [];
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $tipos[$data->tipo] = $data->id;
            }

            if ($amount ==  0)
            {
                return false;
            }

            return $tipos;
        }

        public function limparDestino()
        {
            try {
                $query_delete = $this->db_destino->prepare("DELETE FROM contatos WHERE venda_id = :venda_id");
                $query_delete->bindValue(':venda_id', $this->venda_id);
                $query_delete->execute();
            }
            catch (PDOException $e)
            {
                print PHP_EOL . "ERRO EM CONTATO: " . $e->getMessage() . PHP_EOL;
            }

        }

        public function atualizar()
        {
            $query_select = $this->getDados();
            $tipos = $this->getTipos();

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                $query_insert = $this->db_destino->prepare("INSERT INTO `contatos`(`venda_id`, `contatostipo_id`, `nome`, `contato`) VALUES(:venda_id, :contatostipo_id, :nome, :contato)");
                $query_insert->bindValue(':venda_id', $data->venda_id);
                $query_insert->bindValue(':contatostipo_id', $tipos[$data->tipo]);
                $query_insert->bindValue(':nome', $data->nome);
                $query_insert->bindValue(':contato', $data->contato);
                $query_insert->execute();
            }
        }
    }

?>
