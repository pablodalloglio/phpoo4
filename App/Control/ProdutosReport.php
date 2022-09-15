<?php
use Livro\Control\Page;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;

/**
 * Relatório de vendas
 */
class ProdutosReport extends Page
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
        
        // gerador Barcode em HTML
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        
		$renderer = new \BaconQrCode\Renderer\ImageRenderer(
			new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
			new \BaconQrCode\Renderer\Image\SvgImageBackEnd
		);
		
        $writer = new \BaconQrCode\Writer($renderer);
        
        try
        {
            // inicia transação com o banco 'livro'
            Transaction::open('livro');
            
            $produtos = Produto::all();
            foreach ($produtos as $produto)
            {
                $produto->barcode = $generator->getBarcode($produto->id, $generator::TYPE_CODE_128, 5, 100);
                $produto->qrcode  = $writer->writeString($produto->id . ' ' . $produto->descricao);
            }
            $replaces['produtos'] = $produtos;
            
            Transaction::close(); // finaliza a transação
        }
        catch (Exception $e)
        {
            new Message('error', $e->getMessage());
            Transaction::rollback();
        }
        
        $content = $twig->render('produtos_report.html', $replaces);
        
        // cria um painél para conter o formulário
        $panel = new Panel('Produtos');
        $panel->add($content);
        parent::add($panel);
    }
}
