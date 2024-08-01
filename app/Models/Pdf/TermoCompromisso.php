<?php

namespace App\Models\Pdf;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Utils;
use App\Models\Comprovante;

class TermoCompromisso extends Comprovante
{
    function Header()
    {
		$this->Image($this->cabecalho, 25, 10, 25);
        $this->SetFont('Arial', 'B', 14);
        
        $this->Ln(8);
        $this->Cell(190, 8, utf8_decode(Str::upper('Termo de Compromisso')), 0, 0, "C");
        $this->Ln();
        $this->Cell(190, 8, utf8_decode('Programa Demanda Social - DS'), 0, 0, "C");
        $this->Ln(15);
    }

    function Footer()
    {
        $this->SetFont('Arial', '', 10);
        $this->SetY(-70);

        $this->WriteTag(22, 5, utf8_decode('<i>Local e data:</i>'), 0, 'J');
        $this->Cell(168, 8, '', 'T', 0, 'C');
        $this->Ln();
        $this->WriteTag(62, 5, utf8_decode('<i>Assinatura do(a) beneficiário da bolsa:</i>'), 0, 'J');
        $this->Cell(128, 8, '', 'T', 0, 'C');
        $this->Ln();
        
        $this->WriteTag(190, 15, utf8_decode('<i>Coordenador(a) do Programa de Pós-Graduação</i>'), 0, 'C');
        $this->Ln();
            
        $this->Cell(55, 10, '', 0, 0, 'C');
        $this->Cell(80, 10, '', 'B', 0, 'C');
        $this->Cell(55, 10, '', 0, 0, 'C');
        $this->Ln();

        $this->WriteTag(190, 8, utf8_decode('<i>Carimbo e assinatura</i>'), 0, 'C');
    }

}
