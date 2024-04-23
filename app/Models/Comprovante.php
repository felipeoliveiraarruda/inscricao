<?php

namespace App\Models;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Edital;
use App\Models\Utils;
use App\Models\Inscricao;

class Comprovante extends Fpdf
{
	protected $sigla     = '';
    protected $cabecalho = '';
    protected $presenca  = '';

    function setCabecalho($sigla)
    {
        $this->sigla = Str::lower($sigla);
        $this->cabecalho = asset("images/cabecalho/{$this->sigla}.png");
    }

    function setCabecalhoPresenca($sigla)
    {
        $this->sigla = Str::lower($sigla);
        $this->cabecalho = asset("images/cabecalho/presenca/{$this->sigla}.png");
    }

    function setPresenca($sigla)
    {
        $sigla = Str::lower($sigla);
        $this->presenca = $sigla;
    }

    function Header()
    {
		if ($this->sigla == 'bolsista')
		{
			$this->Image($this->cabecalho, 0, 0, 210);
		}
		else
		{
			$this->Image($this->cabecalho, 10, 10, 190);
			$this->Ln(35);
		}
    }

    function Footer()
    {
        if ($this->presenca == '')
        {
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
        else
        {
            $this->SetFont("Times", "", 7);
            $this->SetY(-30);
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Cell(170, 3, utf8_decode("Escola de Engenharia de Lorena - EEL/USP"), 0, 0, "L");
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Ln();
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Cell(170, 3, utf8_decode("Área II / DEMAR / PPGEM"), 0, 0, "L");
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Ln();
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Cell(170, 3, utf8_decode("Estrada Municipal do Campinho, Nº 100, Campinho, Lorena/SP"), 0, 0, "L");
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Ln();
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Cell(170, 3, utf8_decode("CEP: 12.602-810 - Lorena/SP"), 0, 0, "L");
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Ln();
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Cell(170, 3, utf8_decode("(12) 3159-9904"), 0, 0, "L");
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Ln();
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Cell(170, 3, utf8_decode("www.eel.usp.br / ppgem-eel@usp.br"), 0, 0, "L");
            $this->Cell(10, 3, utf8_decode(''), 0, 0, "L");
            $this->Ln();

            $this->Image(asset("images/cabecalho/presenca/logo_{$this->presenca}.jpg"), 135, 270, 30);
        }

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

    protected $wLine = ''; // Maximum width of the line
	protected $hLine = ''; // Height of the line
	protected $Text = ''; // Text to display
	protected $border = '';
	protected $align = ''; // Justification of the text
	protected $fill = '';
	protected $Padding = '';
	protected $lPadding = '';
	protected $tPadding = '';
	protected $bPadding = '';
	protected $rPadding = '';
	protected $TagStyle = array(); // Style for each tag
	protected $Indent = '';
	protected $Bullet = ''; // Bullet character
	protected $Space = ''; // Minimum space between words
	protected $PileStyle = ''; 
	protected $Line2Print = ''; // Line to display
	protected $NextLineBegin = ''; // Buffer between lines 
	protected $TagName = '';
	protected $Delta = ''; // Maximum width minus width
	protected $StringLength = ''; 
	protected $LineLength = '';
	protected $wTextLine = ''; // Width minus paddings
	protected $nbSpace = ''; // Number of spaces in the line
	protected $Xini = ''; // Initial position
	protected $href = ''; // Current URL
	protected $TagHref = ''; // URL for a cell
	protected $LastLine = '';

	// Public Functions

	function WriteTag($w, $h, $txt, $border=0, $align="J", $fill=false, $padding=0)
	{
		$this->wLine=$w;
		$this->hLine=$h;
		$this->Text=trim($txt);
		$this->Text=preg_replace("/\n|\r|\t/","",$this->Text);
		$this->border=$border;
		$this->align=$align;
		$this->fill=$fill;
		$this->Padding=$padding;

		$this->Xini=$this->GetX();
		$this->href="";
		$this->PileStyle=array();
		$this->TagHref=array();
		$this->LastLine=false;
		$this->NextLineBegin=array();

		$this->SetSpace();
		$this->Padding();
		$this->LineLength();
		$this->BorderTop();

		while($this->Text!="")
		{
			$this->MakeLine();
			$this->PrintLine();
		}

		$this->BorderBottom();
	}

	function SetStyle($tag, $family, $style, $size, $color, $indent=-1, $bullet='')
	{
		$tag=trim($tag);
		$this->TagStyle[$tag]['family']=trim($family);
		$this->TagStyle[$tag]['style']=trim($style);
		$this->TagStyle[$tag]['size']=trim($size);
		$this->TagStyle[$tag]['color']=trim($color);
		$this->TagStyle[$tag]['indent']=$indent;
		$this->TagStyle[$tag]['bullet']=$bullet;
	}

	// Private Functions
	function SetSpace() // Minimal space between words
	{
		$tag=$this->Parser($this->Text);
		
		$this->FindStyle($tag[2],0);
		$this->DoStyle(0);
		$this->Space=$this->GetStringWidth(" ");
	}

	function Padding()
	{
		if(preg_match("/^.+,/",$this->Padding)) {
			$tab=explode(",",$this->Padding);
			$this->lPadding=$tab[0];
			$this->tPadding=$tab[1];
			if(isset($tab[2]))
				$this->bPadding=$tab[2];
			else
				$this->bPadding=$this->tPadding;
			if(isset($tab[3]))
				$this->rPadding=$tab[3];
			else
				$this->rPadding=$this->lPadding;
		}
		else
		{
			$this->lPadding=$this->Padding;
			$this->tPadding=$this->Padding;
			$this->bPadding=$this->Padding;
			$this->rPadding=$this->Padding;
		}
		if($this->tPadding<$this->LineWidth)
			$this->tPadding=$this->LineWidth;
	}


	function LineLength()
	{
		if($this->wLine==0)
			$this->wLine=$this->w - $this->Xini - $this->rMargin;

		$this->wTextLine = $this->wLine - $this->lPadding - $this->rPadding;
	}


	function BorderTop()
	{
		$border=0;
		if($this->border==1)
			$border="TLR";
		$this->Cell($this->wLine,$this->tPadding,"",$border,0,'C',$this->fill);
		$y=$this->GetY()+$this->tPadding;
		$this->SetXY($this->Xini,$y);
	}


	function BorderBottom()
	{
		$border=0;
		if($this->border==1)
			$border="BLR";
		$this->Cell($this->wLine,$this->bPadding,"",$border,0,'C',$this->fill);
	}

	function DoStyle($ind) // Applies a style
	{
		if(!isset($this->TagStyle[$ind]))
			return;

		$this->SetFont($this->TagStyle[$ind]['family'],
			$this->TagStyle[$ind]['style'],
			$this->TagStyle[$ind]['size']);

		$tab=explode(",",$this->TagStyle[$ind]['color']);
		if(count($tab)==1)
			$this->SetTextColor($tab[0]);
		else
			$this->SetTextColor($tab[0],$tab[1],$tab[2]);
	}

	function FindStyle($tag, $ind) // Inheritance from parent elements
	{
		$tag=trim($tag);

		// Family
		if($this->TagStyle[$tag]['family']!="")
			$family=$this->TagStyle[$tag]['family'];
		else
		{
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				if($this->TagStyle[$val]['family']!="") {
					$family=$this->TagStyle[$val]['family'];
					break;
				}
			}
		}

		// Style
		$style="";
		$style1=strtoupper($this->TagStyle[$tag]['style']);
		if($style1!="N")
		{
			$bold=false;
			$italic=false;
			$underline=false;
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				$style1=strtoupper($this->TagStyle[$val]['style']);
				if($style1=="N")
					break;
				else
				{
					if(strpos($style1,"B")!==false)
						$bold=true;
					if(strpos($style1,"I")!==false)
						$italic=true;
					if(strpos($style1,"U")!==false)
						$underline=true;
				} 
			}
			if($bold)
				$style.="B";
			if($italic)
				$style.="I";
			if($underline)
				$style.="U";
		}

		// Size
		if($this->TagStyle[$tag]['size'] != 0)
			$size=$this->TagStyle[$tag]['size'];
		else
		{			
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				if($this->TagStyle[$val]['size']!=0) {
					$size=$this->TagStyle[$val]['size'];
					break;
				}
			}
		}

