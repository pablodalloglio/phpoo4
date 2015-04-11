<?php
use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

class Venda extends Record
{
	   const TABLENAME = 'venda';
	   private $itens;	   // array de objetos do tipo Item
	   /*
	    * função addItem()
	    * adiciona um item (produto) à venda
	    */
	   public function addItem(Item $item)
	   {
		      $this->itens[] = $item;
	   }
	   /*
	    * função store()
	    * armazena uma venda e seus itens no banco de dados
	    */
	   public function store()
	   {
		      // armazena a venda
  		    parent::store();
		   // percorre os itens da venda
		   foreach ($this->itens as $item)
		   {
			      $item->id_venda    = $this->id;
			      // armazena o item
			      $item->store();
		   }
	  }

	 /*
	  * função get_itens()
	  * retorna os itens da venda
	  */
	 public function get_itens()
	 {
		    // instancia um repositório de Item
		    $repositorio = new Repository('Item');
		    // define o critério de seleção
		    $criterio = new Criteria;
		    $criterio->add(new Filter('id_venda', '=', $this->id));
		    // carrega a coleção de itens
		    $this->itens = $repositorio->load($criterio);
		    // retorna os itens
		    return $this->itens;
	 }
	 /*
	  * método get_cliente()
	  * retorna o objeto cliente vinculado à venda
	  */
	 function get_cliente()
	 {
		    // instancia Cliente, carrega
		    // na memória o cliente de código $this->id_cliente
		    $cliente = new Cliente($this->id_cliente);
		    // retorna o objeto instanciado
		    return $cliente;
	 }


}
