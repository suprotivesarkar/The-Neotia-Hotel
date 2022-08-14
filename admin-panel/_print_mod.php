<?php
class PDF extends FPDF
{	
	function Header() 
	{	 
	    //if ( $this->PageNo() == 1 ) {  
			$this->AddFont('Calibri-Bold','','CALIBRIB.php');
			$this->AddFont('Calibri','','Calibri.php');

			$this->AddFont('Cambria-Bold','','cambriab.php');
			$this->AddFont('Cambria','','Cambria.php');

			$this->Image('assets/images/logo.png',9,10,35);
			$this->SetTextColor(253,53,54);
			$this->SetFont('Cambria-Bold','',28); 
			$this->Cell(35);
			$headtxt = "<font color='#c5830a'>" . "Hotel The Grand Chandiram" . " </font><font color='#c5830a'>". ""."</font>";
			$this->MultiCell(0,0, $this->WriteHTML($headtxt), 0, 'C');
		    //$this->Cell(50,0,"The Travelers Club",0,0,'L');
		    $this->Ln(4);
		    $this->Cell(35);
		    $this->SetTextColor(0,0,0);
		    $this->SetFont('Cambria','',8);
		    $this->Cell( 0, 10, "Hotel The Grand Chandiram, Near LIC Building, Chawani Circle, Jhahalwar Road, Kota - 324007 (Rajasthan), India",0,0,'L');
		    $this->Ln(5);
		    $this->Cell(35);
		    $this->Cell(0,10,"Email: thegrandchandiram@gmail.com, www.hotelthegrandchandiram.com, Mob: +91 9414188006, Gst No: 08AAEFC9557E1ZA",0,0,'L');
		    $this->Line(10,28,200,28);
		    $this->Ln(12);
		//}
	} 

