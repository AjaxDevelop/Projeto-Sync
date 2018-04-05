<?php

    class Status
    {
        private $db_origem;
        private $db_destino;
        private $row;
        public $venda_id;

        public function __construct($base_origem = null, $base_destino = null, $venda_id = null)
        {
            if ($base_origem != null && $base_destino != null && $venda_id != null)
            {
                $this->id = $venda_id;
                $this->db_origem = $base_origem;
                $this->db_destino = $base_destino;
            }
            else
            {
                print PHP_EOL . "Erro ao criar objeto Status" . PHP_EOL;
            }

        }

        public function getStatusDestino($status)
        {
            $query_select = $this->db_destino->query("SELECT * FROM status AS s WHERE s.status = '" . $status . "';");

            if ($query_select == "")
            {
                return false;
            }

            $id = null;

            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $id = $data->id;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $id;

        }

    }

?>