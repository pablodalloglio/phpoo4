<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\CheckGroup;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Wrapper\FormWrapper;

/**
 * Formulário de pessoas
 */
class PessoasForm extends Page
{
    private $form;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_pessoas'));
        $this->form->setTitle('Pessoa');
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $nome      = new Entry('nome');
        $endereco  = new Entry('endereco');
        $bairro    = new Entry('bairro');
        $telefone  = new Entry('telefone');
        $email     = new Entry('email');
        $cidade    = new Combo('id_cidade');
        $grupo     = new CheckGroup('ids_grupos');
        $grupo->setLayout('horizontal');
        
        // carrega as cidades do banco de dados
        Transaction::open('livro');
        $cidades = Cidade::all();
        $items = array();
        foreach ($cidades as $obj_cidade) {
            $items[$obj_cidade->id] = $obj_cidade->nome;
        }
        $cidade->addItems($items);
        
        $grupos = Grupo::all();
        $items = array();
        foreach ($grupos as $obj_grupo) {
            $items[$obj_grupo->id] = $obj_grupo->nome;
        }
        $grupo->addItems($items);
        Transaction::close();
        
        $this->form->addField('Código', $codigo, '30%');
        $this->form->addField('Nome', $nome, '70%');
        $this->form->addField('Endereço', $endereco, '70%');
        $this->form->addField('Bairro', $bairro, '70%');
        $this->form->addField('Telefone', $telefone, '70%');
        $this->form->addField('Email', $email, '70%');
        $this->form->addField('Cidade', $cidade, '70%');
        $this->form->addField('Grupo', $grupo, '70%');
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        // adiciona o formulário na página
        parent::add($this->form);
    }

    /**
     * Salva os dados do formulário
     */
    public function onSave()
    {
        try
        {
            // inicia transação com o BD
            Transaction::open('livro');

            $dados = $this->form->getData();
            $this->form->setData($dados);
            $pessoa = new Pessoa; // instancia objeto
            $pessoa->fromArray( (array) $dados); // carrega os dados
            $pessoa->store(); // armazena o objeto no banco de dados
            
            $pessoa->delGrupos();
            if ($dados->ids_grupos) {
                foreach ($dados->ids_grupos as $id_grupo)
                {
                    $pessoa->addGrupo( new Grupo($id_grupo) );
                }
            }
            
            Transaction::close(); // finaliza a transação
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch (Exception $e)
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', $e->getMessage());

            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
    
    /**
     * Carrega registro para edição
     */
    public function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                $id = $param['id']; // obtém a chave
                Transaction::open('livro'); // inicia transação com o BD
                $pessoa = Pessoa::find($id);
                if ($pessoa)
                {
                    $pessoa->ids_grupos = $pessoa->getIdsGrupos();
                    $this->form->setData($pessoa); // lança os dados da pessoa no formulário
                }
                Transaction::close(); // finaliza a transação
            }
        }
        catch (Exception $e)		    // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}
