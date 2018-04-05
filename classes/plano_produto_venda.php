<?php

    class Plano_Produto_Venda
    {
        private $db_origem;
        private $db_destino;
        public $venda_id;
        public $produto_origem = [];

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
                //print PHP_EOL . "Erro ao criar objeto Plano_Produto_Venda" . PHP_EOL;
            }

        }

        public function getDadosOrigem()
        {
            $query_select = $this->db_origem->query("SELECT ov.*, o.produto FROM oiprodutos_vendas AS ov INNER JOIN oiprodutos AS o ON (o.id = ov.oiprodutos_id) WHERE ov.vendas_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            return $query_select;
        }

        public function getPlanoOrigem($id)
        {
            $query_select = $this->db_origem->query("SELECT v.modalidade_pagamento, v.plano, v.categoria_plano, v.tipo_plano FROM vendas AS v WHERE id = '" . $id . "'");

            if ($query_select == "")
            {
                return false;
            }

            $array = [
                'plano' => '',
                'categoria_plano' => '',
                'tipo_plano' => ''
            ];

            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $array['plano'] = $data->plano;
                $array['categoria_plano'] = $data->categoria_plano;
                $array['tipo_plano'] = $data->tipo_plano;
                $array['modalidade_pagamento'] = $data->modalidade_pagamento;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $array;

        }

        public function getPlanoIdDestino($plano)
        {
            $planos = [
                'BÁSICO' => 'Básico',
                'INTERMEDIÁRIO' => 'Intermediário',
                'AVANÇADO' => 'Avançado',
                'TOP' => 'Top',
                'Oi Tv BÁSICO' => 'Oi TV Start HD',
                'Oi Tv INTERMEDIÁRIO' => 'Oi TV Mix HD',
                'Oi Tv AVANÇADO' => 'Oi TV Total Telecine HD',
                'Oi Tv TOP' => 'Oi TV Total Cinema HD DVR',
                'ILIMITADO SEM DDD' => 'Voz Total Ilimitado sem DDD',
                'ILIMITADO COM DDD' => 'Voz Total Ilimitado com DDD',
                '600Kbps' => 'Velox 600 Kb',
                '600 Kbps' => 'Velox 600 Kb',
                '1MB' => 'Velox 1 Mega',
                '2MB' => 'Velox 2 Mega',
                '5MB' => 'Velox 5 Mega',
                '10MB' => 'Velox 10 Mega',
                '15MB' => 'Velox 15 Mega',
                '25MB' => 'Velox 25 Mega',
                '35MB' => 'Velox 35 Mega',
                '10GB' => 'IPC 10 GB',
                '10GB OIM' => 'OIM 10 GB',
                'CONTROLE CARTÃO' => 'Cartão',
                'CONTROLE BOLETO' => 'Boleto Digital'
            ];

            $query_select = $this->db_destino->query("SELECT * FROM planos AS p WHERE p.plano = '" . $planos[$plano] . "'");

            if ($query_select == "")
            {
                return false;
            }

            $plano_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $plano_id = $data->id;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $plano_id;

        }

        public function getProdutoIdDestino($produto)
        {
            $produtos = [
                'CONTROLE MAIS' => 'Oi Mais',
                'PÓS PAGO' => 'Oi Pós Pago',
                'OI FIXO' => 'Oi Fixo',
                'OI INTERNET MÓVEL' => 'Oi Internet Móvel',
                'BANDA LARGA VELOX' => 'Oi Banda Larga',
                'OI INTERNET PRA CELULAR 3G/4G' => 'Oi Internet para Celular',
                'OI TV' => 'Oi TV',
                'OI CHIP' => 'Oi Chip',
                'CONTROLE' => 'Oi Controle'
            ];

            $query_select = $this->db_destino->query("SELECT * FROM produtos AS p WHERE p.produto = '" . $produtos[$produto] . "'");

            if ($query_select == "")
            {
                return false;
            }

            $produto_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $produto_id = $data->id;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $produto_id;

        }

        public function getPlanoProdutoIdDestino($plano_id, $produto_id)
        {
            $query_select = $this->db_destino->query("SELECT * FROM planos_produtos AS p WHERE p.plano_id = '" . $plano_id . "' AND p.produto_id = '" . $produto_id . "'");

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

        public function getNivelLinhaOrigem($venda_id)
        {
            $query_select = $this->db_origem->query("SELECT * FROM linhas AS l WHERE p.venda_id = '" . $venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            $nivel = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $nivel = $data->nivel;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $nivel;
        }

        public function insertPlanoProdutoVendasDestino($array)
        {
            foreach ($array as $key => $value)
            {
                $query_inser = $this->db_destino->prepare("INSERT INTO planos_produtos_vendas(campanha_id, planos_produto_id, venda_id, novo, os, migrado) VALUES(:campanha_id, :planos_produto_id, :venda_id, :novo, :os, :migrado)");
                $query_inser->bindValue('campanha_id', $value['campanha_id']);
                $query_inser->bindValue('planos_produto_id', $value['planos_produto_id']);
                $query_inser->bindValue('venda_id', $value['venda_id']);
                $query_inser->bindValue('novo', $value['novo']);
                $query_inser->bindValue('os', $value['os']);
                $query_inser->bindValue('migrado', $value['migrado']);
                $query_inser->execute();
            }

        }

        public function limparDestino()
        {
            try {
                $query_delete = $this->db_destino->prepare("DELETE FROM planos_produtos_vendas WHERE venda_id = :venda_id");
                $query_delete->bindValue(':venda_id', $this->venda_id);
                $query_delete->execute();
            }
            catch (PDOException $e)
            {
                //print PHP_EOL . "ERRO EM PLANO PRODUTO VENDA: " . $e->getMessage() . PHP_EOL;
            }

        }

        public function atualizar()
        {
            $query_vendas = $this->getDadosOrigem();

            while ($data = $query_vendas->fetch(PDO::FETCH_OBJ))
            {
                $plano_id = null;
                $produto_id = null;
                $array_insert = [];

                $produto_id = $this->getProdutoIdDestino($data->produto);

                //print PHP_EOL . "Produto: " . $data->produto . " | " . $data->vendas_id. PHP_EOL;


                switch ($data->produto)
                {
                    case 'PÓS PAGO':

                        $find_planos = $this->getPlanoOrigem($data->vendas_id); //print_r($find_planos);
                        $plano_id = $this->getPlanoIdDestino($find_planos['categoria_plano']);

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => $produto_id,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;

                    case 'OI FIXO':
                        $find_planos = $this->getPlanoOrigem($data->vendas_id);

                        if ($find_planos['categoria_plano'] == 'OI FIXO')
                        {
                            $nivel_linha = $this->getNivelLinhaOrigem($data->vendas_id);
                            $plano_id = $this->getPlanoIdDestino($nivel_linha);
                        }
                        else
                        {
                            $plano_id = $this->getPlanoIdDestino($find_planos['categoria_plano']);
                        }

                        //print_r($find_planos);

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => $produto_id,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;

                    case 'OI INTERNET MÓVEL':
                        $plano_id = $this->getPlanoIdDestino('10GB OIM');

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => $produto_id,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;

                    case 'BANDA LARGA VELOX':

                        if ($data->novo == true && $data->velocidade_contratada != '0' && $data->velocidade_contratada != '' && ($data->velocidade_atual == '' || $data->velocidade_atual == 'Não Possui' || $data->velocidade_atual == 'Não Possuí'))
                        {

                            //print PHP_EOL . "--->>> True " . $data->vendas_id . " | " . $data->velocidade_atual . " | " . $data->velocidade_contratada . PHP_EOL;

                            $plano_id = $this->getPlanoIdDestino($data->velocidade_contratada);

                            $array_data = [
                                'plano_id' => $plano_id,
                                'produto_id' => $produto_id,
                                'campanha_id' => $data->campanha_id,
                                'venda_id' => $data->vendas_id,
                                'novo' => $data->novo,
                                'os' => $data->os,
                                'migrado' => false
                            ];

                            array_push($array_insert, $array_data);
                        }
                        else if ($data->novo == true &&  $data->velocidade_contratada != '0' && $data->velocidade_contratada != '' && $data->velocidade_atual != '0' && $data->velocidade_atual != '')
                        {

                            //print PHP_EOL . "--->>> True 2 " . $data->vendas_id . " | " . $data->velocidade_atual . " | " . $data->velocidade_contratada . PHP_EOL;

                            $plano_id = $this->getPlanoIdDestino($data->velocidade_contratada);

                            $array_data = [
                                'plano_id' => $plano_id,
                                'produto_id' => $produto_id,
                                'campanha_id' => $data->campanha_id,
                                'venda_id' => $data->vendas_id,
                                'novo' => $data->novo,
                                'os' => $data->os,
                                'migrado' => false
                            ];

                            array_push($array_insert, $array_data);

                            $plano_id = $this->getPlanoIdDestino($data->velocidade_atual);

                            $array_data = [
                                'plano_id' => $plano_id,
                                'produto_id' => $produto_id,
                                'campanha_id' => $data->campanha_id,
                                'venda_id' => $data->vendas_id,
                                'novo' => $data->novo,
                                'os' => $data->os,
                                'migrado' => true
                            ];

                            array_push($array_insert, $array_data);
                        }
                        else if ($data->novo == false && $data->velocidade_atual != '0' && $data->velocidade_atual != '' && $data->velocidade_atual != 'Não Possui' && $data->velocidade_atual != 'Não Possuí')
                        {

                            //print PHP_EOL . "--->>> false " . $data->vendas_id . " | " . $data->velocidade_atual . " | " . $data->velocidade_contratada . PHP_EOL;

                            $plano_id = $this->getPlanoIdDestino($data->velocidade_atual);

                            $array_data = [
                                'plano_id' => $plano_id,
                                'produto_id' => $produto_id,
                                'campanha_id' => $data->campanha_id,
                                'venda_id' => $data->vendas_id,
                                'novo' => $data->novo,
                                'os' => $data->os,
                                'migrado' => false
                            ];

                            array_push($array_insert, $array_data);
                        }

                        break;

                    case 'OI INTERNET PRA CELULAR 3G/4G':
                        $plano_id = $this->getPlanoIdDestino('10GB');

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => $produto_id,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;

                    case 'OI TV':
                        $find_planos = $this->getPlanoOrigem($data->vendas_id); //print_r($find_planos);
                        $plano_id = $this->getPlanoIdDestino('Oi Tv ' . $find_planos['categoria_plano']);

                        if ($plano_id == null)
                        {
                            //print PHP_EOL . " ------------" . $find_planos['categoria_plano'] . "--------" . PHP_EOL;
                        }

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => $produto_id,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;

                    case 'OI CHIP':
                        $find_planos = $this->getPlanoOrigem($data->vendas_id);
                        $plano_id = $this->getPlanoIdDestino($find_planos['categoria_plano']);

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => 8,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;

                    case 'CONTROLE':
                        $find_planos = $this->getPlanoOrigem($data->vendas_id);
                        $plano_id = $this->getPlanoIdDestino('CONTROLE ' . $find_planos['modalidade_pagamento']);

                        $array_data = [
                            'plano_id' => $plano_id,
                            'produto_id' => $produto_id,
                            'campanha_id' => $data->campanha_id,
                            'venda_id' => $data->vendas_id,
                            'novo' => $data->novo,
                            'os' => $data->os,
                            'migrado' => false
                        ];

                        array_push($array_insert, $array_data);

                        break;
                }

                //print PHP_EOL . "Produto_id: " . $produto_id . " | Plano_id: " . $plano_id . PHP_EOL;

                foreach ($array_insert as $key => $value)
                {
                    $plano_produto_id = $this->getPlanoProdutoIdDestino($value['plano_id'], $value['produto_id']);

                    $array_insert[$key]['planos_produto_id'] = $plano_produto_id;
                }

                $this->insertPlanoProdutoVendasDestino($array_insert);

            }

        }

    }

?>