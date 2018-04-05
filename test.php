<?php

    include_once 'config.php';
    include_once ROOT_PATH . '/classes/connect.php';

    class Test
    {
        private $db;
        private $table = 'vendas';
        private $id = 9000;

        public function __construct()
        {

            $db_host_origen = DB_HOST_ORIGEN_TEST;
            $db_base_origen = DB_BASE_ORIGEN_TEST;
            $db_user_origen = DB_USER_ORIGEN_TEST;
            $db_pass_origen = DB_PASS_ORIGEN_TEST;

            $this->db = Connect::conexao($db_host_origen, $db_base_origen, $db_user_origen, $db_pass_origen);

        }

        public function ler()
        {
            $sth = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
            $sth->bindValue(':id', $this->id, PDO::PARAM_INT);
            $sth->execute();

            while ($row = $sth->fetch(PDO::FETCH_OBJ))
            {
                print_r($row);
            }

        }

    }

    $test_obj = new Test();
    $test_obj->ler();

?>