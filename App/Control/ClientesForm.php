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

        // instancia uma tabela
        $table = new Table;

        // adiciona a tabela ao formulário
        $this->form->add($table);

        // cria os campos do formulário
        $codigo    = new Entry('id');
        $nome      = new Entry('nome');
        $endereco  = new Entry('endereco');
        $telefone  = new Entry('telefone');
        $cidade    = new Combo('id_cidade');

        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $codigo->setSize(100);
        $nome->setSize(300);
        $endereco->setSize(300);

        // carrega as cidades do banco de dados
        Transaction::open('livro');

        // instancia um repositório de Cidade
        $repository = new Repository('Cidade');

        // carrega todos os objetos
        $collection = $repository->load(new Criteria);

        // adiciona objetos na combo
        foreach ($collection as $object)
        {
            $items[$object->id] = $object->nome;
        }
        $cidade->addItems($items);

        Transaction::close();

        // adiciona uma linha para o campo código
        $row=$table->addRow();
        $row->addCell(new Label('Código:'));
        $row->addCell($codigo);

        // adiciona uma linha para o campo nome
        $row=$table->addRow();
        $row->addCell(new Label('Nome:'));
        $row->addCell($nome);

        // adiciona uma linha para o campo endereço
        $row=$table->addRow();
        $row->addCell(new Label('Endereco:'));
        $row->addCell($endereco);

        // adiciona uma linha para o campo telefone
        $row=$table->addRow();
        $row->addCell(new Label('Telefone:'));
        $row->addCell($telefone);

        // adiciona uma linha para o campo cidade
        $row=$table->addRow();
        $row->addCell(new Label('Cidade:'));
        $row->addCell($cidade);

        // cria um botão de ação para o formulário
        $button1=new Button('action1');

        // define a ação do botão
        $button1->setAction(new Action(array($this, 'onSave')), 'Salvar');

        // adiciona uma linha para a ação do formulário
        $row=$table->addRow();
        $row->addCell('');
        $row->addCell($button1);

        // define quais são os campos do formulário
        $this->form->setFields(array($codigo, $nome, $endereco, $telefone, $cidade, $button1));

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
