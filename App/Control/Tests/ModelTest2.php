<?php
use Livro\Control\Page;
use Livro\Database\Transaction;

class ModelTest2 extends Page
{
    public function show()
    {
        try {
            Transaction::open('livro');
            
            $p1 = Pessoa::find(1);
            $p1->delGrupos();
            $p1->addGrupo( new Grupo(1) );
            $p1->addGrupo( new Grupo(3) );
            
            $grupos = $p1->getGrupos();
            
            if ($grupos) {
                foreach ($grupos as $grupo) {
                    print $grupo->id . ' - ';
                    print $grupo->nome . '<br>';
                }
            }
            Transaction::close();
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}