<?php
use Livro\Database\Record;

class ItemVenda extends Record
{
    const TABLENAME = 'item_venda';
    private $produto;
    
    /**
     * Retorna a descrição do produto
     */
    function get_descricao()
    {
        // Carrega o objeto produto 
        if (empty($this->produto))
            $this->produto = new Produto($this->id_produto);
        
        // retorna a descrição
        return $this->produto->descricao;
    }
    
    /**
     * Retorna o preço de venda do produto
     */
    function get_preco_venda()
    {
        // Carrega o objeto produto
        if (empty($this->produto))
            $this->produto = new Produto($this->id_produto);
        
        // retorna o preço de venda
        return $this->produto->preco_venda;
    }
}
