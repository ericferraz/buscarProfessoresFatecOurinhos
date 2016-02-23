<?php
ini_set('display_errors','On');
ini_set('error_reporting', E_ALL);
ob_start();
$cor = "#fff";
require './vendor/autoload.php';
require './util/Util.php';
require './dao/ConsultaProfessorFatecDAO.php';
if (!empty($url = $_POST['url'])) {
    
    $consulta = new \Dao\ConsultaProfessorFatecDAO();
    $consulta->setUrl($url);
    $data = $consulta->getConsulta();
    //Util::debug($data);die;
}
if (!empty($data['logo']) && !empty($data['curso'])) {
    ?>

    <table border="0" style="width: 100%;border-collapse: collapse;margin-bottom: 10px;">
      
            <tr>
                
                <?php echo $data['logo']; ?>
                <?php echo $data['curso']; ?>
       
            </tr>
       
    </table>
    <?php
}
?>

<table border="1" style="width: 100%;border-collapse: collapse;margin-top: 10px;">
    <thead>
        <tr>
            <th>
                <b>
                    Nome
                </b>
            </th>

            <th>
                <b>
                    Email
                </b>
            </th>
        </tr>
    </thead>
    <?php
    if (!empty($data)) {
        ?>

        <tbody>
            <?php
            foreach ($data['professor'] as $val) {
                if ($cor == '#fff') {
                    $cor = '#ddd';
                } else {
                    $cor = '#fff';
                }
                ?>
                <tr style="background-color: <?php echo $cor; ?>;">
                    <td>
                        <?php echo $val['nome']; ?>
                    </td>
                    <td>
                        <?php echo $val['email']; ?>
                    </td>
                </tr>


                <?php
            }
        } else {
            ?>
            <tr style="background-color:#ddd;">
                <td colspan="2">
                    Nenhuma informação encontrada.
                </td>
            </tr>

            <?php
        }
        ?>

    </tbody>
</table>



<?php
$conteudo = ob_get_contents();
ob_clean();

$conf['mode'] = 'c';
$conf['format'] = 'A4';
$conf['font_size'] = '12';
$conf['font'] = 'arial';
$conf['m_left'] = 5;
$conf['m_right'] = 5;
$conf['m_top'] = 5;
$conf['m_bottom'] = 5;
$conf['m_header'] = 5;
$conf['m_footer'] = 5;
$conf['orientation'] = 'P';

$date = date('Y-m-d H-i-s');
$date = str_replace("", "-", $date);
$pdf['name'] = sprintf("RELATORIO_PROFESSORES_FATEC_OURINHOS_%s.pdf", $date);
$pdf['content'] = $conteudo;
Util\Util::mpdf($pdf, $conf);
?>
