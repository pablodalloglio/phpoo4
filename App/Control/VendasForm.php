<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Form\Entry;
use Livro\Database\Transaction;
use Livro\Session\Session;

use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

/**
 * Página de vendas
 */
class VendasForm extends Page
{
    private $form;
    private $datagrid;
    private $loaded;

    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        // instancia nova seção
        new Session;

        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_vendas'));
        $this->form->setTitle('Venda');
        
        // cria os campos do formulário
        $codigo      = new Entry('id_produto');
        $quantidade  = new Entry('quantidade');
        
        $this->form->addField('Código', $codigo, '50%');
        $this->form->addField('Quantidade', $quantidade, '50%');
        $this->form->addAction('Adicionar', new Action(array($this, 'onAdiciona')));
        $this->form->addAction('Terminar', new Action(array(new ConcluiVendaForm, 'onLoad')));
        
        // instancia objeto Datagrid
        $this->datagrid = new DatagridWrapper(new Datagrid);

        // instancia as colunas da Datagrid
        $codigo    = new DatagridColumn('id_produto', 'Código', 'center', '20%');
        $descricao = new DatagridColumn('descricao',   'Descrição','left', '40%');
        $quantidade= new DatagridColumn('quantidade',  'Qtde',      'right', '20%');
        $preco     = new DatagridColumn('preco',       'Preço',    'right', '20%');

        // define um transformador para a coluna preço
        $preco->setTransformer(array($this, 'formata_money'));

        // adiciona as colunas à Datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($quantidade);
        $this->datagrid->addColumn($preco);

        $this->datagrid->addAction( 'Excluir',  new Action([$this, 'onDelete']), 'id_produto', 'fa fa-trash fa-lg red');
        
        // monta a página através de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);
        
        parent::add($box);
    }
    
    /**
     * Adiciona item
     */
    public function onAdiciona()
    {
        try {
            // obtém os dados do formulário
            $item = $this->form->getData();
            
            Transaction::open('livro');
            $produto = Produto::find($item->id_produto);
            if ($produto)
            {
                $item->descricao = $produto->descricao;
                $item->preco     = $produto->preco_venda;
                
                $list = Session::getValue('list'); // lê variável $list da seção
                $list[$item->id_produto] = $item;  // acrescenta produto na variável $list
                Session::setValue('list', $list);  // grava variável $list de volta à seção
            }
            Transaction::close('livro');
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
        
        // recarrega a listagem
        $this->onReload();
    }

    /**
     * Exclui item
     */
    public function onDelete($param)
    {
        // lê variável $list da seção
        $list = Session::getValue('list');

        // exclui a posição que armazena o produto de código
        unset($list[$param['id_produto']]);

        // grava variável $list de volta à seção
        Session::setValue('list', $list);

        // recarrega a listagem
        $this->onReload();
    }

    /**
     * Carrega datagrid com objetos
     */
    public function onReload()
    {
        // obtém a variável de seção $list
        $list = Session::getValue('list');

        // limpa a datagrid
        $this->datagrid->clear();
        if ($list)
        {
            foreach ($list as $item)
            {
                // adiciona cada objeto $item na datagrid
                $this->datagrid->addItem($item);
            }
        }
        $this->loaded = true;
    }
    
    /**
     * Formata valor monetário
     */
    public function formata_money($valor)
    {
        return number_format($valor, 2, ',', '.');
    }
    
    /**
     * Exibe a página
     */
    public function show()
    {
        if (!$this->loaded)
        {
            $this->onReload();
        }
        parent::show();
    }
}
