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
use Livro\Validation\RequiredValidator;

/*
 * classe ClientesForm
 * formulário de cadastro de Clientes
 */
class ClientesForm extends Page
{
    private $form; // formulário

    /*
     * método construtor
     * cria a página e o formulário de cadastro
     */
    function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new Form('form_clientes');
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $nome      = new Entry('nome');
        $endereco  = new Entry('endereco');
        $telefone  = new Entry('telefone');
        $cidade    = new Combo('id_cidade');
        
        // carrega as cidades do banco de dados
        Transaction::open('livro');
        $repository = new Repository('Cidade');
        $collection = $repository->load(new Criteria);
        foreach ($collection as $object)
        {
            $items[$object->id] = $object->nome;
        }
        $cidade->addItems($items);
        Transaction::close();
        
        $this->form->addField('Código', $codigo, 40, new RequiredValidator);
        $this->form->addField('Nome', $nome, 40, new RequiredValidator);
        $this->form->addField('Endereço', $endereco, 40);
        $this->form->addField('Telefone', $telefone, 40);
        $this->form->addField('Cidade', $cidade, 40);
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $codigo->setSize(100);
        $nome->setSize(300);
        $endereco->setSize(300);
        
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        // adiciona o formulário na página
        parent::add($this->form);
    }


    /*
     * método onEdit
     * edita os dados de um registro
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // inicia transação com o banco 'livro'
                Transaction::open('livro');

                // obtém o Cliente de acordo com o parâmetro
                $cliente = new Cliente($param['key']);

                // lança os dados do cliente no formulário
                $this->form->setData($cliente);

                // finaliza a transação
                Transaction::close();
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

    /*
     * método onSave
     * executado quando o usuário clicar no botão salvar
     */
    function onSave()
    {
        try
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');

            // lê os dados do formulário e instancia um objeto Cliente
            $cliente = $this->form->getData('Cliente');

            // armazena o objeto no banco de dados
            $cliente->store();

            // finaliza a transação
            Transaction::close();

            // exibe mensagem de sucesso
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch (Exception $e)		     // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());

            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}
?>
