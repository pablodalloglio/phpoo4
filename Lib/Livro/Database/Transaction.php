<?php
namespace Livro\Database;

use Livro\Log\Logger;

/**
 * Fornece os métodos necessários manipular transações
 * @author Pablo Dall'Oglio
 */
final class Transaction
{
    private static $conn;   // conexão ativa
    private static $logger; // objeto de LOG
    
    /**
     * Private para impedir que se crie instâncias de TTransaction
     */
    private function __construct() {}
    
    /**
     * Abre uma transação e uma conexão ao BD
     * @param $database = nome do banco de dados
     */
    public static function open($database)
    {
        // abre uma conexão e armazena na propriedade estática $conn
        if (empty(self::$conn))
        {
            self::$conn = Connection::open($database);
            // inicia a transação
            self::$conn->beginTransaction();
            // desliga o log de SQL
            self::$logger = NULL;
        }
    }
    
    /**
     * Retorna a conexão ativa da transação
     */
    public static function get()
    {
        // retorna a conexão ativa
        return self::$conn;
    }
    
    /**
     * Desfaz todas operações realizadas na transação
     */
    public static function rollback()
    {
        if (self::$conn)
        {
            // desfaz as operações realizadas durante a transação
            self::$conn->rollback();
            self::$conn = NULL;
        }
    }
    
    /**
     * Aplica todas operações realizadas e fecha a transação
     */
    public static function close()
    {
        if (self::$conn)
        {
            // aplica as operações realizadas durante a transação
            self::$conn->commit();
            self::$conn = NULL;
        }
    }
    
    /**
     * Define qual estratégia (algoritmo de LOG será usado)
     */
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }
    
    /**
     * Armazena uma mensagem no arquivo de LOG conforme o logger atual
     */
    public static function log($message)
    {
        // verifica existe um logger
        if (self::$logger)
        {
            self::$logger->write($message);
        }
    }
}
