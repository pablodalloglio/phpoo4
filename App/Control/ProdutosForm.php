<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Button;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Bootstrap\Wrapper\DatagridWrapper;
use Bootstrap\Wrapper\FormWrapper;

/*
 * classe ProdutosForm
 * Formulário de cadastro de Produtos
 */
class ProdutosForm extends Page
{
    private $form; // formulário
    
    /*
     * método construtor
     * Cria a página e o formulário de cadastro
     */
    function __construct()
    {
        parent::__construct();

        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_produtos'));
        
        // cria os campos do formulário
        $codigo      = new Entry('id');
        $descricao   = new Entry('descricao');
        $estoque     = new Entry('estoque');
        $preco_custo = new Entry('preco_custo');
        $preco_venda = new Entry('preco_venda');
        $fabricante  = new Combo('id_fabricante');
        
        // carrega os fabricantes do banco de dados
        Transaction::open('livro');
        $repository = new Repository('Fabricante');
        $collection = $repository->load(new Criteria);
        foreach ($collection as $object)
        {
            $items[$object->id] = $object->nome;
        }
        $fabricante->addItems($items);
        Transaction::close();
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        
        $this->form->addField('Código',    $codigo, 100);
        $this->form->addField('Descrição', $descricao, 200);
        $this->form->addField('Estoque',   $estoque, 200);
        $this->form->addField('Preço custo',   $preco_custo, 200);
        $this->form->addField('Preço venda',   $preco_venda, 200);
        $this->form->addField('Fabricante',   $fabricante, 200);
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
        
        // adiciona o formulário na página
        parent::add($this->form);
    }
        
    
    /*
     * método onEdit
     * Edita os dados de um registro
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // inicia transação com o banco 'livro'
                Transaction::open('livro');
                
                // obtém o Produto de acordo com o parâmetro
                $produto = new Produto($param['key']);
                // lança os dados do produto no formulário
                $this->form->setData($produto);
                
                // finaliza a transação
                Transaction::close();
            }
        }
        catch (Exception $e) // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
    
    /*
     * método onSave
     * Executado quando o usuário clicar no botão salvar
     */
    function onSave()
    {
        try
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');
            
            // lê os dados do formulário e instancia um objeto Produto
            $produto = $this->form->getData('Produto');
            // armazena o objeto no banco de dados
            $produto->store();
            
            // finaliza a transação
            Transaction::close();
            // exibe mensagem de sucesso
            new Message('info', 'Dados armazenados com sucesso');
        }
        catch (Exception $e) // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', '<b>Erro</b>' . $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
    }
}
?>
