<?php
use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

class Venda extends Record
{
    const TABLENAME = 'venda';
    private $itens;
    
    /**
     * Adiciona um item (produto) à venda
     */
    public function addItem(Item $item)
    {
        $this->itens[] = $item;
    }
    
    /**
     * Armazena uma venda e seus itens no banco de dados
     */
    public function store()
    {
        // armazena a venda
        parent::store();
        // percorre os itens da venda
        foreach ($this->itens as $item)
        {
            $item->id_venda = $this->id;
            // armazena o item
            $item->store();
        }
    }
    
    /*
     * Retorna os itens da venda
     */
    public function get_itens()
    {
        // instancia um repositóio de Item
        $repositorio = new Repository('Item');
        // define o critério
        $criterio = new Criteria;
        $criterio->add(new Filter('id_venda', '=', $this->id));
        // carrega a coleção
        $this->itens = $repositorio->load($criterio);
        // retorna os itens
        return $this->itens;
    }
    
    /**
     * retorna o objeto cliente vinculado à venda
     */
    function get_cliente()
    {
        // Carrega o objeto Cliente
        $cliente = new Cliente($this->id_cliente);
        
        // Retorna o objeto instanciado
        return $cliente;
    }
}
