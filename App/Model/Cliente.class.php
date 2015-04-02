<?php
/*
 * classe Cliente
 * Active Record para tabela Cliente
 */
class Cliente extends Record
{
	   const TABLENAME = 'cliente';
	   private $cidade;
	   /*
	    * método get_nome_cidade()
	    * executado sempre se for acessada a propriedade "nome_cidade"
	    */
	   function get_nome_cidade()
	   {
		      // instancia Cidade, carrega
		      // na memória a cidade de código $this->id_cidade
		      if (empty($this->cidade))
			         $this->cidade = new Cidade($this->id_cidade);
		   // retorna o objeto instanciado
		   return $this->cidade->nome;
	  }
}
