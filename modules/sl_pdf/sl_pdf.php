<?php


session_start();

if ($_SESSION["sl_pdf_data"]) {

	require_once('../../core/plugins/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P','A4', 'fr', false, 'ISO-8859-15');
			$html2pdf->setTestTdInOnePage(false);
			$html2pdf->writeHTML($_SESSION["sl_pdf_data"], false);
			$html2pdf->Output('commande.pdf');
		}
		catch(HTML2PDF_exception $e) { 
			echo "Une erreur de cr&eacute;ation du PDF c'est produite, <br/>
				n&eacute;anmoins voici votre bon de commande pour l'impression<br/><br/>";
			echo $_SESSION["sl_pdf_data"]; 
		}

}


?>