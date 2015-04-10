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
use Livro\Validation\RequiredValidator;

use Bootstrap\Wrapper\DatagridWrapper;
use Bootstrap\Wrapper\FormWrapper;

/*
 * classe CidadesList
 * cadastro de cidades: contém o formuláro e a listagem
 */
class CidadesList extends Page
{
    private $form;        // formulário de cadastro
    private $datagrid;   // listagem
    private $loaded;

    /*
     * método construtor
     * Cria a página, o formulário e a listagem
     */
    public function __construct()
    {
        parent::__construct();

        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_cidades'));
        
        // cria os campos do formulário
        $codigo    = new Entry('id');
        $descricao = new Entry('nome');
        $estado    = new Combo('estado');
        
        $codigo->setEditable(FALSE);
        
        // cria um vetor com as opções da combo
        $items= array();
        $items['RS'] = 'Rio Grande do Sul';
        $items['SP'] = 'São Paulo';
        $items['MG'] = 'Minas Gerais';
        $items['PR'] = 'Paraná';
        $estado->addItems($items);
        
        $this->form->addField('Código', $codigo, 40, new RequiredValidator);
        $this->form->addField('Descrição', $descricao, 200, new RequiredValidator);
        $this->form->addField('Estado', $estado, 200);
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        // instancia objeto DataGrid
        $this->datagrid = new DatagridWrapper(new DataGrid);

        // instancia as colunas da DataGrid
        $codigo   = new DataGridColumn('id',     'Código', 'right', 50);
        $nome     = new DataGridColumn('nome',   'Nome',   'left', 200);
        $estado   = new DataGridColumn('estado', 'Estado', 'left', 40);

        // adiciona as colunas à DataGrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($estado);

        // instancia duas ações da DataGrid
        $action1 = new DataGridAction(array($this, 'onEdit'));
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
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);
        
        parent::add($box);
    }

    /*
     * método onReload()
     * carrega a DataGrid com os objetos do banco de dados
     */
    function onReload()
    {
        // inicia transação com o banco 'livro'
        Transaction::open('livro');

        // instancia um repositório para Cidade
        $repository = new Repository('Cidade');

        // cria um critério de seleção, ordenado pelo id
        $criteria = new Criteria;
        $criteria->setProperty('order', 'id');

        // carrega os objetos de acordo com o criterio
        $cidades = $repository->load($criteria);
        $this->datagrid->clear();
        if ($cidades)
        {
            // percorre os objetos retornados
            foreach ($cidades as $cidade)
            {
                // adiciona o objeto na DataGrid
                $this->datagrid->addItem($cidade);
            }
        }
        // finaliza a transação
        Transaction::close();
        $this->loaded = true;
    }


    /*
     * método onSave()
     * executada quando o usuário clicar no botão salvar do formulário
     */
    function onSave()
    {
        try
        {
            $this->form->validate();
            
            // inicia transação com o banco 'livro'
            Transaction::open('livro');
            // obtém os dados no formulário em um objeto Cidade
            $cidade = $this->form->getData('Cidade');
            
            // armazena o objeto
            $cidade->store();
            // finaliza a transação
            Transaction::close();
            // exibe mensagem de sucesso
            new Message('info', 'Dados armazenados com sucesso');
            // recarrega listagem
            $this->onReload();
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
        }
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
        // define os parâmetros de cada ação
        $action1->setParameter('key', $key);

        // exibe um diálogo ao usuário
        new Question('Deseja realmente excluir o registro?', $action1);
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
        // instancia objeto Cidade
        $cidade = new Cidade($key);
        // deleta objeto do banco de dados
        $cidade->delete();
        // finaliza a transação
        Transaction::close();
        // recarrega a datagrid
        $this->onReload();
        // exibe mensagem de sucesso
        new Message('info', "Registro excluído com sucesso");
    }

    /*
     * método onEdit()
     * executada quando o usuário clicar no botão editar da datagrid
     */
    function onEdit($param)
    {
        // obtém o parâmetro $key
        $key=$param['key'];
        // inicia transação com o banco 'livro'
        Transaction::open('livro');
        // instancia objeto Cidade
        $cidade = new Cidade($key);
        // lança os dados da cidade no formulário
        $this->form->setData($cidade);
        // finaliza a transação
        Transaction::close();
        $this->onReload();
    }

    /*
     * método show()
     * exibe a página
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
