<?php
namespace App\Helpers;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;


class Pdf extends Fpdf
{

	protected $widths, $aligns, $lineHeight, $fakultas;

	public function  __construct($fakultas)
	{
			parent::__construct();
			$this->fakultas = $fakultas;
	}

  // Page header
  function Header()
  {
    //Header
    $teksHeader1 = "INSTITUT TEKNOLOGI, SAINS, DAN KESEHATAN RS dr.SOEPRAOEN";
    $teksHeader2 = Str::upper($this->fakultas);
    $this->SetFont('Arial', '', 11);
    $w = $this->GetStringWidth($teksHeader1);
    $this->Cell($w, 4, $teksHeader1, 0, 1, 'C');
    $this->Cell($w, 4, $teksHeader2, 0, 0, 'C');
    $this->Line(10, 19, 135, 19);
    $this->Ln(7);
    $this->Image('./img/anonim.png', 18, 10, 1, 1);
  }

  
	function MultiCellIndent($w, $h, $txt, $border = 0, $align = 'J', $fill = false, $indent = 0)
	{
		//Output text with automatic or explicit line breaks
		$cw = &$this->CurrentFont['cw'];
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;

		$wFirst = $w - $indent;
		$wOther = $w;

		$wmaxFirst = ($wFirst - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$wmaxOther = ($wOther - 2 * $this->cMargin) * 1000 / $this->FontSize;

		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if ($nb > 0 && $s[$nb - 1] == "\n")
			$nb--;
		$b = 0;
		if ($border) {
			if ($border == 1) {
				$border = 'LTRB';
				$b = 'LRT';
				$b2 = 'LR';
			} else {
				$b2 = '';
				if (is_int(strpos($border, 'L')))
					$b2 .= 'L';
				if (is_int(strpos($border, 'R')))
					$b2 .= 'R';
				$b = is_int(strpos($border, 'T')) ? $b2 . 'T' : $b2;
			}
		}
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$ns = 0;
		$nl = 1;
		$first = true;
		while ($i < $nb) {
			//Get next character
			$c = $s[$i];
			if ($c == "\n") {
				//Explicit line break
				if ($this->ws > 0) {
					$this->ws = 0;
					$this->_out('0 Tw');
				}
				$this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$ns = 0;
				$nl++;
				if ($border && $nl == 2)
					$b = $b2;
				continue;
			}
			if ($c == ' ') {
				$sep = $i;
				$ls = $l;
				$ns++;
			}
			$l += $cw[$c];

			if ($first) {
				$wmax = $wmaxFirst;
				$w = $wFirst;
			} else {
				$wmax = $wmaxOther;
				$w = $wOther;
			}

			if ($l > $wmax) {
				//Automatic line break
				if ($sep == -1) {
					if ($i == $j)
						$i++;
					if ($this->ws > 0) {
						$this->ws = 0;
						$this->_out('0 Tw');
					}
					$SaveX = $this->x;
					if ($first && $indent > 0) {
						$this->SetX($this->x + $indent);
						$first = false;
					}
					$this->Cell($w, $h, substr($s, $j, $i - $j), $b, 2, $align, $fill);
					$this->SetX($SaveX);
				} else {
					if ($align == 'J') {
						$this->ws = ($ns > 1) ? ($wmax - $ls) / 1000 * $this->FontSize / ($ns - 1) : 0;
						$this->_out(sprintf('%.3f Tw', $this->ws * $this->k));
					}
					$SaveX = $this->x;
					if ($first && $indent > 0) {
						$this->SetX($this->x + $indent);
						$first = false;
					}
					$this->Cell($w, $h, substr($s, $j, $sep - $j), $b, 2, $align, $fill);
					$this->SetX($SaveX);
					$i = $sep + 1;
				}
				$sep = -1;
				$j = $i;
				$l = 0;
				$ns = 0;
				$nl++;
				if ($border && $nl == 2)
					$b = $b2;
			} else
				$i++;
		}
		//Last chunk
		if ($this->ws > 0) {
			$this->ws = 0;
			$this->_out('0 Tw');
		}
		if ($border && is_int(strpos($border, 'B')))
			$b .= 'B';
		$this->Cell($w, $h, substr($s, $j, $i), $b, 2, $align, $fill);
		$this->x = $this->lMargin;
	}

	// ---------------------------------------  Font Strict   ----------------------------------------------------------------
	
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
							$char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
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

	// -----------------------------------------  Warp Text  --------------------------------------------------------------

	//Set the array of column widths
	function SetWidths($w){
		$this->widths=$w;
	}

	//Set the array of column alignments
	function SetAligns($a){
		$this->aligns=$a;
	}

	//Set line height
	function SetLineHeight($h){
		$this->lineHeight=$h;
	}

	//Calculate the height of the row
	function Row($data)
	{
		// number of line
		$nb=0;

		// loop each data to find out greatest line number in a row.
		for($i=0;$i<count($data);$i++){
				// NbLines will calculate how many lines needed to display text wrapped in specified width.
				// then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		}
		
		//multiply number of line with line height. This will be the height of current row
		$h=$this->lineHeight * $nb;

		//Issue a page break first if needed
		$this->CheckPageBreak($h);

		//Draw the cells of current row
		for($i=0;$i<count($data);$i++)
		{
				// width of the current col
				$w=$this->widths[$i];
				// alignment of the current col. if unset, make it left.
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h);
				//Print the text
				$this->MultiCell($w,5,$data[$i],0,$a);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h-5);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//calculate the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
				$c=$s[$i];
				if($c=="\n")
				{
						$i++;
						$sep=-1;
						$j=$i;
						$l=0;
						$nl++;
						continue;
				}
				if($c==' ')
						$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax)
				{
						if($sep==-1)
						{
								if($i==$j)
										$i++;
						}
						else
								$i=$sep+1;
						$sep=-1;
						$j=$i;
						$l=0;
						$nl++;
				}
				else
						$i++;
		}
		return $nl;
	}
		
				
}
