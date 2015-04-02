<?php
Namespace Livro\Widgets\Dialog;

/**
 * classe TMessage
 * exibe mensagens ao usuário
 */
class Message
{
    /**
     * método construtor
     * instancia objeto TMessage
     * @param $type      = tipo de mensagem (info, error)
     * @param $message = mensagem ao usuário
     */
    public function __construct($type, $message)
    {
        $style = new TStyle('tmessage');
        $style->position      = 'absolute';
        $style->left          = '30%';
        $style->top           = '30%';
        $style->width         = '300';
        $style->height        = '150';
        $style->color         = 'black';
        $style->background    = '#DDDDDD';
        $style->border        = '4px solid #000000';
        $style->z_index       = '10000000000000000';
        
        // exibe o estilo na tela
        $style->show();
        
        // instancia o painel para exibir o diálogo
        $painel = new Element('div');
        $painel->class = "tmessage";
        $painel->id     = "tmessage";
        
        // cria um botão que vai fechar o diálogo
        $button = new Element('input');
        $button->type = 'button';
        $button->value = 'Fechar';
        $button->onclick="document.getElementById('tmessage').style.display='none'";
        
        // cria um tabela para organizar o layout
        $table = new TTable;
        $table->align = 'center';
        
        // cria uma linha para o ícone e a mensagem
        $row=$table->addRow();
        $row->addCell(new TImage("app.images/{$type}.png"));
        $row->addCell($message);
        
        // cria uma linha para o botão
        $row=$table->addRow();
        $row->addCell('');
        $row->addCell($button);
        
        // adiciona a tabela ao painél
        $painel->add($table);
        
        // exibe o painél
        $painel->show();
    }
}