	function Footer()
	{
		$this->SetY(-15);
	    $this->AddFont('Cambria-Bold','','cambriab.php');
	    $this->AddFont('Cambria','','Cambria.php');
		$this->SetFont('Cambria-Bold','',8);
		$this->SetTextColor(0, 0, 0);
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function MultiCelly($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline)
    {
        //Output text with automatic or explicit line breaks, at most $maxline lines
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border,'L')))
                    $b2.='L';
                if(is_int(strpos($border,'R')))
                    $b2.='R';
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if($maxline && $nl>$maxline)
                    return substr($s,$i);
                continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        $this->_out('0 Tw');
                    }
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if($maxline && $nl>$maxline)
                {
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        $this->_out('0 Tw');
                    }
                    return substr($s,$i);
                }
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            $this->_out('0 Tw');
        }
        if($border && is_int(strpos($border,'B')))
            $b.='B';
        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
        $this->x=$this->lMargin;
        return '';
    }



	function WordWrap(&$text, $maxwidth)
	{
	    $text = trim($text);
	    if ($text==='')
	        return 0;
	    $space = $this->GetStringWidth(' ');
	    $lines = explode("\n", $text);
	    $text = '';
	    $count = 0;

	    foreach ($lines as $line)
	    {
	        $words = preg_split('/ +/', $line);
	        $width = 0;

	        foreach ($words as $word)
	        {
	            $wordwidth = $this->GetStringWidth($word);
	            if ($wordwidth > $maxwidth)
	            {
	                // Word is too long, we cut it
	                for($i=0; $i<strlen($word); $i++)
	                {
	                    $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
	                    if($width + $wordwidth <= $maxwidth)
	                    {
	                        $width += $wordwidth;
	                        $text .= substr($word, $i, 1);
	                    }
	                    else
	                    {
	                        $width = $wordwidth;
	                        $text = rtrim($text)."\n".substr($word, $i, 1);
	                        $count++;
	                    }
	                }
	            }
	            elseif($width + $wordwidth <= $maxwidth)
	            {
	                $width += $wordwidth + $space;
	                $text .= $word.' ';
	            }
	            else
	            {
	                $width = $wordwidth + $space;
	                $text = rtrim($text)."\n".$word.' ';
	                $count++;
	            }
	        }
	        $text = rtrim($text)."\n";
	        $count++;
	    }
	    $text = rtrim($text);
	    return $count;
	}


	function Justify($text, $w, $h)
	{
	    $tab_paragraphe = explode("\n", $text);
	    $nb_paragraphe = count($tab_paragraphe);
	    $j = 0;

	    while ($j<$nb_paragraphe) {
	        $paragraphe = $tab_paragraphe[$j];
	        $tab_mot = explode(' ', $paragraphe);
	        $nb_mot = count($tab_mot);

	        // Handle strings longer than paragraph width
	        $k=0;
	        $l=0;
	        while ($k<$nb_mot) {

	            $len_mot = strlen ($tab_mot[$k]);
	            if ($len_mot<($w-5) )
	            {
	                $tab_mot2[$l] = $tab_mot[$k];
	                $l++;    
	            } else {
	                $m=0;
	                $chaine_lettre='';
	                while ($m<$len_mot) {

	                    $lettre = substr($tab_mot[$k], $m, 1);
	                    $len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);

	                    if ($len_chaine_lettre>($w-7)) {
	                        $tab_mot2[$l] = $chaine_lettre . '-';
	                        $chaine_lettre = $lettre;
	                        $l++;
	                    } else {
	                        $chaine_lettre .= $lettre;
	                    }
	                    $m++;
	                }
	                if ($chaine_lettre) {
	                    $tab_mot2[$l] = $chaine_lettre;
	                    $l++;
	                }

	            }
	            $k++;
	        }

	        // Justified lines
	        $nb_mot = count($tab_mot2);
	        $i=0;
	        $ligne = '';
	        while ($i<$nb_mot) {

	            $mot = $tab_mot2[$i];
	            $len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

	            if ($len_ligne>($w-5)) {

	                $len_ligne = $this->GetStringWidth($ligne);
	                $nb_carac = strlen ($ligne);
	                $ecart = (($w-2) - $len_ligne) / $nb_carac;
	                $this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
	                $this->MultiCell($w,$h,$ligne);
	                $ligne = $mot;

	            } else {

	                if ($ligne)
	                {
	                    $ligne .= ' ' . $mot;
	                } else {
	                    $ligne = $mot;
	                }

	            }
	            $i++;
	        }

	        // Last line
	        $this->_out('BT 0 Tc ET');
	        $this->MultiCell($w,$h,$ligne);
	        $tab_mot = '';
	        $tab_mot2 = '';
	        $j++;
	    }
	}
	
	function MultiCellWithIndent($w, $h, $txt, $border=0, $align='J', $fill=false, $indent=0)
	{
	    //Output text with automatic or explicit line breaks
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;

	    $wFirst = $w-$indent;
	    $wOther = $w;

	    $wmaxFirst=($wFirst-2*$this->cMargin)*1000/$this->FontSize;
	    $wmaxOther=($wOther-2*$this->cMargin)*1000/$this->FontSize;

	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 && $s[$nb-1]=="\n")
	        $nb--;
	    $b=0;
	    if($border)
	    {
	        if($border==1)
	        {
	            $border='LTRB';
	            $b='LRT';
	            $b2='LR';
	        }
	        else
	        {
	            $b2='';
	            if(is_int(strpos($border,'L')))
	                $b2.='L';
	            if(is_int(strpos($border,'R')))
	                $b2.='R';
	            $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
	        }
	    }
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $ns=0;
	    $nl=1;
	        $first=true;
	    while($i<$nb)
	    {
	        //Get next character
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            //Explicit line break
	            if($this->ws>0)
	            {
	                $this->ws=0;
	                $this->_out('0 Tw');
	            }
	            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $ns=0;
	            $nl++;
	            if($border && $nl==2)
	                $b=$b2;
	            continue;
	        }
	        if($c==' ')
	        {
	            $sep=$i;
	            $ls=$l;
	            $ns++;
	        }
	        $l+=$cw[$c];

	        if ($first)
	        {
	            $wmax = $wmaxFirst;
	            $w = $wFirst;
	        }
	        else
	        {
	            $wmax = $wmaxOther;
	            $w = $wOther;
	        }

	        if($l>$wmax)
	        {
	            //Automatic line break
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	                if($this->ws>0)
	                {
	                    $this->ws=0;
	                    $this->_out('0 Tw');
	                }
	                $SaveX = $this->x; 
	                if ($first && $indent>0)
	                {
	                    $this->SetX($this->x + $indent);
	                    $first=false;
	                }
	                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
	                    $this->SetX($SaveX);
	            }
	            else
	            {
	                if($align=='J')
	                {
	                    $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
	                    $this->_out(sprintf('%.3f Tw',$this->ws*$this->k));
	                }
	                $SaveX = $this->x; 
	                if ($first && $indent>0)
	                {
	                    $this->SetX($this->x + $indent);
	                    $first=false;
	                }
	                $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
	                    $this->SetX($SaveX);
	                $i=$sep+1;
	            }
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $ns=0;
	            $nl++;
	            if($border && $nl==2)
	                $b=$b2;
	        }
	        else
	            $i++;
	    }
	    //Last chunk
	    if($this->ws>0)
	    {
	        $this->ws=0;
	        $this->_out('0 Tw');
	    }
	    if($border && is_int(strpos($border,'B')))
	        $b.='B';
	    $this->Cell($w,$h,substr($s,$j,$i),$b,2,$align,$fill);
	    $this->x=$this->lMargin;
	}

	var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';
    var $issetfont=false;
    var $issetcolor=false;
    var $fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');

    function WriteHTML($html)
    {
        //HTML parser
        $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>");
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                else
                    $this->Write(5,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }
    function hex2dec($couleur = "#000000"){
	    $R = substr($couleur, 1, 2);
	    $rouge = hexdec($R);
	    $V = substr($couleur, 3, 2);
	    $vert = hexdec($V);
	    $B = substr($couleur, 5, 2);
	    $bleu = hexdec($B);
	    $tbl_couleur = array();
	    $tbl_couleur['R']=$rouge;
	    $tbl_couleur['V']=$vert;
	    $tbl_couleur['B']=$bleu;
	    return $tbl_couleur;
	}
	function px2mm($px){
	    return $px*25.4/72;
	}
	function txtentities($html){
	    $trans = get_html_translation_table(HTML_ENTITIES);
	    $trans = array_flip($trans);
	    return strtr($html, $trans);
	}

    function OpenTag($tag,$prop)
    {
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
        if($tag=='FONT')
        {
            if (isset($prop['COLOR']) && $prop['COLOR']!='') {
                $coul=$this->hex2dec($prop['COLOR']);
                $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                $this->issetcolor=true;
            }
            if (isset($prop['FACE']) && in_array(strtolower($prop['FACE']), $this->fontlist)) {
                $this->SetFont(strtolower($prop['FACE']));
                $this->issetfont=true;
            }
        }
        if($tag=='IMG'){
            if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;
                $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
            }
       }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
        if($tag=='FONT'){
	        if ($this->issetcolor==true) {
	            $this->SetTextColor(0);
	        }
	        if ($this->issetfont) {
	            $this->SetFont('arial');
	            $this->issetfont=false;
	        }
	    }
    }

    function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }


    function subWrite($h, $txt, $link='',$subFontSize=12,$subOffset=0)
	{
	    // resize font
	    $subFontSizeold = $this->FontSizePt;
	    $this->SetFontSize($subFontSize);
	    
	    // reposition y
	    $subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
	    $subX        = $this->x;
	    $subY        = $this->y;
	    $this->SetXY($subX, $subY - $subOffset);

	    //Output text
	    $this->Write($h, $txt, $link);

	    // restore y position
	    $subX        = $this->x;
	    $subY        = $this->y;
	    $this->SetXY($subX,  $subY + $subOffset);

	    // restore font size
	    $this->SetFontSize($subFontSizeold);
	}



    function MultiCellBltArray($w, $h, $blt_array, $border=0, $align='J', $fill=false)
    {
        if (!is_array($blt_array))
        {
            die('MultiCellBltArray requires an array with the following keys: bullet,margin,text,indent,spacer');
            exit;
        }
                
        $bak_x = $this->x;
        
        for ($i=0; $i<sizeof($blt_array['text']); $i++)
        {
            $blt_width = $this->GetStringWidth($blt_array['bullet'] . $blt_array['margin'])+$this->cMargin*2;
            
            $this->SetX($bak_x);
            
            if ($blt_array['indent'] > 0)
                $this->Cell($blt_array['indent']);
            
            $this->Cell($blt_width,$h,$blt_array['bullet'] . $blt_array['margin'],0,'',$fill);
            
            $this->MultiCell($w-$blt_width,$h,$blt_array['text'][$i],$border,$align,$fill);
            
            if ($i != sizeof($blt_array['text'])-1)
                $this->Ln($blt_array['spacer']);
            
            if (is_numeric($blt_array['bullet']))
                $blt_array['bullet']++;
        }
        $this->x = $bak_x;
    }

    function RC4($key, $data)
    {
        return openssl_encrypt($data, 'RC4-40', $key, OPENSSL_RAW_DATA);
    }
    protected $encrypted = false;  //whether document is protected
    protected $Uvalue;             //U entry in pdf document
    protected $Ovalue;             //O entry in pdf document
    protected $Pvalue;             //P entry in pdf document
    protected $enc_obj_id;         //encryption object id
    function SetProtection($permissions=array(), $user_pass='', $owner_pass=null)
    {
        $options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32 );
        $protection = 192;
        foreach($permissions as $permission)
        {
            if (!isset($options[$permission]))
                $this->Error('Incorrect permission: '.$permission);
            $protection += $options[$permission];
        }
        if ($owner_pass === null)
            $owner_pass = uniqid(rand());
        $this->encrypted = true;
        $this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08".
                        "\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
        $this->_generateencryptionkey($user_pass, $owner_pass, $protection);
    }

    function _putstream($s)
    {
        if ($this->encrypted)
            $s = $this->RC4($this->_objectkey($this->n), $s);
        parent::_putstream($s);
    }

    function _textstring($s)
    {
        if (!$this->_isascii($s))
            $s = $this->_UTF8toUTF16($s);
        if ($this->encrypted)
            $s = $this->RC4($this->_objectkey($this->n), $s);
        return '('.$this->_escape($s).')';
    }
    function _objectkey($n)
    {
        return substr($this->_md5_16($this->encryption_key.pack('VXxx',$n)),0,10);
    }
    function _putresources()
    {
        parent::_putresources();
        if ($this->encrypted) {
            $this->_newobj();
            $this->enc_obj_id = $this->n;
            $this->_put('<<');
            $this->_putencryption();
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    function _putencryption()
    {
        $this->_put('/Filter /Standard');
        $this->_put('/V 1');
        $this->_put('/R 2');
        $this->_put('/O ('.$this->_escape($this->Ovalue).')');
        $this->_put('/U ('.$this->_escape($this->Uvalue).')');
        $this->_put('/P '.$this->Pvalue);
    }

    function _puttrailer()
    {
        parent::_puttrailer();
        if ($this->encrypted) {
            $this->_put('/Encrypt '.$this->enc_obj_id.' 0 R');
            $this->_put('/ID [()()]');
        }
    }
    function _md5_16($string)
    {
        return md5($string, true);
    }
    function _Ovalue($user_pass, $owner_pass)
    {
        $tmp = $this->_md5_16($owner_pass);
        $owner_RC4_key = substr($tmp,0,5);
        return $this->RC4($owner_RC4_key, $user_pass);
    }
    function _Uvalue()
    {
        return $this->RC4($this->encryption_key, $this->padding);
    }
    function _generateencryptionkey($user_pass, $owner_pass, $protection)
    {
        $user_pass = substr($user_pass.$this->padding,0,32);
        $owner_pass = substr($owner_pass.$this->padding,0,32);
        $this->Ovalue = $this->_Ovalue($user_pass,$owner_pass);
        $tmp = $this->_md5_16($user_pass.$this->Ovalue.chr($protection)."\xFF\xFF\xFF");
        $this->encryption_key = substr($tmp,0,5);
        $this->Uvalue = $this->_Uvalue();
        $this->Pvalue = -(($protection^255)+1);
    }
}
?>