<?php
use Livro\Control\Page;
use Livro\Database\Transaction;

class ModelTest3 extends Page
{
    public function show()
    {
        try {
            Transaction::open('livro');
            
            $venda = new Venda;
            $venda->cliente     = new Pessoa(3);
            $venda->data_venda  = date('Y-m-d');
            $venda->valor_venda = 0;
            $venda->desconto    = 0;
            $venda->acrescimos  = 0;
            $venda->obs         = 'obs';
    
            $venda->addItem(new Produto(3), 2);
            $venda->addItem(new Produto(4), 1);
            
            $venda->valor_final = $venda->valor_venda + $venda->acrescimos - $venda->desconto;
            
            $venda->store();
            Transaction::close();
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}