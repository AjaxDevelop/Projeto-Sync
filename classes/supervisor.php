<?php

    class Supervisor
    {
        private $db_origem;
        private $db_destino;
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
                print PHP_EOL . "Erro ao criar objeto Supervisor" . PHP_EOL;
            }

        }

        public function getSupervisorIdDestino($vendedor_id)
        {
            $query_select = $this->db_destino->query("SELECT * FROM supervisoes AS s WHERE s.vendedor_id = '" . $vendedor_id . "'");

            if ($query_select == "")
            {
                return null;
            }

            $id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $id = $data->supervisor_id;
            }

            if ($amount ==  0)
            {
                return false;
            }

            return $id;

        }

    }

?>