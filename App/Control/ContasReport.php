<?php
use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Database\Filter;

use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;

/**
 * Relatório de contas
 */
class ContasReport extends Page
{
    private $form;   // formulário de entrada

    /**
     * método construtor
     */
    public function __construct()
    {
        parent::__construct();

        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_relat_contas'));

        // cria os campos do formulário
        $data_ini = new Entry('data_ini');
        $data_fim = new Entry('data_fim');
        
        $this->form->addField('Vencimento Inicial', $data_ini, 200);
        $this->form->addField('Vencimento Final', $data_fim, 200);
        $this->form->addAction('Gerar', new Action(array($this, 'onGera')));
        
        $panel = new Panel('Relatório de contas');
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
        $template = $twig->loadTemplate('contas_report.html');
        
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

            // instancia um repositório da classe Conta
            $repositorio = new Repository('Conta');

            // cria um critério de seleção por intervalo de datas
            $criterio = new Criteria;
            $criterio->setProperty('order', 'dt_vencimento');
            
            if ($dados->data_ini)
                $criterio->add(new Filter('dt_vencimento', '>=', $data_ini));
            if ($dados->data_fim)
                $criterio->add(new Filter('dt_vencimento', '<=', $data_fim));
            
            // lê todas contas que satisfazem ao critério
            $contas = $repositorio->load($criterio);
            
            if ($contas)
            {
                foreach ($contas as $conta)
                {
                    $conta_array = $conta->toArray();
                    $conta_array['nome_cliente'] = $conta->cliente->nome;
                    $replaces['contas'][] = $conta_array;
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
