<?php

    class Endereco
    {
        private $db_origem;
        private $db_destino;
        public $venda_id;

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
                print PHP_EOL . "Erro ao criar objeto Endereco" . PHP_EOL;
            }

        }

        public function getPais()
        {
            $query_select = $this->db_destino->query("SELECT * FROM paises");

            if ($query_select == "")
            {
                return false;
            }

            $pais_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                if ($data->pais == 'Brasil')
                {
                    $pais_id = $data->id;
                }

            }

            if ($amount == 0)
            {
                return false;
            }

            return $pais_id;

        }

        public function estados()
        {
            $estados = [
                [
                    'estado' => 'Acre',
                    'sigla' => 'AC'
                ],
                [
                    'estado' => 'Alagoas',
                    'sigla' => 'AL'
                ],
                [
                    'estado' => 'Amapá',
                    'sigla' => 'AP'
                ],
                [
                    'estado' => 'Amazonas',
                    'sigla' => 'AM'
                ],
                [
                    'estado' => 'Bahia',
                    'sigla' => 'BA'
                ],
                [
                    'estado' => 'Ceará',
                    'sigla' => 'CE'
                ],
                [
                    'estado' => 'Distrito Federal',
                    'sigla' => 'DF'
                ],
                [
                    'estado' => 'Espírito Santo',
                    'sigla' => 'ES'
                ],
                [
                    'estado' => 'Goiás',
                    'sigla' => 'GO'
                ],
                [
                    'estado' => 'Maranhão',
                    'sigla' => 'MA'
                ],
                [
                    'estado' => 'Mato Grosso',
                    'sigla' => 'MT'
                ],
                [
                    'estado' => 'Mato Grosso do Sul',
                    'sigla' => 'MS'
                ],
                [
                    'estado' => 'Minas Gerais',
                    'sigla' => 'MG'
                ],
                [
                    'estado' => 'Pará',
                    'sigla' => 'PA'
                ],
                [
                    'estado' => 'Paraíba',
                    'sigla' => 'PB'
                ],
                [
                    'estado' => 'Paraná',
                    'sigla' => 'PR'
                ],
                [
                    'estado' => 'Pernambuco',
                    'sigla' => 'PE'
                ],
                [
                    'estado' => 'Piauí',
                    'sigla' => 'PI'
                ],
                [
                    'estado' => 'Rio de Janeiro',
                    'sigla' => 'RJ'
                ],
                [
                    'estado' => 'Rio Grande do Norte',
                    'sigla' => 'RN'
                ],
                [
                    'estado' => 'Rio Grande do Sul',
                    'sigla' => 'RS'
                ],
                [
                    'estado' => 'Rondânia',
                    'sigla' => 'RO'
                ],
                [
                    'estado' => 'Roraima',
                    'sigla' => 'RR'
                ],
                [
                    'estado' => 'Santa Catarina',
                    'sigla' => 'SC'
                ],
                [
                    'estado' => 'São Paulo',
                    'sigla' => 'SP'
                ],
                [
                    'estado' => 'Sergipe',
                    'sigla' => 'SE'
                ],
                [
                    'estado' => 'Tocantins',
                    'sigla' => 'TO'
                ],
            ];

            return $estados;
        }

        public function getEstadoDestino($sigla)
        {
            $query_select = $this->db_destino->query("SELECT * FROM estados AS e WHERE e.sigla = '" . $sigla . "'");

            if ($query_select == "")
            {
                return false;
            }

            $estado_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $estado_id = $data->id;

            }

            if ($amount == 0)
            {
                return false;
            }

            return $estado_id;

        }

        public function getCidadesOrigem()
        {
            $query_select = $this->db_origem->query("SELECT e.cidade, e.estado FROM enderecos AS e WHERE e.cidade != '' AND e.cidade != 'N/A' GROUP BY e.cidade AND e.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            return $query_select;

        }

        public function getCidadeDestino($cidade)
        {
            $query_select = $this->db_destino->query("SELECT * FROM cidades AS c WHERE c.cidade = '" . $cidade . "'");

            if ($query_select == "")
            {
                return false;
            }

            $cidade_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $cidade_id = $data->id;

            }

            if ($amount == 0)
            {
                return false;
            }

            return $cidade_id;

        }

        public function getBairrosOrigem()
        {
            $query_select = $this->db_origem->query("SELECT e.bairro, e.cidade, e.bairro_instalacao, e.cidade_instalacao FROM enderecos AS e WHERE e.bairro != '' AND e.bairro != 'N/A' GROUP BY e.bairro AND e.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            $array = [];
            $bairros = [];
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;

                if (!in_array($data->bairro, $bairros))
                {
                    array_push($array, ['bairro' => $data->bairro, 'cidade' => $data->cidade]);
                    array_push($bairros, $data->bairro);
                }

                if (!in_array($data->bairro_instalacao, $bairros))
                {
                    array_push($array, ['bairro' => $data->bairro_instalacao, 'cidade' => $data->cidade_instalacao]);
                    array_push($bairros, $data->bairro_instalacao);
                }
            }

            if ($amount == 0)
            {
                return false;
            }

            return $array;

        }

        public function getBairroDestino($bairro)
        {
            $query_select = $this->db_destino->query('SELECT * FROM bairros AS b WHERE b.bairro = "' . $bairro . '"');

            if ($query_select == "")
            {
                return false;
            }

            $bairro_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $bairro_id = $data->id;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $bairro_id;

        }

        public function getCepOrigem()
        {
            $query_select = $this->db_origem->query("SELECT e.cep, e.cep_instalacao FROM enderecos AS e WHERE e.cep != '' AND e.cep != 'N/A' AND e.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            $ceps = [];
            $array = [];
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;

                if (!in_array($data->cep, $array))
                {
                    array_push($ceps, ['cep' => $data->cep]);
                    array_push($array, $data->cep);
                }

                if (!in_array($data->cep_instalacao, $array))
                {
                    array_push($ceps, ['cep' => $data->cep_instalacao]);
                    array_push($array, $data->cep_instalacao);
                }
            }

            if ($amount == 0)
            {
                return false;
            }

            return $ceps;

        }

        public function getCepDestino($cep)
        {
            $query_select = $this->db_destino->query("SELECT * FROM ceps AS c WHERE c.cep = '" . $cep . "'");

            if ($query_select == "")
            {
                return false;
            }

            $cep_id = null;
            $amount = 0;

            while ($data = $query_select->fetch(PDO::FETCH_OBJ))
            {
                ++$amount;
                $cep_id = $data->id;
            }

            if ($amount == 0)
            {
                return false;
            }

            return $cep_id;

        }

        public function getEnderecosOrigem()
        {
            $query_select = $this->db_origem->query("SELECT * FROM enderecos AS e WHERE e.venda_id != '' AND e.cep != '' AND e.cep != 'N/A' AND e.bairro != '' AND e.bairro != 'N/A' AND e.venda_id = '" . $this->venda_id . "'");

            if ($query_select == "")
            {
                return false;
            }

            return $query_select;

        }

        private function insertEnderecoDestino($array)
        {
            $query_cobranca = $this->db_destino->prepare("INSERT INTO `enderecos`(`venda_id`, `bairro_id`, `cep_id`, `logradouro`, `numero`, `complemento`, `referencia`, `tipo`) VALUES(:venda_id, :bairro_id, :cep_id, :logradouro, :numero, :complemento, :referencia, :tipo)");
            $query_cobranca->bindValue(':venda_id', $array['venda_id']);
            $query_cobranca->bindValue(':bairro_id', $array['bairro_id']);
            $query_cobranca->bindValue(':cep_id', $array['cep_id']);
            $query_cobranca->bindValue(':logradouro', $array['logradouro']);
            $query_cobranca->bindValue(':numero', $array['numero']);
            $query_cobranca->bindValue(':complemento', $array['complemento']);
            $query_cobranca->bindValue(':referencia', $array['referencia']);
            $query_cobranca->bindValue(':tipo', $array['tipo']);
            $query_cobranca->execute();
        }

        public function limparDestino()
        {
            try {
                $query_delete = $this->db_destino->prepare("DELETE FROM enderecos WHERE venda_id = :venda_id");
                $query_delete->bindValue(':venda_id', $this->venda_id);
                $query_delete->execute();
            }
            catch (PDOException $e)
            {
                print PHP_EOL . "ERRO EM ENDERECO: " . $e->getMessage() . PHP_EOL;
            }

        }

        public function atualizar()
        {
            # Atualizar Cidades
            $query_cidades = $this->getCidadesOrigem();

            while ($data = $query_cidades->fetch(PDO::FETCH_OBJ))
            {
                if ($this->getCidadeDestino($data->cidade) == false)
                {
                    $estado_id = $this->getEstadoDestino($data->estado);

                    $query_insert_cidade = $this->db_destino->prepare("INSERT INTO `cidades`(`estado_id`, `cidade`) VALUES(:estado_id, :cidade)");
                    $query_insert_cidade->bindValue(':estado_id', $estado_id);
                    $query_insert_cidade->bindValue(':cidade', $data->cidade);
                    $query_insert_cidade->execute();
                }
            }

            //Atualizar Bairros
            $array_bairros = $this->getBairrosOrigem();

            foreach ($array_bairros as $key => $value)
            {
                if ($this->getBairroDestino($value['bairro']) == false)
                {
                    $cidade_id = $this->getCidadeDestino($value['cidade']);

                    $query_insert_bairro = $this->db_destino->prepare("INSERT INTO `bairros`(`cidade_id`, `bairro`) VALUES(:cidade_id, :bairro)");
                    $query_insert_bairro->bindValue(':cidade_id', $cidade_id);
                    $query_insert_bairro->bindValue(':bairro', $value['bairro']);
                    $query_insert_bairro->execute();
                }
            }

            # Atualizar CEPs
            $array_ceps = $this->getCepOrigem();

            foreach ($array_ceps as $key => $value)
            {
                if ($this->getBairroDestino($value['cep']) == false)
                {
                    $query_insert_bairro = $this->db_destino->prepare("INSERT INTO `ceps`(`cep`) VALUES(:cep)");
                    $query_insert_bairro->bindValue(':cep', $value['cep']);
                    $query_insert_bairro->execute();
                }
            }

            # Atualizar enderecos
            $query_enderecos = $this->getEnderecosOrigem();

            while ($data = $query_enderecos->fetch(PDO::FETCH_OBJ))
            {
                #Migrar endereço de cobrança.
                $bairro_id = $this->getBairroDestino($data->bairro);
                $cep_id = $this->getCepDestino($data->cep);
                $array_cobranca = [
                    'venda_id' => $data->venda_id,
                    'bairro_id' => $bairro_id,
                    'cep_id' => $cep_id,
                    'logradouro' => $data->endereco,
                    'numero' => $data->numero,
                    'complemento' => $data->complemento,
                    'referencia' => $data->referencia,
                    'tipo' => 'cobrança'
                ];

                $this->insertEnderecoDestino($array_cobranca);

                #Migrar endereço de instalação.
                if (is_string($data->bairro_instalacao) && $data->bairro_instalacao != 'N/A' && is_string($data->cep_instalacao) && $data->cep_instalacao != 'N/A')
                {
                    $bairro_id = $this->getBairroDestino($data->bairro);
                    $cep_id = $this->getCepDestino($data->cep);
                    $array_instalacao = [
                        'venda_id' => $data->venda_id,
                        'bairro_id' => $bairro_id,
                        'cep_id' => $cep_id,
                        'logradouro' => $data->endereco_instalacao,
                        'numero' => $data->numero_instalacao,
                        'complemento' => $data->complemento_instalacao,
                        'referencia' => $data->referencia_instalacao,
                        'tipo' => 'instalação'
                    ];

                    $this->insertEnderecoDestino($array_instalacao);

                }

            }

        }

    }

?>
