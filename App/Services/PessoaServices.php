<?php
use Livro\Database\Transaction;

class PessoaServices
{
    public static function getData($request)
    {
        $id_pessoa = $request['id'];
        
        $pessoa_array = array();
        Transaction::open('livro'); // inicia transação
        $pessoa = Pessoa::find($id_pessoa);
        if ($pessoa) {
            $pessoa_array = $pessoa->toArray();
        }
        else {
            throw new Exception("Pessoa {$id_pessoa} não encontrado");
        }
        Transaction::close(); // fecha transação
        return $pessoa_array;
    }
}