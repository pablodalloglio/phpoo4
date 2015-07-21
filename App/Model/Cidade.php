<?php
use Livro\Database\Record;

class Cidade extends Record
{
    const TABLENAME = 'cidade';
	
	public function get_estado()
	{
	    return new Estado($this->id_estado);
	}
	
	public function get_nome_estado()
	{
	    return (new Estado($this->id_estado))->nome;
	}
}
