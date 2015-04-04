<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

/*
 * classe ClientesList
 * listagem de Clientes
 */
class ClientesList extends Page
{
    private $form;     // formulário de buscas
    private $datagrid; // listagem
    private $loaded;

    /*
     * método construtor
     * cria a página, o formulário de buscas e a listagem
     */
    public function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new Form('form_busca_clientes');

        // instancia uma tabela
        $table = new Table;

        // adiciona a tabela ao formulário
        $this->form->add($table);

        // cria os campos do formulário
        $nome = new Entry('nome');

        // adiciona uma linha para o campo nome
        $row=$table->addRow();
        $row->addCell(new Label('Nome:'));
        $row->addCell($nome);

        // cria dois botões de ação para o formulário
        $find_button = new Button('busca');
        $new_button = new Button('cadastra');

        // define as ações dos botões
        $find_button->setAction(new Action(array($this, 'onReload')), 'Buscar');
        $obj = new ClientesForm;
        $new_button->setAction(new Action(array($obj, 'onEdit')), 'Cadastrar');

        // adiciona uma linha para aas ações do formulário
        $row=$table->addRow();
        $row->addCell($find_button);
        $row->addCell($new_button);

        // define quais são os campos do formulário
        $this->form->setFields(array($nome, $find_button, $new_button));

        // instancia objeto DataGrid
        $this->datagrid = new DataGrid;

        // instancia as colunas da DataGrid
        $codigo   = new DataGridColumn('id',         'Código', 'right', 50);
        $nome     = new DataGridColumn('nome',       'Nome',    'left', 140);
        $endereco = new DataGridColumn('endereco',   'Endereco','left', 140);
        $cidade   = new DataGridColumn('nome_cidade','Cidade', 'left', 140);

        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($endereco);
        $this->datagrid->addColumn($cidade);

        // instancia duas ações da DataGrid
        //$obj = new ClientesForm;
        $action1 = new DataGridAction(array($obj, 'onEdit'));
        $action1->setLabel('Editar');

        $action1->setImage('ico_edit.png');
        $action1->setField('id');
        $action2 = new DataGridAction(array($this, 'onDelete'));
        $action2->setLabel('Deletar');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');

        // adiciona as ações à DataGrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);

        // cria o modelo da DataGrid, montando sua estrutura
        $this->datagrid->createModel();

        // monta a página através de uma tabela
        $table = new Table;
        $table->width='100%';

        // cria uma linha para o formulário
        $row = $table->addRow();
        $row->addCell($this->form);

        // cria uma linha para a datagrid
        $row = $table->addRow();
        $row->addCell($this->datagrid);

        // adiciona a tabela à página
        parent::add($table);
    }

    /*
     * método onReload()
     * carrega a DataGrid com os objetos do banco de dados
     */
    function onReload()
    {
        // inicia transação com o banco 'livro'
        Transaction::open('livro');

        // instancia um repositório para Cliente
        $repository = new Repository('Cliente');

        // cria um critério de seleção de dados
        $criteria = new Criteria;

        // ordena pelo campo id
        $criteria->setProperty('order', 'id');

        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();

        // verifica se o usuário preencheu o formulário
        if ($dados->nome)
        {
            // filtra pelo nome do cliente
            $criteria->add(new TFilter('nome', 'like', "%{$dados->nome}%"));
        }

        // carrega os produtos que satisfazem o critério
        $clientes = $repository->load($criteria);
        $this->datagrid->clear();
        if ($clientes)
        {
            foreach ($clientes as $cliente)
            {
                // adiciona o objeto na DataGrid
                $this->datagrid->addItem($cliente);
            }
        }

        // finaliza a transação
        Transaction::close();
        $this->loaded = true;
    }

    /*
     * método onDelete()
     * executada quando o usuário clicar no botão excluir da datagrid
     * pergunta ao usuário se deseja realmente excluir um registro
     */
    function onDelete($param)
    {
        // obtém o parâmetro $key
        $key=$param['key'];

        // define duas ações
        $action1 = new Action(array($this, 'Delete'));
        $action2 = new Action(array($this, 'teste'));

        // define os parâmetros de cada ação
        $action1->setParameter('key', $key);
        $action2->setParameter('key', $key);

        // exibe um diálogo ao usuário
        new TQuestion('Deseja realmente excluir o registro?', $action1, $action2);
    }

    /*
     * método Delete()
     * exclui um registro
     */
    function Delete($param)
    {
        // obtém o parâmetro $key
        $key=$param['key'];

        // inicia transação com o banco 'livro'
        Transaction::open('livro');

        // instancia objeto Cliente
        $cliente = new Cliente($key);

        // deleta objeto do banco de dados
        $cliente->delete();

        // finaliza a transação
        Transaction::close();

        // recarrega a datagrid
        $this->onReload();

        // exibe mensagem de sucesso
        new Message('info', "Registro excluído com sucesso");
    }

    /*
    * método show()
    * executada quando o usuário clicar no botão excluir
    */
    function show()
    {
        // se a listagem ainda não foi carregada
        if (!$this->loaded)
        {
            $this->onReload();
        }
        parent::show();
    }
}
?>
