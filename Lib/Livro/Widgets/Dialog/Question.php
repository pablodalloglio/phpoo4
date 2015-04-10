<?php
Namespace Livro\Widgets\Dialog;

use Livro\Control\Action;
use Livro\Widgets\Base\Element;
use Livro\Widgets\Container\Table;
use Livro\Widgets\Form\Image;

/**
 * Exibe perguntas ao usuário
 * @author Pablo Dall'Oglio
 */
class Question
{
    /**
     * Instancia questionamento
     * @param $message = pergunta ao usuário
     * @param $action_yes = ação para resposta positiva
     * @param $action_no = ação para resposta negativa
     */
    function __construct($message, Action $action_yes, Action $action_no = NULL)
    {
        // converte os nomes de métodos em URL's
        $url_yes = $action_yes->serialize();
        if ($action_no)
        {
            $url_no = $action_no->serialize();
        }
        
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
        $table = new Table;
        $table->align = 'center';
        $table->cellspacing = 10;
        
        // cria uma linha para o ícone e a mensagem
        $row=$table->addRow();
        $row->addCell(new Image('App/Images/question.png'));
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
