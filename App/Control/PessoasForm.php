<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\CheckGroup;
use Livro\Database\Transaction;
use Livro\Database\Repository;

/**
 * Formulário de pessoas
 */
class PessoasForm extends Page
{
    private $form;

    /**
     * Construtor da página
     */
    function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new Form('form_pessoas');
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $nome      = new Entry('nome');
        $endereco  = new Entry('endereco');
        $bairro    = new Entry('bairro');
        $telefone  = new Entry('telefone');
        $email     = new Entry('email');
        $cidade    = new Combo('id_cidade');
        $grupo     = new CheckGroup('grupos');
        
        // carrega as cidades do banco de dados
        Transaction::open('livro');
        $cidades = Cidade::all();
        $items = array();
        foreach ($cidades as $obj_cidade)
        {
            $items[$obj_cidade->id] = $obj_cidade->nome;
        }
        $cidade->addItems($items);
        
        $grupos = Grupo::all();
        $items = array();
        foreach ($grupos as $obj_grupo)
        {
            $items[$obj_grupo->id] = $obj_grupo->nome;
        }
        $grupo->addItems($items);
        Transaction::close();
        
        $this->form->addField('Código', $codigo, 40);
        $this->form->addField('Nome', $nome, 300);
        $this->form->addField('Endereço', $endereco, 300);
        $this->form->addField('Bairro', $bairro, 200);
        $this->form->addField('Telefone', $telefone, 200);
        $this->form->addField('Email', $email, 200);
        $this->form->addField('Cidade', $cidade, 200);
        $this->form->addField('Grupo', $grupo, 200);
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $codigo->setSize(100);
        $nome->setSize(300);
        $endereco->setSize(300);
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        // adiciona o formulário na página
        parent::add($this->form);
    }

    /**
     * Carrega registro para edição
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key']; // obtém a chave
                Transaction::open('livro'); // inicia transação com o BD
                $pessoa = new Pessoa($key); // instancia o Active Record
                $pessoa->grupos = explode(',', $pessoa->grupos);
                $this->form->setData($pessoa); // lança os dados da cidade no formulário
                Transaction::close(); // finaliza a transação
            }
        }
        catch (Exception $e)		    // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
    
    /**
     * Salva os dados do formulário
     */
    function onSave()
    {
        try
        {
            // inicia transação com o BD
            Transaction::open('livro');

            $dados = $this->form->getData();
            $this->form->setData($dados);
            $dados->grupos = implode(',', $dados->grupos);
            
            $pessoa = new Pessoa; // instancia objeto
            $pessoa->fromArray( (array) $dados); // carrega os dados
            $pessoa->store(); // armazena o objeto no banco de dados
            
            
            Transaction::close(); // finaliza a transação
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch (Exception $e)
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());

            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}