		// Color
		if($this->TagStyle[$tag]['color']!="")
			$color=$this->TagStyle[$tag]['color'];
		else
		{
			foreach($this->PileStyle as $val)
			{
				$val=trim($val);
				if($this->TagStyle[$val]['color']!="") {
					$color=$this->TagStyle[$val]['color'];
					break;
				}
			}
		}

	
		 
		// Result
		$this->TagStyle[$ind]['family']=$family;
		$this->TagStyle[$ind]['style']=$style;
		$this->TagStyle[$ind]['size']=0;
		$this->TagStyle[$ind]['color']=$color;
		$this->TagStyle[$ind]['indent']=$this->TagStyle[$tag]['indent'];
	}


	function Parser($text)
	{
		$tab=array();
		// Closing tag
		if(preg_match("|^(</([^>]+)>)|",$text,$regs)) {
			$tab[1]="c";
			$tab[2]=trim($regs[2]);
		}
		// Opening tag
		else if(preg_match("|^(<([^>]+)>)|",$text,$regs)) {
			$regs[2]=preg_replace("/^a/","a ",$regs[2]);
			$tab[1]="o";
			$tab[2]=trim($regs[2]);

			// Presence of attributes
			if(preg_match("/(.+) (.+)='(.+)'/",$regs[2])) {
				$tab1=preg_split("/ +/",$regs[2]);
				$tab[2]=trim($tab1[0]);
				foreach($tab1 as $i=>$couple)
				{
					if($i>0) {
						$tab2=explode("=",$couple);
						$tab2[0]=trim($tab2[0]);
						$tab2[1]=trim($tab2[1]);
						$end=strlen($tab2[1])-2;
						$tab[$tab2[0]]=substr($tab2[1],1,$end);
					}
				}
			}
		}
	 	// Space
	 	else if(preg_match("/^( )/",$text,$regs)) {
			$tab[1]="s";
			$tab[2]=' ';
		}
		// Text
		else if(preg_match("/^([^< ]+)/",$text,$regs)) {
			$tab[1]="t";
			$tab[2]=trim($regs[1]);
		}

		$begin=strlen($regs[1]);
 		$end=strlen($text);
 		$text=substr($text, $begin, $end);
		$tab[0]=$text;

		return $tab;
	}


	function MakeLine()
	{
		$this->Text.=" ";
		$this->LineLength=array();
		$this->TagHref=array();
		$Length=0;
		$this->nbSpace=0;

		$i=$this->BeginLine();
		$this->TagName=array();

		if($i==0) {
			$Length=$this->StringLength[0];
			$this->TagName[0]=1;
			$this->TagHref[0]=$this->href;
		}

		while($Length<$this->wTextLine)
		{
			$tab=$this->Parser($this->Text);
			$this->Text=$tab[0];
			if($this->Text=="") {
				$this->LastLine=true;
				break;
			}

			if($tab[1]=="o") {
				array_unshift($this->PileStyle,$tab[2]);
				$this->FindStyle($this->PileStyle[0],$i+1);

				$this->DoStyle($i+1);
				$this->TagName[$i+1]=1;
				if($this->TagStyle[$tab[2]]['indent']!=-1) {
					$Length+=$this->TagStyle[$tab[2]]['indent'];
					$this->Indent=$this->TagStyle[$tab[2]]['indent'];
					$this->Bullet=$this->TagStyle[$tab[2]]['bullet'];
				}
				if($tab[2]=="a")
					$this->href=$tab['href'];
			}

			if($tab[1]=="c") {
				array_shift($this->PileStyle);
				if(isset($this->PileStyle[0]))
				{
					$this->FindStyle($this->PileStyle[0],$i+1);
					$this->DoStyle($i+1);
				}
				$this->TagName[$i+1]=1;
				if($this->TagStyle[$tab[2]]['indent']!=-1) {
					$this->LastLine=true;
					$this->Text=trim($this->Text);
					break;
				}
				if($tab[2]=="a")
					$this->href="";
			}

			if($tab[1]=="s") {
				$i++;
				$Length+=$this->Space;
				$this->Line2Print[$i]="";
				if($this->href!="")
					$this->TagHref[$i]=$this->href;
			}

			if($tab[1]=="t") {
				$i++;
				$this->StringLength[$i]=$this->GetStringWidth($tab[2]);
				$Length+=$this->StringLength[$i];
				$this->LineLength[$i]=$Length;
				$this->Line2Print[$i]=$tab[2];
				if($this->href!="")
					$this->TagHref[$i]=$this->href;
			 }

		}

		trim($this->Text);
		if($Length>$this->wTextLine || $this->LastLine==true)
			$this->EndLine();
	}


	function BeginLine()
	{
		$this->Line2Print=array();
		$this->StringLength=array();

		if(isset($this->PileStyle[0]))
		{
			$this->FindStyle($this->PileStyle[0],0);
			$this->DoStyle(0);
		}

		if(count($this->NextLineBegin)>0) {
			$this->Line2Print[0]=$this->NextLineBegin['text'];
			$this->StringLength[0]=$this->NextLineBegin['length'];
			$this->NextLineBegin=array();
			$i=0;
		}
		else {
			preg_match("/^(( *(<([^>]+)>)* *)*)(.*)/",$this->Text,$regs);
			$regs[1]=str_replace(" ", "", $regs[1]);
			$this->Text=$regs[1].$regs[5];
			$i=-1;
		}

		return $i;
	}


	function EndLine()
	{
		if(end($this->Line2Print)!="" && $this->LastLine==false) {
			$this->NextLineBegin['text']=array_pop($this->Line2Print);
			$this->NextLineBegin['length']=end($this->StringLength);
			array_pop($this->LineLength);
		}

		while(end($this->Line2Print)==="")
			array_pop($this->Line2Print);

		$this->Delta=$this->wTextLine-end($this->LineLength);

		$this->nbSpace=0;
		for($i=0; $i<count($this->Line2Print); $i++) {
			if($this->Line2Print[$i]=="")
				$this->nbSpace++;
		}
	}


	function PrintLine()
	{
		$border=0;
		if($this->border==1)
			$border="LR";
		$this->Cell($this->wLine,$this->hLine,"",$border,0,'C',$this->fill);
		$y=$this->GetY();
		$this->SetXY($this->Xini+$this->lPadding,$y);

		if($this->Indent>0) {
			if($this->Bullet!='')
				$this->SetTextColor(0);
			$this->Cell($this->Indent,$this->hLine,$this->Bullet);
			$this->Indent=-1;
			$this->Bullet='';
		}

		$space=$this->LineAlign();
		$this->DoStyle(0);
		for($i=0; $i<count($this->Line2Print); $i++)
		{
			if(isset($this->TagName[$i]))
				$this->DoStyle($i);
			if(isset($this->TagHref[$i]))
				$href=$this->TagHref[$i];
			else
				$href='';
			if($this->Line2Print[$i]=="")
				$this->Cell($space,$this->hLine,"         ",0,0,'C',false,$href);
			else
				$this->Cell($this->StringLength[$i],$this->hLine,$this->Line2Print[$i],0,0,'C',false,$href);
		}

		$this->LineBreak();
		if($this->LastLine && $this->Text!="")
			$this->EndParagraph();
		$this->LastLine=false;
	}


	function LineAlign()
	{
		$space=$this->Space;
		if($this->align=="J") {
			if($this->nbSpace!=0)
				$space=$this->Space + ($this->Delta/$this->nbSpace);
			if($this->LastLine)
				$space=$this->Space;
		}

		if($this->align=="R")
			$this->Cell($this->Delta,$this->hLine);

		if($this->align=="C")
			$this->Cell($this->Delta/2,$this->hLine);

		return $space;
	}

	function LineBreak()
	{
		$x=$this->Xini;
		$y=$this->GetY()+$this->hLine;
		$this->SetXY($x,$y);
	}

	function EndParagraph()
	{
		$border=0;
		if($this->border==1)
			$border="LR";
		$this->Cell($this->wLine,$this->hLine/2,"",$border,0,'C',$this->fill);
		$x=$this->Xini;
		$y=$this->GetY()+$this->hLine/2;
		$this->SetXY($x,$y);
	}

    public static function obterTitulo($siglaNivel, $codigoCurso, $siglaPrograma)
    {
        $curso = Utils::obterCurso($codigoCurso);

        if ($siglaNivel == "ME")
        {
            $titulo = "REQUERIMENTO DE INSCRIÇÃO PARA EXAME DE SELEÇÃO MESTRADO - {$siglaPrograma}";
        }
		
        if ($siglaNivel == "AE")
        {
            $titulo = "REQUERIMENTO DE INSCRIÇÃO PARA ALUNO ESPECIAL - {$siglaPrograma}";
        }

		if ($siglaNivel == "DF")
        {
            $titulo = "REQUERIMENTO DE INSCRIÇÃO PARA DOUTORADO FLUXO CONTINUO - {$siglaPrograma}";
        }

        return $titulo;
    }

    public static function setTituloPresenca($siglaNivel, $codigoCurso, $siglaPrograma)
    {
        $curso = Utils::obterCurso($codigoCurso);

        if ($siglaNivel == "ME")
        {
            $temp['titulo']     = "PROVA DE SELEÇÃO MESTRADO - {$siglaPrograma}";
            $temp['sub_titulo'] = "PÓS-GRADUAÇÃO EM ENGENHARIA DE MATERIAIS - {$siglaPrograma}";
        }

        return  $temp;
    }
    
    public static function gerarComprovante($cabecalho, $codigoInscricao)
    {
        $inscricao = Inscricao::obterDadosPessoaisInscricao(Auth::user()->id, $codigoInscricao);
        $edital    = Edital::join('niveis', 'editais.codigoNivel', '=', 'niveis.codigoNivel')->where('editais.codigoEdital', $inscricao->codigoEdital)->first();    

        /*if ($tipo == 'mestrado')
        {                            
            $titulo      = 'REQUERIMENTO DE INSCRIÇÃO PARA EXAME DE SELEÇÃO NO MESTRADO PROFISSIONAL DO 
            PROGRAMA DE PÓS-GRADUAÇÃO EM PROJETOS EDUCACIONAIS DE CIÊNCIAS - PPGPE';
            $codigo      = 'M';
            $curso       = 'Seleção do Curso de Mestrado para ingresso no '.$semestre.'.';
            $requerimento  = 'Venho requerer minha inscrição para o processo seletivo conforme regulamenta o edital PPGPE Nº '.$edital.' (DOESP de '.$data_edital.').';
        }

        $pdf = new Comprovante('');
        $pdf->AddPage();
        $pdf->SetFillColor(190,190,190);
        $pdf->Image(asset('images/cabecalho/pae.png'), 10, 10, 190);
        $pdf->Ln(40);


        /*$pdf->SetDisplayMode('real');
        $pdf->AliasNbPages();
        $pdf->AddPage('P');
        $pdf->SetFont('Arial','B', 16);
        $pdf->SetFillColor(190,190,190);
        $pdf->MultiCell(190, 8, utf8_decode('REQUERIMENTO DE INSCRIÇÃO'), 1, 'C', true);            
        $pdf->Ln();

        $pdf->SetFont('Arial','B', 10);    
        $pdf->Cell(140, 8, utf8_decode('NÚMERO DE INSCRIÇÃO'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetFillColor(255,255,255);
        $pdf->Cell(50, 8, $inscricao->numeroInscricao . ' - '.$edital->siglaNivel, 1, 0, 'L', true);
        $pdf->Ln();*/

        //$pdf->Output('F', public_path("pae/comprovante/{$inscricao->numeroInscricao}.pdf"));
        $pdf->Output();
    }
}
