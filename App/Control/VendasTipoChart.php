<?php
use Livro\Control\Page;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;

/**
 * Vendas por tipo
 */
class VendasTipoChart extends Page
{
    /**
     * método construtor
     */
    public function __construct()
    {
        parent::__construct();
        
        $loader = new Twig_Loader_Filesystem('App/Resources');
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate('vendas_tipo.html');
        
        try
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');
            $vendas = Venda::getVendasTipo();
            Transaction::close(); // finaliza a transação
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }
        
        // vetor de parâmetros para o template
        $replaces = array();
        $replaces['title'] = 'Vendas por tipo';
        $replaces['labels'] = json_encode(array_keys($vendas));
        $replaces['data']  = json_encode(array_values($vendas));
        
        $content = $template->render($replaces);
        
        // cria um painél para conter o formulário
        $panel = new Panel('Vendas/tipo');
        $panel->add($content);
        
        parent::add($panel);
    }
}
