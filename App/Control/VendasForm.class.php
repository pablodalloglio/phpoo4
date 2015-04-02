<?php
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
class VendasForm extends TPage
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
        new TSession;

        // instancia um formulário
        $this->form = new TForm('form_vendas');

        // instancia uma tabela
        $table = new TTable;

        // adiciona a tabela ao formulário
        $this->form->add($table);

        // cria os campos do formulário
        $codigo      = new TEntry('id_produto');
        $quantidade = new TEntry('quantidade');

        // define os tamanhos
        $codigo->setSize(100);

        // adiciona uma linha para o campo código
        $row=$table->addRow();
        $row->addCell(new TLabel('Código:'));
        $row->addCell($codigo);

        // adiciona uma linha para o campo quantidade
        $row=$table->addRow();
        $row->addCell(new TLabel('Quantidade:'));
        $row->addCell($quantidade);

        // cria dois botões de ação para o formulário
        $save_button = new TButton('save');
        $fim_button = new TButton('fim');

        // define as ações dos botões
        $save_button->setAction(new TAction(array($this, 'onAdiciona')), 'Adicionar');
        $fim_button->setAction(new TAction(array($this, 'onFinal')), 'Finalizar');

        // adiciona uma linha para as ações do formulário
        $row=$table->addRow();
        $row->addCell($save_button);
        $row->addCell($fim_button);

        // define quais são os campos do formulário
        $this->form->setFields(array($codigo, $quantidade, $save_button, $fim_button));

        // instancia objeto DataGrid
        $this->datagrid = new TDataGrid;

        // instancia as colunas da DataGrid
        $codigo    = new TDataGridColumn('id_produto', 'Código', 'right', 50);

        $descricao = new TDataGridColumn('descricao',   'Descrição','left', 200);
        $quantidade= new TDataGridColumn('quantidade',  'Qtde',      'right', 40);
        $preco     = new TDataGridColumn('preco_venda', 'Preço',    'right', 70);

        // define um transformador para a coluna preço
        $preco->setTransformer('formata_money');

        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($preco);

        // cria uma ação para a datagrid
        $action = new TDataGridAction(array($this, 'onDelete'));
        $action->setLabel('Deletar');
        $action->setImage('ico_delete.png');
        $action->setField('id_produto');

        // adiciona a ação à DataGrid
        $this->datagrid->addAction($action);

        // cria o modelo da DataGrid, montando sua estrutura
        $this->datagrid->createModel();

        // monta a página através de uma tabela
        $table = new TTable;

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
        $list = TSession::getValue('list');

        // acrescenta produto na variável $list
        $list[$item->id_produto]= $item;

        // grava variável $list de volta à seção
        TSession::setValue('list', $list);

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
        $list = TSession::getValue('list');

        // exclui a posição que armazena o produto de código $key
        unset($list[$param['key']]);

        // grava variável $list de volta à seção
        TSession::setValue('list', $list);

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
        $list = TSession::getValue('list');

        // limpa a datagrid
        $this->datagrid->clear();
        if ($list)
        {
            // inicia transação com o banco 'pg_livro'
            TTransaction::open('pg_livro');
            // percorre o array $list
            foreach ($list as $item)
            {
                // adiciona cada objeto $item na datagrid
                $this->datagrid->addItem($item);
            }
            // fecha transação
            TTransaction::close();
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
        $list = TSession::getValue('list');

        // inicia transação com o banco 'pg_livro'
        TTransaction::open('pg_livro');

        $total = 0;
        foreach ($list as $item)
        {
            // soma o total de produtos vendidos
            $total += $item->preco_venda * $item->quantidade;
        }

        // fecha a transação
        TTransaction::close();

        // instancia formulário de conclusão de venda
        $form = new ConcluiVendaForm;

        // define a ação do botão deste formulário
        $form->button->setAction(new TAction(array($this, 'onGravaVenda')), 'Salvar');

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

        // inicia transação com o banco 'pg_livro'
        TTransaction::open('pg_livro');

        // instancia novo objeto Venda
        $venda = new Venda;

        // define os atributos a serem gravados
        $venda->id_cliente  = $dados->id_cliente;
        $venda->data_venda  = date('Y-m-d');
        $venda->desconto    = $dados->desconto;
        $venda->valor_total = $dados->valor_total;
        $venda->valor_pago  = $dados->valor_pago;

        // lê a variável $list da seção
        $itens = TSession::getValue('list');
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
        TTransaction::close();

        // limpa lista de itens da seção
        TSession::setValue('list', array());

        // exibe mensagem de sucesso
        new TMessage('info', 'Venda registrada com sucesso');

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
?>
