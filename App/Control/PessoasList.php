<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

/**
 * Listagem de Pessoas
 */
class PessoasList extends Page
{
    private $form;     // formulário de buscas
    private $datagrid; // listagem
    private $loaded;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();
        // instancia um formulário
        $this->form = new Form('form_busca_pessoas');

        // cria os campos do formulário
        $nome = new Entry('nome');
        
        $this->form->addField('Nome', $nome, 300);
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Novo', new Action(array(new PessoasForm, 'onEdit')));
        
        // instancia objeto DataGrid
        $this->datagrid = new DataGrid;

        // instancia as colunas da DataGrid
        $codigo   = new DataGridColumn('id',         'Código', 'right', 50);
        $nome     = new DataGridColumn('nome',       'Nome',    'left', 200);
        $endereco = new DataGridColumn('endereco',   'Endereco','left', 200);
        $cidade   = new DataGridColumn('nome_cidade','Cidade', 'left', 140);

        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($endereco);
        $this->datagrid->addColumn($cidade);

        // instancia duas ações da DataGrid
        $action1 = new DataGridAction(array(new PessoasForm, 'onEdit'));
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

        // monta a página através de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);
        
        parent::add($box);
    }

    /**
     * Carrega a Datagrid com os objetos do banco de dados
     */
    public function onReload()
    {
        Transaction::open('livro'); // inicia transação com o BD
        $repository = new Repository('Pessoa');

        // cria um critério de seleção de dados
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();

        // verifica se o usuário preencheu o formulário
        if ($dados->nome)
        {
            // filtra pelo nome do pessoa
            $criteria->add(new Filter('nome', 'like', "%{$dados->nome}%"));
        }

        // carrega os produtos que satisfazem o critério
        $pessoas = $repository->load($criteria);
        $this->datagrid->clear();
        if ($pessoas)
        {
            foreach ($pessoas as $pessoa)
            {
                // adiciona o objeto na DataGrid
                $this->datagrid->addItem($pessoa);
            }
        }

        // finaliza a transação
        Transaction::close();
        $this->loaded = true;
    }

    /**
     * Pergunta sobre a exclusão de registro
     */
    public function onDelete($param)
    {
        $key = $param['key']; // obtém o parâmetro $key
        $action1 = new Action(array($this, 'Delete'));
        $action1->setParameter('key', $key);
        
        new Question('Deseja realmente excluir o registro?', $action1);
    }

    /**
     * Exclui um registro
     */
    public function Delete($param)
    {
        try
        {
            $key = $param['key']; // obtém a chave
            Transaction::open('livro'); // inicia transação com o banco 'livro'
            $cidade = new Pessoa($key); // instancia objeto Cidade
            $cidade->delete(); // deleta objeto do banco de dados
            Transaction::close(); // finaliza a transação
            $this->onReload(); // recarrega a datagrid
            new Message('info', "Registro excluído com sucesso");
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
    }

    /**
     * Exibe a página
     */
    public function show()
    {
         // se a listagem ainda não foi carregada
         if (!$this->loaded)
         {
	        $this->onReload();
         }
         parent::show();
    }
}
