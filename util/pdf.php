<?php

include_once(dirname(__FILE__).'/../config/pdf.php');

class PDF extends FPDF
{
	
	//En-tête
	function Header()
	{
		$this->SetY(7);
		
		//Logo
		$this->Image(dirname(__FILE__).'/../image/elba_print.jpg',0,0);
		//Police Arial gras 15
		$this->SetFont('Arial','B',14);
		$this->SetFillColor(0,39,161);
		$this->SetTextColor(255,255,255);
		//Décalage à droite
		$this->Cell(35);
		
		//Titre
		$this->Cell(0,8,$this->title,0,0,'C',1);
		//Saut de ligne
		$this->Ln(15);
	}
	
	//Pied de page
	function Footer()
	{
		$this->SetY(-15);
		//Numéro de page
		$this->SetFont('Arial','B',12);
		$this->SetFillColor(0,39,161);
		$this->SetTextColor(255,255,255);
		$this->Cell(0,8,$this->subject,0,0,'L',1);
		//Police Arial italique 8
		$this->SetFont('Arial','I',8);
		$this->Cell(0,8,'Page '.$this->PageNo().' sur {nb}',0,0,'R');
	}
	
	//Tableau coloré
	function Table($header, $data)
	{		
		//Données
		$alternate = false;
		$sum = 0;
		
		for($i = 0; $i < count($header); $i++) {
			$sum += $header[$i][1];
		}
		
		for($i = 0; $i < count($data); $i++)
		{
			if (($i % 32) == 0) {
				
				if ($i != 0) {
					$this->Cell($sum,0,'','T');
					$this->Ln();
				}
				
				//Couleurs, épaisseur du trait et police grasse
				$this->SetFillColor(255,204,102);
				$this->SetTextColor(0);
				$this->SetFont('','B');
		
				for($j = 0; $j < count($header); $j++) {
					$this->Cell($header[$j][1], 5, $header[$j][0],1,0,'C',1);
				}
				
				$this->Ln();
				
				$alternate = false;
				//Restauration des couleurs et de la police
				$this->SetFillColor(255,255,153);
				$this->SetTextColor(0);
				$this->SetFont('');
			}
			
			for($j = 0; $j < count($header); $j++) {
			
				if ($header[$j][2] == "number") {
					$this->Cell($header[$j][1],5,$data[$i][$j],'LR',0,'R',$alternate);
				} else if ($header[$j][2] == "center") {
					$this->Cell($header[$j][1],5,$data[$i][$j],'LR',0,'C',$alternate);
				} else {
					$this->Cell($header[$j][1],5,$data[$i][$j],'LR',0,'L',$alternate);
				}
			}
			
			$this->Ln();
			
			$alternate = !$alternate;
		}
		
		if ($i > 0) {
			$this->Cell($sum,0,'','T');
			$this->Ln();
		}
	}
}

function print_pdf($title, $bottom, $author, $header, $data) {

	$title = 'Appli Cellules | '.$title.' | '.date("d/m/Y G:i");
	
	$pdf=new PDF('L', 'mm', 'A4');
	$pdf->SetTitle($title);
	$pdf->SetSubject($bottom);
	
	$pdf->SetAuthor($author);

	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);
	
	$pdf->Table($header, $data);
	
	$pdf->Output();
}

?>