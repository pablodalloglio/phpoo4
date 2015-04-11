<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

use Bootstrap\Wrapper\DatagridWrapper;
use Bootstrap\Wrapper\FormWrapper;

/*
 * classe ProdutosList
 * Listagem de Produtos
 */
class ProdutosList extends Page
{
    private $form;      // formulário de buscas
    private $datagrid;  // listagem
    private $loaded;
    
    /*
     * método construtor
     * Cria a página, o formulário de buscas e a listagem
     */
    public function __construct()
    {
        parent::__construct();
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_busca_produtos'));
        
        // cria os campos do formulário
        $descricao= new Entry('descricao');
        
        $this->form->addField('Descrição',   $descricao, 200);
        $this->form->addAction('Buscar', new Action(array($this, 'onReload')));
        $this->form->addAction('Cadastrar', new Action(array(new ProdutosForm, 'onEdit')));
        
        // instancia objeto DataGrid
        $this->datagrid = new DatagridWrapper(new DataGrid);
        
        // instancia as colunas da DataGrid
        $codigo   = new DataGridColumn('id',             'Código',    'right',  50);
        $descricao= new DataGridColumn('descricao',      'Descrição', 'left',   270);
        $fabrica  = new DataGridColumn('nome_fabricante','Fabricante','left',   80);
        $estoque  = new DataGridColumn('estoque',        'Estoq.',    'right',  40);
        $preco    = new DataGridColumn('preco_venda',    'Venda',     'right',  40);
        
        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($fabrica);
        $this->datagrid->addColumn($estoque);
        $this->datagrid->addColumn($preco);
        
        // instancia duas ações da DataGrid
        $obj = new ProdutosForm;
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
     * Carrega a DataGrid com os objetos do banco de dados
     */
    function onReload()
    {
        // inicia transação com o banco 'livro'
        Transaction::open('livro');
        
        // instancia um repositório para Produto
        $repository = new Repository('Produto');
        
        // cria um critério de seleção de dados
        $criteria = new Criteria;
        // ordena pelo campo id
        $criteria->setProperty('order', 'id');
        
        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();
        // verifica se o usuário preencheu o formulário
        if ($dados->descricao)
        {
            // filtra pela descrição do produto
            $criteria->add(new Filter('descricao', 'like', "%{$dados->descricao}%"));
        }
        
        // carreta os produtos que satisfazem o critério
        $produtos = $repository->load($criteria);
        $this->datagrid->clear();
        if ($produtos)
        {
            foreach ($produtos as $produto)
            {
                // adiciona o objeto na DataGrid
                $this->datagrid->addItem($produto);
            }
        }
        // finaliza a transação
        Transaction::close();
        $this->loaded = true;
    }
    
    /*
     * método onDelete()
     * Executada quando o usuário clicar no botão excluir da datagrid
     * Pergunta ao usuário se deseja realmente excluir um registro
     */
    function onDelete($param)
    {
        // obtém o parâmetro $key
        $key=$param['key'];
        
        // define duas ações
        $action1 = new Action(array($this, 'Delete'));
        
        // define os parâmetros de cada ação
        $action1->setParameter('key', $key);
        
        // exibe um diálogo ao usuário
        new Question('Deseja realmente excluir o registro ?', $action1);
    }
    
    /*
     * método Delete()
     * Exclui um registro
     */
    function Delete($param)
    {
        // obtém o parâmetro $key
        $key=$param['key'];
        
        // inicia transação com o banco 'livro'
        Transaction::open('livro');
        
        // instanicia objeto Produto
        $cidade = new Produto($key);
        // deleta objeto do banco de dados
        $cidade->delete();
        
        // finaliza a transação
        Transaction::close();
        
        // re-carrega a datagrid
        $this->onReload();
        // exibe mensagem de sucesso
        new Message('info', "Registro Excluído com sucesso");
    }

    /*
     * método show()
     * Executada quando o usuário clicar no botão excluir
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
