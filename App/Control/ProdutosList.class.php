<?php
/*
 * classe ProdutosList
 * Listagem de Produtos
 */
class ProdutosList extends TPage
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
        $this->form = new TForm('form_busca_produtos');
        // instancia uma tabela
        $table = new TTable;
        
        // adiciona a tabela ao formulário
        $this->form->add($table);
        
        // cria os campos do formulário
        $descricao= new TEntry('descricao');
        
        // adiciona uma linha para o campo descriçao
        $row=$table->addRow();
        $row->addCell(new TLabel('Descrição:'));
        $row->addCell($descricao);
        
        // cria dois botões de ação para o formulário
        $find_button = new TButton('busca');
        $new_button  = new TButton('cadastrar');
        // define as ações dos botões
        $find_button->setAction(new TAction(array($this, 'onReload')), 'Buscar');
        
        $obj = new ProdutosForm;
        $new_button->setAction(new TAction(array($obj, 'onEdit')), 'Cadastrar');
        
        // adiciona uma linha para as ações do formulário
        $row=$table->addRow();
        $row->addCell($find_button);
        $row->addCell($new_button);
        
        // define quais são os campos do formulário
        $this->form->setFields(array($descricao, $find_button, $new_button));
        
        // instancia objeto DataGrid
        $this->datagrid = new TDataGrid;
        
        // instancia as colunas da DataGrid
        $codigo   = new TDataGridColumn('id',             'Código',    'right',  50);
        $descricao= new TDataGridColumn('descricao',      'Descrição', 'left',   270);
        $fabrica  = new TDataGridColumn('nome_fabricante','Fabricante','left',   80);
        $estoque  = new TDataGridColumn('estoque',        'Estoq.',    'right',  40);
        $preco    = new TDataGridColumn('preco_venda',    'Venda',     'right',  40);
        
        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($fabrica);
        $this->datagrid->addColumn($estoque);
        $this->datagrid->addColumn($preco);
        
        // instancia duas ações da DataGrid
        $obj = new ProdutosForm;
        $action1 = new TDataGridAction(array($obj, 'onEdit'));
        $action1->setLabel('Editar');
        $action1->setImage('ico_edit.png');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel('Deletar');
        $action2->setImage('ico_delete.png');
        $action2->setField('id');
        
        // adiciona as ações à DataGrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // cria o modelo da DataGrid, montando sua estrutura
        $this->datagrid->createModel();
        
        // monta a página através de uma tabela
        $table = new TTable;
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
        // inicia transação com o banco 'pg_livro'
        TTransaction::open('pg_livro');
        
        // instancia um repositório para Produto
        $repository = new TRepository('Produto');
        
        // cria um critério de seleção de dados
        $criteria = new TCriteria;
        // ordena pelo campo id
        $criteria->setProperty('order', 'id');
        
        // obtém os dados do formulário de buscas
        $dados = $this->form->getData();
        // verifica se o usuário preencheu o formulário
        if ($dados->descricao)
        {
            // filtra pela descrição do produto
            $criteria->add(new TFilter('descricao', 'like', "%{$dados->descricao}%"));
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
        TTransaction::close();
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
        $action1 = new TAction(array($this, 'Delete'));
        $action2 = new TAction(array($this, 'teste'));
        
        // define os parâmetros de cada ação
        $action1->setParameter('key', $key);
        $action2->setParameter('key', $key);
        
        // exibe um diálogo ao usuário
        new TQuestion('Deseja realmente excluir o registro ?', $action1, $action2);
    }
    
    /*
     * método Delete()
     * Exclui um registro
     */
    function Delete($param)
    {
        // obtém o parâmetro $key
        $key=$param['key'];
        
        // inicia transação com o banco 'pg_livro'
        TTransaction::open('pg_livro');
        
        // instanicia objeto Produto
        $cidade = new Produto($key);
        // deleta objeto do banco de dados
        $cidade->delete();
        
        // finaliza a transação
        TTransaction::close();
        
        // re-carrega a datagrid
        $this->onReload();
        // exibe mensagem de sucesso
        new TMessage('info', "Registro Excluído com sucesso");
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
?>
