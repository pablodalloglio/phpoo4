<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Datagrid\DatagridAction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Button;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Session\Session;

/*
 * função formata_string
 * exibe um valor com as casas decimais
 */
function formata_money($valor)
{
    return number_format($valor, 2, ',', '.');
	
}
/*
 * classe VendasForm
 * formulário de vendas
 */
class VendasForm extends Page
{
    private $form;       // formulário de novo item
    private $datagrid;   // listagem de itens
    private $loaded;

    /*
     * método construtor
     * cria a página e o formulário de cadastro
     */
    public function __construct()
    {
        parent::__construct();

        // instancia nova seção
        new Session;

        // instancia um formulário
        $this->form = new Form('form_vendas');

        // cria os campos do formulário
        $codigo      = new Entry('id_produto');
        $quantidade = new Entry('quantidade');
        
        $this->form->addField('Código', $codigo, 100);
        $this->form->addField('Quantidade', $quantidade, 200);
        $this->form->addAction('Adicionar', new Action(array($this, 'onAdiciona')));
        $this->form->addAction('Terminar', new Action(array($this, 'onFinal')));
        
        // instancia objeto DataGrid
        $this->datagrid = new DataGrid;

        // instancia as colunas da DataGrid
        $codigo    = new DataGridColumn('id_produto', 'Código', 'right', 50);
        $descricao = new DataGridColumn('descricao',   'Descrição','left', 200);
        $quantidade= new DataGridColumn('quantidade',  'Qtde',      'right', 40);
        $preco     = new DataGridColumn('preco_venda', 'Preço',    'right', 70);

        // define um transformador para a coluna preço
        $preco->setTransformer('formata_money');

        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($preco);

        // cria uma ação para a datagrid
        $action = new DataGridAction(array($this, 'onDelete'));
        $action->setLabel('Deletar');
        $action->setImage('ico_delete.png');
        $action->setField('id_produto');

        // adiciona a ação à DataGrid
        $this->datagrid->addAction($action);

        // cria o modelo da DataGrid, montando sua estrutura
        $this->datagrid->createModel();

        // monta a página através de uma tabela
        $table = new Table;

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
     * método onAdiciona()
     * executada quando o usuário clicar no botão salvar do formulário
     */
    function onAdiciona()
    {
        // obtém os dados do formulário
        $item = $this->form->getData('Item');

        // lê variável $list da seção
        $list = Session::getValue('list');

        // acrescenta produto na variável $list
        $list[$item->id_produto]= $item;

        // grava variável $list de volta à seção
        Session::setValue('list', $list);

        // recarrega a listagem
        $this->onReload();
    }

    /*
     * método onDelete()
     * executada quando o usuário clicar no botão excluir da datagrid
     */
    function onDelete($param)
    {
        // lê variável $list da seção
        $list = Session::getValue('list');

        // exclui a posição que armazena o produto de código $key
        unset($list[$param['key']]);

        // grava variável $list de volta à seção
        Session::setValue('list', $list);

        // recarrega a listagem
        $this->onReload();
    }

    /*
     * método onReload()
     * carrega a DataGrid com os objetos
     */
    function onReload()
    {
        // obtém a variável de seção $list
        $list = Session::getValue('list');

        // limpa a datagrid
        $this->datagrid->clear();
        if ($list)
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');
            // percorre o array $list
            foreach ($list as $item)
            {
                // adiciona cada objeto $item na datagrid
                $this->datagrid->addItem($item);
            }
            // fecha transação
            Transaction::close();
        }
        $this->loaded = true;
    }

    /*
     * método onFinal()
     * executada quando o usuário finalizar a Venda
     */
    function onFinal()
    {
        // instancia uma nova janela
        $janela = new TWindow('Concui Venda');
        $janela->setPosition(520,200);
        $janela->setSize(250,180);

        // lê a variável $list da seção
        $list = Session::getValue('list');

        // inicia transação com o banco 'livro'
        Transaction::open('livro');

        $total = 0;
        foreach ($list as $item)
        {
            // soma o total de produtos vendidos
            $total += $item->preco_venda * $item->quantidade;
        }

        // fecha a transação
        Transaction::close();

        // instancia formulário de conclusão de venda
        $form = new ConcluiVendaForm;

        // define a ação do botão deste formulário
        $form->button->setAction(new Action(array($this, 'onGravaVenda')), 'Salvar');

        // preenche o formulário com o valor_total
        $dados = new StdClass;
        $dados->valor_total = $total;
        $form->setData($dados);

        // adiciona o formulário à janela
        $janela->add($form);
        $janela->show();
    }

    /*
     * método onGravaVenda()
     * executada quando o usuário Finalizar a venda
     */
    function onGravaVenda()
    {
        date_default_timezone_set('America/Sao_Paulo');
        // obtém os dados do formulário de conclusão de venda
        $form = new ConcluiVendaForm;
        $dados = $form->getData();

        // inicia transação com o banco 'livro'
        Transaction::open('livro');

        // instancia novo objeto Venda
        $venda = new Venda;

        // define os atributos a serem gravados
        $venda->id_cliente  = $dados->id_cliente;
        $venda->data_venda  = date('Y-m-d');
        $venda->desconto    = $dados->desconto;
        $venda->valor_total = $dados->valor_total;
        $venda->valor_pago  = $dados->valor_pago;

        // lê a variável $list da seção
        $itens = Session::getValue('list');
        if ($itens)
        {
            // percorre os itens
            foreach ($itens as $item)
            {
                // adiciona o item na venda
                $venda->addItem($item);
            }
        }
        // armazena venda no banco de dados
        $venda->store();

        // finaliza a transação
        Transaction::close();

        // limpa lista de itens da seção
        Session::setValue('list', array());

        // exibe mensagem de sucesso
        new Message('info', 'Venda registrada com sucesso');

        // recarrega lista de itens
        $this->onReload();
    }

    /*
     * função show()
     * executada quando o usuário clicar no botão excluir
     */
    function show()
    {
        if (!$this->loaded)
        {
            $this->onReload();
        }
        parent::show();
    }
}
