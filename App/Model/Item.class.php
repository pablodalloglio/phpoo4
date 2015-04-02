<?php
/*
 * classe Item
 * Active Record para tabela Item
 */
class Item extends Record
{
	   const TABLENAME = 'item';
	   private $produto;
	   /*
	    * método get_descricao()
	    * retorna a descrição do produto
	    */
	   function get_descricao()
	   {
		      // instancia Produto, carrega
		      // na memória o produto de código $this->id_produto
		      if (empty($this->produto))
			         $this->produto = new Produto($this->id_produto);
		      // retorna a descrição do produto instanciado
		      return $this->produto->descricao;
	   }
	  /*
	   * método get_preco_venda()
	   * retorna o preço de venda do produto
	   */
	  function get_preco_venda()
	  {
		     // instancia Produto, carrega
		     // na memória o produto de código $this->id_produto
		     if (empty($this->produto))
			        $this->produto = new Produto($this->id_produto);
		     // retorna o preço de venda do produto instanciado
		     return $this->produto->preco_venda;
	  }
}
