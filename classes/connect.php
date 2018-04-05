<?php

class Connect
{

    protected static $db;
    public $db_host;
    private $db_nome;
    private $db_usuario;
    private $db_senha;
    private $db_driver = "mysql";

    private function __construct($host, $nome, $usuario, $senha)
    {
        # Informações sobre o banco de dados.
        $this->db_host = $host;
        $this->db_nome = $nome;
        $this->db_usuario = $usuario;
        $this->db_senha = $senha;

        try
        {
            # Atribui o objeto PDO à variável $db.
            self::$db = new PDO("$this->db_driver:host=$this->db_host; dbname=$this->db_nome", $this->db_usuario, $this->db_senha);
            # Garante que o PDO lance exceções durante erros.
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            # Garante que os dados sejam armazenados com codificação UFT-8.
            self::$db->exec('SET NAMES utf8');
        }
        catch (PDOException $e)
        {
            # Não carrega nada mais da página.
            die("Connection Error: " . $e->getMessage());
        }
    }

    # Método estático - acessível sem instanciação.
    public static function conexao($host = null, $nome = null, $usuario = null, $senha = null)
    {
        # Verificação dos dados do banco de dados.
        if ($host == null || $nome == null || $usuario == null || $senha == null)
        {
            return false;
        }

        # Gera uma nova instância do banco de dados.
        new Connect($host, $nome, $usuario, $senha);

        # Retorna a conexão.
        return self::$db;
    }
}

?>