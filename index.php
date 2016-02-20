<?php
require './dao/ConsultaProfessorFatecDAO.php';
$consulta = \Dao\ConsultaProfessorFatecDAO::getInstance();
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Consulta automatizada de professores / email da Fatec Ourinhos</title>
    </head>
    <body>

        <form method="post" action="./gera-pdf.php" target="_blank">
            
            <label>
                Curso:
            </label>
            <fieldset>
                
                <select name="url" id="url">

                    <option value="<?php echo $consulta->getUrl(); ?>/ads/docentes/">ADS</option>
                    <option value="<?php echo $consulta->getUrl(); ?>/agro/docentes/">AGRO</option>
                    <option value="<?php echo $consulta->getUrl(); ?>/jogos/docentes/">JOGOS</option>
                    <option value="<?php echo $consulta->getUrl(); ?>/seguranca/docentes/">SEGURANÇA</option>
                </select>
                <br/><br/>
                <button type="submit">
                    Consultar
                </button>
            </fieldset>
        </form>
    </body>
</html>
