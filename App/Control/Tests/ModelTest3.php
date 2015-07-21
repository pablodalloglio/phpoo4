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
    
            $item1 = new ItemVenda;
            $item1->produto    = new Produto(3);
            $item1->preco      = $item1->produto->preco_venda;
            $item1->quantidade = 2;
            $venda->valor_venda += ($item1->preco * $item1->quantidade);
            
            $item2 = new ItemVenda;
            $item2->produto    = new Produto(4);
            $item2->preco      = $item2->produto->preco_venda;
            $item2->quantidade = 1;
            $venda->valor_venda += ($item2->preco * $item2->quantidade);
            
            $venda->addItem($item1);
            $venda->addItem($item2);
            
            $venda->valor_final = $venda->valor_venda + $venda->acrescimos - $venda->desconto;
            
            $venda->store();
            Transaction::close();
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}