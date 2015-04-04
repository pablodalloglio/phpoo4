<?php
use Livro\Database\Record;

class Produto extends Record
{
    const TABLENAME = 'produto';
	
	   private $fabricante;
	   /*
	    * método get_nome_fabricante()
	    * retorna o nome do fabricante do produto
	    */
	   function get_nome_fabricante()
	   {
		      // instancia Fabricante, carrega
		      // na memória a fabricante de código $this->id_fabricante
		      if (empty($fabricante))
			         $this->fabricante = new Fabricante($this->id_fabricante);
		      // retorna o nome do fabricante
        return $this->fabricante->nome;
		
    }
	
}
