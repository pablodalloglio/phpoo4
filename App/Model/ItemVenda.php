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
    public function get_descricao()
    {
        return $this->get_produto()->descricao;
    }
}
