<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Button;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Bootstrap\Wrapper\DatagridWrapper;
use Bootstrap\Wrapper\FormWrapper;
use Bootstrap\Widgets\Panel;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;

/**
 * Cadastro de Produtos
 */
class ProdutosForm extends Page
{
    private $form; // formulário
    
    use SaveTrait;
    use EditTrait;
    
    /**
     * Construtor da página
     */
    function __construct()
    {
        parent::__construct();

        $this->activeRecord = 'Produto';
        $this->connection = 'livro';
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_produtos'));
        
        // cria os campos do formulário
        $codigo      = new Entry('id');
        $descricao   = new Entry('descricao');
        $estoque     = new Entry('estoque');
        $preco_custo = new Entry('preco_custo');
        $preco_venda = new Entry('preco_venda');
        $fabricante  = new Combo('id_fabricante');
        
        // carrega os fabricantes do banco de dados
        Transaction::open('livro');
        $repository = new Repository('Fabricante');
        $collection = $repository->load(new Criteria);
        foreach ($collection as $object)
        {
            $items[$object->id] = $object->nome;
        }
        $fabricante->addItems($items);
        Transaction::close();
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        
        $this->form->addField('Código',    $codigo, 100);
        $this->form->addField('Descrição', $descricao, 200);
        $this->form->addField('Estoque',   $estoque, 200);
        $this->form->addField('Preço custo',   $preco_custo, 200);
        $this->form->addField('Preço venda',   $preco_venda, 200);
        $this->form->addField('Fabricante',   $fabricante, 200);
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        $panel = new Panel('Produtos');
        $panel->add($this->form);
        
        // adiciona o formulário na página
        parent::add($panel);
    }
}
