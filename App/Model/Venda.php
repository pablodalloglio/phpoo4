<?php
use Livro\Database\Transaction;
use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Criteria;

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
    
    /**
     * Retorna os itens da venda
     */
    public function get_itens()
    {
        // instancia um repositóio de Item
        $repositorio = new Repository('ItemVenda');
        // define o critério
        $criterio = new Criteria;
        $criterio->add('id_venda', '=', $this->id);
        // carrega a coleção
        $this->itens = $repositorio->load($criterio);
        // retorna os itens
        return $this->itens;
    }
    
    /**
     * Retorna vendas por mes
     */
    public static function getVendasMes()
    {
        $meses = array();
        $meses[1] = 'Janeiro';
        $meses[2] = 'Fevereiro';
        $meses[3] = 'Março';
        $meses[4] = 'Abril';
        $meses[5] = 'Maio';
        $meses[6] = 'Junho';
        $meses[7] = 'Julho';
        $meses[8] = 'Agosto';
        $meses[9] = 'Setembro';
        $meses[10] = 'Outubro';
        $meses[11] = 'Novembro';
        $meses[12] = 'Dezembro';
        
        $conn = Transaction::get();
        $result = $conn->query("select strftime('%m', data_venda) as mes, sum(valor_final) as valor from venda group by 1");
        
        $dataset = [];
        foreach ($result as $row)
        {
            $mes = $meses[ (int) $row['mes'] ];
            $dataset[ $mes ] = $row['valor'];
        }
        
        return $dataset;
    }
    
    /**
     * Retorna vendas por mes
     */
    public static function getVendasTipo()
    {
        $conn = Transaction::get();
        $result = $conn->query("  SELECT tipo.nome as tipo, sum(item_venda.quantidade*item_venda.preco) as total
                                    FROM venda, item_venda, produto, tipo
                                   WHERE venda.id = item_venda.id_venda 
                                     AND item_venda.id_produto = produto.id
                                     AND produto.id_tipo = tipo.id
                                GROUP BY 1");
        
        $dataset = [];
        foreach ($result as $row)
        {
            $dataset[ $row['tipo'] ] = $row['total'];
        }
        
        return $dataset;
    }
}
