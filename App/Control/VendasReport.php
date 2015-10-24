<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Label;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

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

        // cria os campos do formulário
        $data_ini = new Entry('data_ini');
        $data_fim = new Entry('data_fim');
        
        $this->form->addField('Data Inicial', $data_ini, 200);
        $this->form->addField('Data Final', $data_fim, 200);
        $this->form->addAction('Gerar', new Action(array($this, 'onGera')));
        
        $panel = new Panel('Relatório de vendas');
        $panel->add($this->form);
        
        parent::add($panel);
    }

    /**
     * Gera o relatório, baseado nos parâmetros do formulário
     */
    public function onGera()
    {
        require_once 'Lib/Twig/Autoloader.php';
        Twig_Autoloader::register();
        
        $loader = new Twig_Loader_Filesystem('App/Resources');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate('vendas_report.html');
        
        // obtém os dados do formulário
        $dados = $this->form->getData();

        // joga os dados de volta ao formulário
        $this->form->setData($dados);

        $conv_data_to_us = function($data) {
            $dia = substr($data,0,2);
            $mes = substr($data,3,2);
            $ano = substr($data,6,4);
            return "{$ano}-{$mes}-{$dia}";
        };
        
        // lê os campos do formulário, converte para o padrão americano
        $data_ini = $conv_data_to_us($dados->data_ini);
        $data_fim = $conv_data_to_us($dados->data_fim);
        
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
                $criterio->add(new Filter('data_venda', '>=', $data_ini));
            if ($dados->data_fim)
                $criterio->add(new Filter('data_venda', '<=', $data_fim));
            
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
        parent::add($content);
    }
}
