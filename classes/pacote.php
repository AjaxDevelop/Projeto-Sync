<?php

    class Pacote
    {
        private $db_origem;
        private $db_destino;
        public $venda_id;
        public $observacao_origem = [];

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
                print PHP_EOL . "Erro ao criar objeto Observacao" . PHP_EOL;
            }

        }

        public function getPacotesDestino()
        {
            $query_select = $this->db_destino->query("SELECT * FROM pacotes");

            if ($query_select == "")
            {
                return false;
            }

            $pacotes = [];
            $amount = 0;
            $encoding = mb_internal_encoding();

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;

                switch ($data->pacote)
                {
                    case 'Velox':
                        $data->pacote = 'BANDA LARGA';
                        break;

                    case 'Velox + Fixo':
                        $data->pacote = 'BANDA LARGA + FIXO';
                        break;

                    case 'TV':
                        $data->pacote = 'OI TV';
                        break;

                }

                $pacotes[mb_strtoupper($data->pacote, $encoding)] = $data->id;

            }

            if ($amount == 0)
            {
                return false;
            }

            return $pacotes;

        }

    }

?>