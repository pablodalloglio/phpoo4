<?php
use Livro\Control\Page;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;

/**
 * Relatório de vendas
 */
class PessoasReport extends Page
{
    /**
     * método construtor
     */
    public function __construct()
    {
        parent::__construct();

        $loader = new \Twig\Loader\FilesystemLoader('App/Resources');
	$twig = new \Twig\Environment($loader);

        // vetor de parâmetros para o template
        $replaces = array();
        
        try
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');
            $replaces['pessoas'] = ViewSaldoPessoa::all();
            Transaction::close(); // finaliza a transação
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }
        
        $content = $twig->render('pessoas_report.html', $replaces);
        
        // cria um painél para conter o formulário
        $panel = new Panel('Pessoas');
        $panel->add($content);
        
        parent::add($panel);
    }
}
