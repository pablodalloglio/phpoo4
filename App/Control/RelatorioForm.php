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
use Livro\Validation\RequiredValidator;

use Bootstrap\Wrapper\FormWrapper;

/*
 * classe RelatorioForm
 * relatório de vendas por período
 */
class RelatorioForm extends Page
{
    private $form;   // formulário de entrada

    /*
     * método construtor
     * cria a página e o formulário de parâmetros
     */
    public function __construct()
    {
        parent::__construct();

        // instancia um formulário
        $this->form = new Form('form_relat_vendas');

        // cria os campos do formulário
        $data_ini = new Entry('data_ini');
        $data_fim = new Entry('data_fim');
        
        $this->form->addField('Data Inicial', $data_ini, 200);
        $this->form->addField('Data Final', $data_fim, 200);
        $this->form->addAction('Gerar', new Action(array($this, 'onGera')));
        
        // adiciona o formulário à página
        parent::add($this->form);
    }

    /*
     * método onGera
     * gera o relatório, baseado nos parâmetros do formulário
     */
    function onGera()
    {
        // obtém os dados do formulário
        $dados = $this->form->getData();

        // joga os dados de volta ao formulário
        $this->form->setData($dados);

        // lê os campos do formulário, converte para o padrão americano
        $data_ini = $this->conv_data_to_us($dados->data_ini);
        $data_fim = $this->conv_data_to_us($dados->data_fim);

        // instancia uma nova tabela
        $table = new Table;
        $table->border = 1;
        $table->width = '80%';
        $table->style = 'border-collapse:collapse';

        // adiciona uma linha para o cabeçalho do relatório
        $row = $table->addRow();
        $row->bgcolor = '#a0a0a0';

        // adiciona as células ao cabeçalho
        $cell = $row->addCell('Data');
        $cell = $row->addCell('Cliente/Produtos');
        $cell = $row->addCell('Qtde');

        $cell->align = 'right';
        $cell = $row->addCell('Preço');
        $cell->align = 'right';
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
            
            var_dump($criterio->dump());
            // lê todas vendas que satisfazem ao critério
            $vendas = $repositorio->load($criterio);

            // verifica se retornou algum objeto
            if ($vendas)
            {
                // percorre as vendas
                foreach ($vendas as $venda)
                {
                    // adiciona uma linha à tabela e define suas propriedades
                    $row = $table->addRow();
                    $row->bgcolor = "#e0e0e0";

                    // adiciona células para data da venda e dados do cliente
                    $cell = $row->addCell($this->conv_data_to_br($venda->data_venda));
                    $cell = $row->addCell($venda->id_cliente . ' : ' . $venda->cliente->nome);
                    $cell->colspan=3;

                    // verifica se a venda possui itens
                    if ($venda->itens)
                    {
                        $sub_total =0;
                        $total_qtde=0;

                        // percorre os itens da venda

                        foreach ($venda->itens as $item)
                        {
                            // adiciona uma linha para cada item da venda
                            $row = $table->addRow();

                            // adiciona as células com os dados do item
                            $cell = $row->addCell('');
                            $cell = $row->addCell($item->id_produto . ' : ' . $item->descricao);
                            $cell = $row->addCell($item->quantidade);
                            $cell->align = 'right';
                            $cell = $row->addCell(number_format($item->preco_venda,2,',','.'));
                            $cell->align = 'right';

                            // acumula totais de valor e quantidade
                            $sub_total += $item->quantidade * $item->preco_venda;
                            $total_qtde += $item->quantidade;
                        }
                       // adiciona uma linha para os totais da venda
                       $row = $table->addRow();
                       $cell = $row->addCell('');
                       $cell = $row->addCell('<b>Sub-Total</b>');
                       $cell = $row->addCell('<b>'.$total_qtde.'</b>');
                       $cell->align = 'right';
                       $cell = $row->addCell('<b>'.number_format($sub_total,2,',','.').'</b>');
                       $cell->align = 'right';
                    }
              }
            }
            // finaliza a transação
            Transaction::close();
        }
        catch (Exception $e)		     // em caso de exceção
        {
            // exibe a mensagem gerada pela exceção
            new Message('error', $e->getMessage());
            // desfaz todas alterações no banco de dados
            Transaction::rollback();
        }
        // adiciona a tabela à página
        parent::add($table);
    }

    /*
     * método conv_data_to_us()
     * Converte uma data para o formato yyyy-mm-dd
     * @param $data = data no formato dd/mm/yyyy
     */
    function conv_data_to_us($data)
    {
        $dia = substr($data,0,2);
        $mes = substr($data,3,2);
        $ano = substr($data,6,4);
        return "{$ano}-{$mes}-{$dia}";
    }

    /*
     * método conv_data_to_br()
     * Converte uma data para o formato dd/mm/yyyy
     * @param $data = data no formato yyyy-mm-dd
     */
    function conv_data_to_br($data)
    {
        // captura as partes da data
        $ano = substr($data,0,4);
        $mes = substr($data,5,2);
        $dia = substr($data,8,4);

        // retorna a data resultante
        return "{$dia}/{$mes}/{$ano}";
    }
}
