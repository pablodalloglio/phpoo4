<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Date;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

/**
 * Relatório de vendas
 */
class VendasReport extends Page
{
    private $form;   // formulário de entrada

    /**
     * método construtor
     */
    public function __construct()
    {
        parent::__construct();

        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_relat_vendas'));
        $this->form->setTitle('Relatório de vendas');
        
        // cria os campos do formulário
        $data_ini = new Date('data_ini');
        $data_fim = new Date('data_fim');
        
        $this->form->addField('Data Inicial', $data_ini, '50%');
        $this->form->addField('Data Final', $data_fim, '50%');
        $this->form->addAction('Gerar', new Action(array($this, 'onGera')));
        
        parent::add($this->form);
    }

    /**
     * Gera o relatório, baseado nos parâmetros do formulário
     */
    public function onGera()
    {
        $loader = new Twig_Loader_Filesystem('App/Resources');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate('vendas_report.html');
        
        // obtém os dados do formulário
        $dados = $this->form->getData();

        // joga os dados de volta ao formulário
        $this->form->setData($dados);
        
        // lê os campos do formulário, converte para o padrão americano
        $data_ini = $dados->data_ini;
        $data_fim = $dados->data_fim;
        
        // vetor de parâmetros para o template
        $replaces = array();
        $replaces['data_ini'] = $dados->data_ini;
        $replaces['data_fim'] = $dados->data_fim;
        
        try
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');

            // instancia um repositório da classe Venda
            $repositorio = new Repository('Venda');

            // cria um critério de seleção por intervalo de datas
            $criterio = new Criteria;
            $criterio->setProperty('order', 'data_venda');
            
            if ($dados->data_ini)
                $criterio->add('data_venda', '>=', $data_ini);
            if ($dados->data_fim)
                $criterio->add('data_venda', '<=', $data_fim);
            
            // lê todas vendas que satisfazem ao critério
            $vendas = $repositorio->load($criterio);
            
            if ($vendas)
            {
                foreach ($vendas as $venda)
                {
                    $venda_array = $venda->toArray();
                    $venda_array['nome_cliente'] = $venda->cliente->nome;
                    $itens = $venda->itens;
                    if ($itens)
                    {
                        foreach ($itens as $item)
                        {
                            $item_array = $item->toArray();
                            $item_array['descricao'] = $item->produto->descricao;
                            $venda_array['itens'][] = $item_array;
                        }
                    }
                    $replaces['vendas'][] = $venda_array;
                }
            }
            // finaliza a transação
            Transaction::close();
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }
        $content = $template->render($replaces);
        
        $title = 'Vendas';
        $title.= (!empty($dados->data_ini)) ? ' de '  . $dados->data_ini : '';
        $title.= (!empty($dados->data_fim)) ? ' até ' . $dados->data_fim : '';
        
        // cria um painél para conter o formulário
        $panel = new Panel($title);
        $panel->add($content);
        
        parent::add($panel);
    }
}
