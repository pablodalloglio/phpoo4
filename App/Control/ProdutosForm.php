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
        $this->form = new Form('form_produtos');

        // instancia uma tabela
        $table = new Table;
        
        // adiciona a tabela ao formulário
        $this->form->add($table);
        
        // cria os campos do formulário
        $codigo      = new Entry('id');
        $descricao   = new Entry('descricao');
        $estoque     = new Entry('estoque');
        $preco_custo = new Entry('preco_custo');
        $preco_venda = new Entry('preco_venda');
        $fabricante  = new Combo('id_fabricante');
        
        // carrega os fabricantes do banco de dados
        Transaction::open('livro');
        // instancia um repositório de Fabricante
        $repository = new Repository('Fabricante');
        // carrega todos objetos
        $collection = $repository->load(new Criteria);
        // adiciona objetos na combo
        foreach ($collection as $object)
        {
            $items[$object->id] = $object->nome;
        }
        $fabricante->addItems($items);
        Transaction::close();
        
        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $codigo->setSize(100);
        $estoque->setSize(100);
        $preco_custo->setSize(100);
        $preco_venda->setSize(100);
        
        // adiciona uma linha para o campo código
        $row=$table->addRow();
        $row->addCell(new Label('Código:'));
        $row->addCell($codigo);
        
        // adiciona uma linha para o campo descrição
        $row=$table->addRow();
        $row->addCell(new Label('Descrição:'));
        $row->addCell($descricao);
        
        // adiciona uma linha para o campo estoque
        $row=$table->addRow();
        $row->addCell(new Label('Estoque:'));
        $row->addCell($estoque);
        
        // adiciona uma linha para o campo preco de custo
        $row=$table->addRow();
        $row->addCell(new Label('Preço Custo:'));
        $row->addCell($preco_custo);
        
        // adiciona uma linha para o campo preço de venda
        $row=$table->addRow();
        $row->addCell(new Label('Preço Venda:'));
        $row->addCell($preco_venda);
        
        // adiciona uma linha para o campo fabricante
        $row=$table->addRow();
        $row->addCell(new Label('Fabricante:'));
        $row->addCell($fabricante);
        
        // cria um botão de ação para o formulário
        $button1=new Button('action1');
        // define a ação dos botão
        $button1->setAction(new Action(array($this, 'onSave')), 'Salvar');
        
        // adiciona uma linha para a ação do formulário
        $row=$table->addRow();
        $row->addCell('');
        $row->addCell($button1);
        
        // define quais são os campos do formulário
        $this->form->setFields(array($codigo, $descricao, $estoque, $preco_custo, $preco_venda, $fabricante, $button1));
        
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
