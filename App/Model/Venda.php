<?php
use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

class Venda extends Record
{
    const TABLENAME = 'venda';
    private $itens;
    private $cliente;
    
    /**
     * Atribui o cliente
     */
    public function set_cliente(Pessoa $c)
    {
        $this->cliente = $c;
        $this->id_cliente = $c->id;
    }
    
    /**
     * retorna o objeto cliente vinculado à venda
     */
    public function get_cliente()
    {
        if (empty($this->cliente))
        {
            $this->cliente = new Pessoa($this->id_cliente);
        }
        
        // Retorna o objeto instanciado
        return $this->cliente;
    }
    
    /**
     * Adiciona um item (produto) à venda
     */
    public function addItem(Produto $p, $quantidade)
    {    
        $item = new ItemVenda;
        $item->produto    = $p;
        $item->preco      = $p->preco_venda;
        $item->quantidade = $quantidade;
        
        $this->itens[] = $item;
        $this->valor_venda += ($item->preco * $quantidade);
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
        $repositorio = new Repository('ItemVenda');
        // define o critério
        $criterio = new Criteria;
        $criterio->add(new Filter('id_venda', '=', $this->id));
        // carrega a coleção
        $this->itens = $repositorio->load($criterio);
        // retorna os itens
        return $this->itens;
    }
}
