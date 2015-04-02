<?php
Namespace Livro\Widgets\Dialog;

use Livro\Control\Action;

/**
 * classe TQuestion
 * Exibe perguntas ao usuário
 */
class Question
{
    /**
     * método construtor
     * instancia objeto TQuestion
     * @param $message = pergunta ao usuário
     * @param $action_yes = ação para resposta positiva
     * @param $action_no = ação para resposta negativa
     */
    function __construct($message, Action $action_yes, Action $action_no)
    {
        $style = new TStyle('tquestion');
        $style->position     = 'absolute';
        $style->left         = '30%';
        $style->top          = '30%';
        $style->width        = '300';
        $style->height       = '150';
        $style->border_width = '1px';
        $style->color         = 'black';
        $style->background    = '#DDDDDD';
        $style->border        = '4px solid #000000';
        $style->z_index       = '10000000000000000';
        
        // converte os nomes de métodos em URL's
        $url_yes = $action_yes->serialize();
        $url_no = $action_no->serialize();
        
        // exibe o estilo na tela
        $style->show();
        
        // instancia o painel para exibir o diálogo
        $painel = new Element('div');
        $painel->class = "tquestion";
        
        // cria um botão para a resposta positiva
        $button1 = new Element('input');
        $button1->type = 'button';
        $button1->value = 'Sim';
        $button1->onclick="javascript:location='$url_yes'";
        
        // cria um botão para a resposta negativa
        $button2 = new Element('input');
        $button2->type = 'button';
        $button2->value = 'Não';
        $button2->onclick="javascript:location='$url_no'";
        
        // cria uma tabela para organizar o layout
        $table = new TTable;
        $table->align = 'center';
        $table->cellspacing = 10;
        
        // cria uma linha para o ícone e a mensagem
        $row=$table->addRow();
        $row->addCell(new TImage('app.images/question.png'));
        $row->addCell($message);
        
        // cria uma linha para os botões
        $row=$table->addRow();
        $row->addCell($button1);
        $row->addCell($button2);
        
        // adiciona a tabela ao painél
        $painel->add($table);
        
        // exibe o painél
        $painel->show();
    }
}
