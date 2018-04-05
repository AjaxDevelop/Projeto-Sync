<?php

    include_once 'supervisor.php';
    include_once 'pacote.php';
    include_once 'status.php';
    include_once 'cliente_venda.php';

    class Venda
    {
        private $db_origem;
        private $db_destino;
        private $supervisor;
        private $status;
        private $pacote;
        private $cliente_venda;
        public $id;
        public $venda_origem = [];
        public $produto_origem = [];
        public $observacao_origem = [];

        public function __construct($base_origem = null, $base_destino = null, $id = null)
        {
            if ($base_origem != null && $base_destino != null && $id != null)
            {
                $this->id = $id;
                $this->db_origem = $base_origem;
                $this->db_destino = $base_destino;
                $this->supervisor = new Supervisor($base_origem, $base_destino, $id);
                $this->pacote = new Pacote($base_origem, $base_destino, $id);
                $this->status = new Status($base_origem, $base_destino, $id);
                $this->cliente_venda = new Cliente_Venda($base_origem, $base_destino, $id);
            }
            else
            {
                print PHP_EOL . "Erro ao criar objeto Venda" . PHP_EOL;
            }

        }

        public function eVendaOrigem()
        {
            $amount  = 0;
            $query_select = $this->db_origem->query("SELECT * FROM vendas AS v WHERE v.id = '" . $this->id . "'");

            while ($row = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
            }

            if ($amount > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        public function getVendaOrigem()
        {
            $pacotes = $this->pacote->getPacotesDestino();

            $array = [];
            $query_select = $this->db_origem->query("SELECT * FROM vendas AS v WHERE v.id = '" . $this->id . "'");

            if ($query_select != "")
            {
                while ($data = $query_select->fetch(PDO::FETCH_OBJ))
                {
                    # Dados do Cliente
                    $clientes_vendas = $this->cliente_venda->getClientesVendasOrigem();
                    $pessoa_id = $clientes_vendas[1]['pessoa_id'];

                    if ($clientes_vendas[0]['cliente_id'] != $clientes_vendas[1]['cliente_id'])
                    {
                        $tirulariedade = true;
                    }
                    else
                    {
                        $tirulariedade = false;
                    }

                    # Status
                    $status = $this->status->getStatusDestino($data->status);
                    $status_auditoria = $this->status->getStatusDestino($data->auditoria_status);

                    # Supervisor
                    $supervisor_id = $this->supervisor->getSupervisorIdDestino($data->vendedor_id);

                    $array = [
                        'pdv_id' => $data->pdv_id,
                        'supervisor_id' => $supervisor_id,
                        'vendedor_id' => $data->vendedor_id,
                        'cliente_id' => $data->cliente_id,
                        'pacote_id' => $pacotes[$data->tipo_plano],
                        'status_id' => $status,
                        'status_auditoria_id' => $status_auditoria,
                        'data_venda' => $data->data_venda,
                        'data_agendamento' => $data->data_agendamento,
                        'periodo_agendamento' => $data->periodo_agendamento,
                        'hora_agendamento' => $data->hora_agendamento,
                        'data_ativacao' => $data->data_ativacao,
                        'data_finalizacao' => $data->data_finalizacao,
                        'ddd' => $data->ddd,
                        'fixo' => $data->fixo,
                        'fixo_base' => $data->fixo_base,
                        'titularidade' => $tirulariedade,
                        'fidelizacao' => true,
                        'valor' => $data->valor_plano,
                        'created' => $data->created,
                        'modified' => $data->modified,
                    ];

                }

            }

            $this->venda_origem = $array;

        }

        public function limparDestino()
        {
            try {
                $query_delete = $this->db_destino->prepare("DELETE FROM vendas WHERE id = :id");
                $query_delete->bindValue(':id', $this->id);
                $query_delete->execute();
            }
            catch (PDOException $e)
            {
                print PHP_EOL . "ERRO EM VENDA: " . $e->getMessage() . PHP_EOL;
            }

        }

        public function atualizar()
        {
            $this->getVendaOrigem();

            $query_insert = $this->db_destino->prepare("INSERT INTO `vendas`(`id`, `pdv_id`, `supervisor_id`, `vendedor_id`, `cliente_id`, `pacote_id`, `status_id`, `status_auditoria_id`, `data_venda`, `data_agendamento`, `periodo_agendamento`, `hora_agendamento`, `data_ativacao`, `data_finalizacao`, `ddd`, `fixo`, `fixo_base`, `titularidade`, `fidelizacao`, `valor`, `created`, `modified`) VALUES(:id, :pdv_id, :supervisor_id, :vendedor_id, :cliente_id, :pacote_id, :status_id, :status_auditoria_id, :data_venda, :data_agendamento, :periodo_agendamento, :data_agendamento, :data_ativacao, :data_finalizacao, :ddd, :fixo, :fixo_base, :titularidade, :fidelizacao, :valor, :created, :modified)");
            $query_insert->bindValue(':id', $this->id);
            $query_insert->bindValue(':pdv_id', $this->venda_origem['pdv_id']);
            $query_insert->bindValue(':supervisor_id', $this->venda_origem['supervisor_id']);
            $query_insert->bindValue(':vendedor_id', $this->venda_origem['vendedor_id']);
            $query_insert->bindValue(':cliente_id', $this->venda_origem['cliente_id']);
            $query_insert->bindValue(':pacote_id', $this->venda_origem['pacote_id']);
            $query_insert->bindValue(':status_id', $this->venda_origem['status_id']);
            $query_insert->bindValue(':status_auditoria_id', $this->venda_origem['status_auditoria_id']);
            $query_insert->bindValue(':data_venda', $this->venda_origem['data_venda']);
            $query_insert->bindValue(':data_agendamento', $this->venda_origem['data_agendamento']);
            $query_insert->bindValue(':periodo_agendamento', $this->venda_origem['periodo_agendamento']);
            $query_insert->bindValue(':hora_agendamento', $this->venda_origem['hora_agendamento']);
            $query_insert->bindValue(':data_ativacao', $this->venda_origem['data_ativacao']);
            $query_insert->bindValue(':data_finalizacao', $this->venda_origem['data_finalizacao']);
            $query_insert->bindValue(':ddd', $this->venda_origem['ddd']);
            $query_insert->bindValue(':fixo', $this->venda_origem['fixo']);
            $query_insert->bindValue(':fixo_base', $this->venda_origem['fixo_base']);
            $query_insert->bindValue(':titularidade', $this->venda_origem['titularidade']);
            $query_insert->bindValue(':fidelizacao', $this->venda_origem['fidelizacao']);
            $query_insert->bindValue(':valor', $this->venda_origem['valor']);
            $query_insert->bindValue(':created', $this->venda_origem['created']);
            $query_insert->bindValue(':modified', $this->venda_origem['modified']);
            $query_insert->execute();

        }
    }

?>