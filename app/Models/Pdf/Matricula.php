<?php

namespace App\Models\Pdf;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Utils;
use App\Models\Comprovante;

class Matricula extends Comprovante
{
    function Footer()
    {
        $this->SetY(-40);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(120, 8, utf8_decode('DEFERIDO EM 21/07/2023'), 0, 0, 'L', false);
        $this->Cell(60, 8, '', 'B', 0, 'C', false);
        $this->Cell(10, 8, '', 0, 0, 'L', false);
        $this->Ln();         

        $this->Cell(120, 8, '', 0, 0, 'L', false);
        $this->Cell(60, 8, 'Presidente da CPG', 0, 0, 'C', false);
        $this->Cell(10, 8, '', 0, 0, 'L', false);  
        $this->Ln(); 
        $this->Ln();

        $this->SetFont("Arial","B",8);
        $this->SetY(-15);
        $this->Cell(95, 3, utf8_decode("ÁREA I"), 0, 0, "L");
        $this->Cell(95, 3, utf8_decode("ÁREA II"), 0, 0, "R");
        $this->SetFont("Times", "", 7);
        $this->Ln();
        $this->Cell(95, 3, utf8_decode("Estrada Municipal do Campinho, Nº 100, Campinho, Lorena/SP"), 0, 0, "L");
        $this->Cell(95, 3, utf8_decode("Estrada Municipal Chiquito de Aquino, Nº 1000, Mondesir, Lorena/SP"), 0, 0, "R");
        $this->Ln();
        $this->Cell(95, 3, "CEP 12602-810 - Tel. (012) 3159-5000", 0, 0, "L");
        $this->Cell(95, 3, "CEP 12612-550 - Tel. (012) 3159-9900", 0, 0, "R");
    }
}
