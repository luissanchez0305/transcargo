<html>
<?php
    /*$content = "
<page>
    <h1>Exemple d'utilisation</h1>
    <br>
    Ceci est un <b>exemple d'utilisation</b>
    de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
</page>";*/

    /*require_once(dirname(__FILE__).'/libraries/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');*/
?>
	<?= $data['header']; ?>
	<style>
		body {
			background-image: url('');
			background-color: #fff;
		}	
	</style>
	<?= $data['list']; ?>
	<script>
	 	window.print();
	</script>
</html>