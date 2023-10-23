<?php

namespace App\Models;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;

class Comprovante extends Fpdf
{
    protected $cabecalho;

    public function __construct($cabecalho)
    {
        $this->cabecalho = $cabecalho;
    }

    function Header()
    {
        $this->Image("/var/www/drupal_sites/sites/files/ppgem/publico/inscricao/cabecalho.png", 10, 10, 190);
        $this->Ln(35);
    }

    function Footer()
    {
        $this->SetFont("Arial","B",8);
        $this->SetY(-15);
        $this->Cell(95, 3, utf8_decode("ÁREA I"), 0, 0, "L");
        $this->Cell(95, 3, utf8_decode("ÁREA II"), 0, 0, "R");
        $this->SetFont("Times", "", 7);
        $this->Ln();
        $this->Cell(95, 3, utf8_decode("Estrada Municipal  do Campinho - LORENA - S.P."), 0, 0, "L");
        $this->Cell(95, 3, utf8_decode("Polo Urbo Industrial - Gleba AI 6 - LORENA - S.P."), 0, 0, "R");
        $this->Ln();
        $this->Cell(95, 3, "CEP 12602-810  Tel / Fax  (012) 3159-5051", 0, 0, "L");
        $this->Cell(95, 3, "CEP 12600-970 - Tel. (012) 3159-9004", 0, 0, "R");
    }

    //Cell with horizontal scaling if text is too wide
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
        $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Same as calling CellFit directly
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }

    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }   

    public static function obterTitulo($siglaNivel, $codigoCurso)
    {
        $curso = Utils::obterCurso($codigoCurso);

        dd($curso);

        if ($siglaNivel == "ME")
        {
            $titulo = "REQUERIMENTO DE INSCRIÇÃO PARA EXAME DE SELEÇÃO MESTRADO ";
        }
    }
    
    public static function gerarComprovante($codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao(Auth::user()->id, $codigoInscricao);
        $edital    = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();

    dd($edital);
    


        /*if ($tipo == 'mestrado')
        {                            
            $titulo      = 'REQUERIMENTO DE INSCRIÇÃO PARA EXAME DE SELEÇÃO NO MESTRADO PROFISSIONAL DO 
            PROGRAMA DE PÓS-GRADUAÇÃO EM PROJETOS EDUCACIONAIS DE CIÊNCIAS - PPGPE';
            $codigo      = 'M';
            $curso       = 'Seleção do Curso de Mestrado para ingresso no '.$semestre.'.';
            $requerimento  = 'Venho requerer minha inscrição para o processo seletivo conforme regulamenta o edital PPGPE Nº '.$edital.' (DOESP de '.$data_edital.').';
        }*/

        $pdf = new Comprovante('');
        $pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B', 16);
        $pdf->SetFillColor(190,190,190);
        $pdf->MultiCell(190, 8, utf8_decode('REQUERIMENTO DE INSCRIÇÃO'), 1, 'C', true);            
        $pdf->Ln();

        $pdf->SetFont('Arial','B', 10);    
        $pdf->Cell(140, 8, utf8_decode('NÚMERO DE INSCRIÇÃO'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(50, 8, $codigo.$registration->field_codigo['und'][0]['value'] . ' - '.$edital, 1, 0, 'L', true);
        $pdf->Ln();

    }
}
