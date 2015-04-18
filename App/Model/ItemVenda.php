<?php
use Livro\Database\Record;

class ItemVenda extends Record
{
    const TABLENAME = 'item_venda';
    private $produto;
    
    /**
     * Retorna o objeto produto
     */
    public function get_produto()
    {
        if (empty($this->produto)) {
            $this->produto = new Produto($this->id_produto);
        }
        return $this->produto;
        
    }
    
    /**
     * Retorna a descrição do produto
     */
    function get_descricao()
    {
        return $this->get_produto()->descricao;
    }
    
    /**
     * Retorna o preço de venda do produto
     */
    function get_preco_venda()
    {
        return $this->get_produto()->preco_venda;
    }
}
