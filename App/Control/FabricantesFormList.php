<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;

use Livro\Database\Transaction;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;
use Livro\Traits\SaveTrait;

use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

/*
 * classe FabricantesFormList
 * Cadastro de Fabricantes
 * Contém o formuláro e a listagem
 */
class FabricantesFormList extends Page
{
    private $form;      // formulário de cadastro
    private $datagrid;  // listagem
    private $loaded;
    private $connection;
    private $activeRecord;
    
    use DeleteTrait;
    use ReloadTrait {
        onReload as onReloadTrait;
    }
    use SaveTrait {
        onSave as onSaveTrait;
    }
    
    
    /*
     * método construtor
     * Cria a página, o formulário e a listagem
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->activeRecord = 'Fabricante';
        $this->connection   = 'livro';
        
        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_fabricantes'));
        $this->form->setTitle('Fabricantes');
        
        // cria os campos do formulário
        $codigo = new Entry('id');
        $nome   = new Entry('nome');
        $site   = new Entry('site');
        $codigo->setEditable(FALSE);
        
        $this->form->addField('Código', $codigo, '30%');
        $this->form->addField('Nome',   $nome, '70%');
        $this->form->addField('Site',   $site, '70%');
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        $this->form->addAction('Limpar', new Action(array($this, 'onEdit')));
        
        // instancia objeto DataGrid
        $this->datagrid = new DatagridWrapper(new DataGrid);
        
        // instancia as colunas da DataGrid
        $codigo   = new DataGridColumn('id',       'Código',  'center',  '10%');
        $nome     = new DataGridColumn('nome',     'Nome',    'left',  '60%');
        $site     = new DataGridColumn('site',     'Site',    'left',  '30%');
        
        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($site);
        
        $this->datagrid->addAction( 'Editar',  new Action([$this, 'onEdit']),   'id', 'fa fa-edit fa-lg blue');
        $this->datagrid->addAction( 'Excluir', new Action([$this, 'onDelete']), 'id', 'fa fa-trash fa-lg red');
        
        // monta a página através de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);
        
        parent::add($box);
    }
    
    /**
     * Salva os dados
     */
    public function onSave()
    {
        $this->onSaveTrait();
        $this->onReload();
    }
    
    /**
     * Carrega os dados
     */
    public function onReload()
    {
        $this->onReloadTrait();   
        $this->loaded = true;
    }
    
    /**
     * Carrega registro para edição
     */
    public function onEdit($param)
    {
        if (isset($param['key']))
        {
            $key = $param['key']; // obtém a chave
            Transaction::open('livro'); // inicia transação com o BD
            $fabricante = Fabricante::find($key); // instancia o Active Record
            $this->form->setData($fabricante); // lança os dados no formulário
            Transaction::close(); // finaliza a transação
            $this->onReload();
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
