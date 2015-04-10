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

use Bootstrap\Wrapper\DatagridWrapper;

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
        $this->form = new Form('form_cidades');

        // instancia uma tabela
        $table = new Table;

        // adiciona a tabela ao formulário
        $this->form->add($table);

        // cria os campos do formulário
        $codigo    = new Entry('id');
        $descricao = new Entry('nome');
        $estado    = new Combo('estado');

        // cria um vetor com as opções da combo
        $items= array();
        $items['RS'] = 'Rio Grande do Sul';
        $items['SP'] = 'São Paulo';
        $items['MG'] = 'Minas Gerais';
        $items['PR'] = 'Paraná';

        // adiciona as opções na combo
        $estado->addItems($items);

        // define os tamanhos dos campos
        $codigo->setSize(40);
        $estado->setSize(200);

        // adiciona uma linha para o campo código
        $row=$table->addRow();
        $row->addCell(new Label('Código:'));
        $row->addCell($codigo);

        // adiciona uma linha para o campo descrição
        $row=$table->addRow();
        $row->addCell(new Label('Descrição:'));
        $row->addCell($descricao);

        // adiciona uma linha para o campo estado
        $row=$table->addRow();
        $row->addCell(new Label('Estado:'));
        $row->addCell($estado);

        // cria um botão de ação (salvar)
        $save_button=new Button('save');

        // define a ação do botão
        $save_button->setAction(new Action(array($this, 'onSave')), 'Salvar');

        // adiciona uma linha para a ação do formulário
        $row=$table->addRow();
        $row->addCell($save_button);

        // define quais são os campos do formulário
        $this->form->setFields(array($codigo, $descricao, $estado, $save_button));

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
