<?php
namespace Util;
abstract class Util {

    private function __construct() {
        
    }

    /**
     * Auxiliará na busca automatizada
     * @author Éric Luiz Ferras <eric.ferras@fatec.sp.gov.br>
     * @param type $url
     * @param type $post
     * @param type $get
     * @return type
     */
    public static function simpleCurl($url, $post = array(), $get = array()) {
        $url = explode('?', $url, 2);
        if (count($url) === 2) {
            $temp_get = array();
            parse_str($url[1], $temp_get);
            $get = array_merge($get, $temp_get);
        }

        if (!empty($get)) {
            $ch = curl_init($url[0] . "?" . http_build_query($get));
        } else {
            if (is_array($url)) {
                $ch = curl_init($url[0]);
            } else {
                $ch = curl_init($url[0]);
            }
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }

    public static function debug($val, $d = null) {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
        echo '<br/>';
        if (!empty($d)) {
            die;
        }
    }
    
    public static function mpdf(array $pdf, $conf = array()) {

        /**
          conversion HTML => PDF */
        //include_once("mpdf/mpdf.php");
        //include_once 'mpdf.php';

        if (!empty($conf['orientation']) && !empty($conf['format'])) {
            if ($conf['orientation'] == 'L') {
                $conf['format'] = $conf['format'] . '-L';
            }
        }

        $mpdf = new \mPDF(
                        $conf['mode'] ? $conf['mode'] : 'c',
                        $conf['format'] ? $conf['format'] : 'A4',
                        $conf['font_size'] ? $conf['font_size'] : '',
                        $conf['font'] ? $conf['font'] : '',
                        $conf['m_left'] ? $conf['m_left'] : '',
                        $conf['m_right'] ? $conf['m_right'] : '',
                        $conf['m_top'] ? $conf['m_top'] : '',
                        $conf['m_bottom'] ? $conf['m_bottom'] : '',
                        $conf['m_header'] ? $conf['m_header'] : '',
                        $conf['m_footer'] ? $conf['m_footer'] : '',
                        $conf['orientation'] ? $conf['orientation'] : 'P'
        );

        if (isset($pdf['header'])) {
            $mpdf->SetHTMLHeader($pdf['header']);
        }
        if (isset($pdf['footer'])) {
            $mpdf->SetHTMLFooter($pdf['footer']);
        }

        if (isset($pdf['title'])) {
            $mpdf->SetTitle($pdf['title']);
        }

        $mpdf->WriteHTML($pdf['content']);

        if(empty($pdf['name'])){
            $pdf['name'] = null;
        }
        
        $mpdf->Output($pdf['name'],'I');

        exit;
    }

}
