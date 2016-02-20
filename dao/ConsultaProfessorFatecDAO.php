<?php

/**
 * Description of ConsultaProfessorFatecDAO
 *
 * @author Éric Luiz Ferras <eric.ferras@fatec.sp.gov.br>
 */
namespace Dao;
class ConsultaProfessorFatecDAO {

    private static $instance = null;
    private $url = "https://www.fatecourinhos.edu.br";
    private $charset = "utf-8";
    private $idBusca = "#tablepress-20-no-2";
    private $idBuscaLogoCurso = "#tablepress-19-no-2";
    private $tiraIndice = -1;
    private $nomeCurso = "";

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setChartSet($charset) {
        $this->charset = $charset;
    }

    public function getChartSet() {
        return $this->charset;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ConsultaProfessorFatecDAO();
        }
        return self::$instance;
    }

    private function normalizeIdBusca() {
        $u = $this->url;
        if (strpos($u, 'ads') !== FALSE) {
            $this->idBusca = "#tablepress-20-no-2";
            $this->idBuscaLogoCurso = "#tablepress-19-no-2";
        } else if (strpos($u, 'agro') !== FALSE) {
            $this->idBusca = "#tablepress-15-no-2";
            $this->idBuscaLogoCurso = "#tablepress-14-no-2";
            $this->tiraIndice = 0;
        } else if (strpos($u, 'jogos') !== FALSE) {
            $this->idBusca = "#tablepress-5-no-2";
            $this->idBuscaLogoCurso = "#tablepress-16-no-2";
            $this->tiraIndice = 0;
        } else if (strpos($u, 'seguranca') !== FALSE) {
            $this->idBusca = "#tablepress-18-no-2";
            $this->idBuscaLogoCurso = "#tablepress-17-no-2";
        }
    }

    public final function getConsulta() {
        $this->normalizeIdBusca();
        $retorno = array();
        $html = \Util\Util::simpleCurl($this->url);
       
        $d = \phpQuery::newDocumentHTML($html, $this->charset);
        $data = array(
            'nome' => trim(pq("table{$this->idBusca} .column-1")),
            'email' => trim(pq("table{$this->idBusca} .column-2")),
        );
        /**  
        echo '<pre>';
        print_r($data);
        echo '</pre>';die;
         * 
         */

        $cabecalho = array(
            'curso' => trim(pq("table{$this->idBuscaLogoCurso} .column-2")),
            'logo' => trim(pq("table{$this->idBuscaLogoCurso} .column-1")),
        );
            
        if (!empty($data)) {
            $nome = str_replace('</div></th>', '', str_replace('<th class="column-1"><div>', '', str_replace("</td>", '', explode('<td class="column-1">', $data['nome']))));
            $email = str_replace('</div></th>', '', str_replace('<th class="column-2"><div>', '', str_replace("</td>", '', explode('<td class="column-2">', $data['email']))));

           
            if (!empty($nome)) {
                foreach ($nome as $key => $n) {
                    if ($this->tiraIndice >= 0 && $this->tiraIndice == $key) {
                        unset($nome[$key]);
                        continue;
                    }
                    $retorno['professor'][$key]['nome'] = utf8_decode($n);
                }
            }

            if (!empty($email)) {
                foreach ($email as $key => $e) {
                    if ($this->tiraIndice >= 0 && $this->tiraIndice == $key) {
                        unset($email[$key]);
                        continue;
                    }
                    $retorno['professor'][$key]['email'] = utf8_decode($e);
                }
            }
        }

        if (!empty($cabecalho['curso'])) {
            $c = utf8_decode($cabecalho['curso']);
            $retorno['curso'] = $c;
        }

        if (!empty($cabecalho['logo'])) {
            $retorno['logo'] = $cabecalho['logo'];
        }
        /**
        echo '<pre>';
        print_r($retorno);
        echo '</pre>';die;
         * 
         */

      
        return $retorno;
    }

}
