<?php

namespace ThirdParty\FPDF;

use Exception;
use ThirdParty\FPDF\MakeFont\TTFParser;

require_once dirname(__FILE__).'/makefont/ttfparser.php';

class MakePDFFont
{
    protected $_filename = '';
    protected $_destPath = '';
    protected $_sourceFile = '';

    public function createFontFileSet($fontName, $regularFile = null, $boldFile = null, $italicFile = null, $boldItalicFile = null)
    {
        $ret = array();

        if ($regularFile != null)
            $ret['regular'] = $this->createFontFile($regularFile, $fontName);

        if ($boldFile != null)
            $ret['bold'] = $this->createFontFile($boldFile, $fontName, true);

        if ($italicFile != null)
            $ret['italic'] = $this->createFontFile($italicFile, $fontName, false, true);

        if ($boldItalicFile != null)
            $ret['bold-italic'] = $this->createFontFile($boldItalicFile, $fontName, true, true);

        return $ret;
    }

    public function createFontFile($fontSourceFile, $fontName, $isBold = false, $isItalic = false)
    {
        $this->_sourceFile = $fontSourceFile;
        $this->_filename = $fontName;

        if ($isBold)
            $this->_filename .= 'b';

        if ($isItalic)
            $this->_filename .= 'i';

        $this->MakeFont();

        return $this->_filename.'.php';
    }

    private function Message($txt, $severity='')
    {

    }

    private function Notice($txt)
    {

    }

    private function Warning($txt)
    {

    }

    private function Error($txt)
    {
        throw new Exception($txt);
    }

    private function LoadMap($enc)
    {
        $file = dirname(__FILE__).'/makefont/'.strtolower($enc).'.map';
        $a = file($file);
        if(empty($a))
            $this->Error('Encoding not found: '.$enc);
        $map = array_fill(0, 256, array('uv'=>-1, 'name'=>'.notdef'));
        foreach($a as $line)
        {
            $e = explode(' ', rtrim($line));
            $c = hexdec(substr($e[0],1));
            $uv = hexdec(substr($e[1],2));
            $name = $e[2];
            $map[$c] = array('uv'=>$uv, 'name'=>$name);
        }
        return $map;
    }

    private function GetInfoFromTrueType($file, $embed, $map)
    {
        // Return informations from a TrueType font
        $ttf = new TTFParser();
        $ttf->Parse($file);
        if($embed)
        {
            if(!$ttf->Embeddable)
                $this->Error('Font license does not allow embedding');
            $info['Data'] = file_get_contents($file);
            $info['OriginalSize'] = filesize($file);
        }
        $k = 1000/$ttf->unitsPerEm;
        $info['FontName'] = $ttf->postScriptName;
        $info['Bold'] = $ttf->Bold;
        $info['ItalicAngle'] = $ttf->italicAngle;
        $info['IsFixedPitch'] = $ttf->isFixedPitch;
        $info['Ascender'] = round($k*$ttf->typoAscender);
        $info['Descender'] = round($k*$ttf->typoDescender);
        $info['UnderlineThickness'] = round($k*$ttf->underlineThickness);
        $info['UnderlinePosition'] = round($k*$ttf->underlinePosition);
        $info['FontBBox'] = array(round($k*$ttf->xMin), round($k*$ttf->yMin), round($k*$ttf->xMax), round($k*$ttf->yMax));
        $info['CapHeight'] = round($k*$ttf->capHeight);
        $info['MissingWidth'] = round($k*$ttf->widths[0]);
        $widths = array_fill(0, 256, $info['MissingWidth']);
        for($c=0;$c<=255;$c++)
        {
            if($map[$c]['name']!='.notdef')
            {
                $uv = $map[$c]['uv'];
                if(isset($ttf->chars[$uv]))
                {
                    $w = $ttf->widths[$ttf->chars[$uv]];
                    $widths[$c] = round($k*$w);
                }
                else
                    $this->Warning('Character '.$map[$c]['name'].' is missing');
            }
        }
        $info['Widths'] = $widths;
        return $info;
    }

    private function GetInfoFromType1($file, $embed, $map)
    {
        // Return informations from a Type1 font
        if($embed)
        {
            $f = fopen($file, 'rb');
            if(!$f)
                $this->Error('Can\'t open font file');
            // Read first segment
            $a = unpack('Cmarker/Ctype/Vsize', fread($f,6));
            if($a['marker']!=128)
                $this->Error('Font file is not a valid binary Type1');
            $size1 = $a['size'];
            $data = fread($f, $size1);
            // Read second segment
            $a = unpack('Cmarker/Ctype/Vsize', fread($f,6));
            if($a['marker']!=128)
                $this->Error('Font file is not a valid binary Type1');
            $size2 = $a['size'];
            $data .= fread($f, $size2);
            fclose($f);
            $info['Data'] = $data;
            $info['Size1'] = $size1;
            $info['Size2'] = $size2;
        }

        $afm = substr($file, 0, -3).'afm';
        if(!file_exists($afm))
            $this->Error('AFM font file not found: '.$afm);
        $a = file($afm);
        if(empty($a))
            $this->Error('AFM file empty or not readable');
        foreach($a as $line)
        {
            $e = explode(' ', rtrim($line));
            if(count($e)<2)
                continue;
            $entry = $e[0];
            if($entry=='C')
            {
                $w = $e[4];
                $name = $e[7];
                $cw[$name] = $w;
            }
            elseif($entry=='FontName')
                $info['FontName'] = $e[1];
            elseif($entry=='Weight')
                $info['Weight'] = $e[1];
            elseif($entry=='ItalicAngle')
                $info['ItalicAngle'] = (int)$e[1];
            elseif($entry=='Ascender')
                $info['Ascender'] = (int)$e[1];
            elseif($entry=='Descender')
                $info['Descender'] = (int)$e[1];
            elseif($entry=='UnderlineThickness')
                $info['UnderlineThickness'] = (int)$e[1];
            elseif($entry=='UnderlinePosition')
                $info['UnderlinePosition'] = (int)$e[1];
            elseif($entry=='IsFixedPitch')
                $info['IsFixedPitch'] = ($e[1]=='true');
            elseif($entry=='FontBBox')
                $info['FontBBox'] = array((int)$e[1], (int)$e[2], (int)$e[3], (int)$e[4]);
            elseif($entry=='CapHeight')
                $info['CapHeight'] = (int)$e[1];
            elseif($entry=='StdVW')
                $info['StdVW'] = (int)$e[1];
        }

        if(!isset($info['FontName']))
            $this->Error('FontName missing in AFM file');
        $info['Bold'] = isset($info['Weight']) && preg_match('/bold|black/i', $info['Weight']);
        if(isset($cw['.notdef']))
            $info['MissingWidth'] = $cw['.notdef'];
        else
            $info['MissingWidth'] = 0;
        $widths = array_fill(0, 256, $info['MissingWidth']);
        for($c=0;$c<=255;$c++)
        {
            $name = $map[$c]['name'];
            if($name!='.notdef')
            {
                if(isset($cw[$name]))
                    $widths[$c] = $cw[$name];
                else
                    $this->Warning('Character '.$name.' is missing');
            }
        }
        $info['Widths'] = $widths;
        return $info;
    }

    private function MakeFontDescriptor($info)
    {
        // Ascent
        $fd = "array('Ascent'=>".$info['Ascender'];
        // Descent
        $fd .= ",'Descent'=>".$info['Descender'];
        // CapHeight
        if(!empty($info['CapHeight']))
            $fd .= ",'CapHeight'=>".$info['CapHeight'];
        else
            $fd .= ",'CapHeight'=>".$info['Ascender'];
        // Flags
        $flags = 0;
        if($info['IsFixedPitch'])
            $flags += 1<<0;
        $flags += 1<<5;
        if($info['ItalicAngle']!=0)
            $flags += 1<<6;
        $fd .= ",'Flags'=>".$flags;
        // FontBBox
        $fbb = $info['FontBBox'];
        $fd .= ",'FontBBox'=>'[".$fbb[0].' '.$fbb[1].' '.$fbb[2].' '.$fbb[3]."]'";
        // ItalicAngle
        $fd .= ",'ItalicAngle'=>".$info['ItalicAngle'];
        // StemV
        if(isset($info['StdVW']))
            $stemv = $info['StdVW'];
        elseif($info['Bold'])
            $stemv = 120;
        else
            $stemv = 70;
        $fd .= ",'StemV'=>".$stemv;
        // MissingWidth
        $fd .= ",'MissingWidth'=>".$info['MissingWidth'].')';
        return $fd;
    }

    private function MakeWidthArray($widths)
    {
        $s = "array(\n\t";
        for($c=0;$c<=255;$c++)
        {
            if(chr($c)=="'")
                $s .= "'\\''";
            elseif(chr($c)=="\\")
                $s .= "'\\\\'";
            elseif($c>=32 && $c<=126)
                $s .= "'".chr($c)."'";
            else
                $s .= "chr($c)";
            $s .= '=>'.$widths[$c];
            if($c<255)
                $s .= ',';
            if(($c+1)%22==0)
                $s .= "\n\t";
        }
        $s .= ')';
        return $s;
    }

    private function MakeFontEncoding($map)
    {
        // Build differences from reference encoding
        $ref = $this->LoadMap('cp1252');
        $s = '';
        $last = 0;
        for($c=32;$c<=255;$c++)
        {
            if($map[$c]['name']!=$ref[$c]['name'])
            {
                if($c!=$last+1)
                    $s .= $c.' ';
                $last = $c;
                $s .= '/'.$map[$c]['name'].' ';
            }
        }
        return rtrim($s);
    }

    private function SaveToFile($file, $s, $mode)
    {
        $f = fopen($file, 'w'.$mode);
        if(!$f)
            $this->Error('Can\'t write to file '.$file);
        fwrite($f, $s, strlen($s));
        fclose($f);
    }

    private function MakeDefinitionFile($file, $type, $enc, $embed, $map, $info)
    {
        $s = "<?php\n";
        $s .= '$type = \''.$type."';\n";
        $s .= '$name = \''.$info['FontName']."';\n";
        $s .= '$desc = '.$this->MakeFontDescriptor($info).";\n";
        $s .= '$up = '.$info['UnderlinePosition'].";\n";
        $s .= '$ut = '.$info['UnderlineThickness'].";\n";
        $s .= '$cw = '.$this->MakeWidthArray($info['Widths']).";\n";
        $s .= '$enc = \''.$enc."';\n";
        $diff = $this->MakeFontEncoding($map);
        if($diff)
            $s .= '$diff = \''.$diff."';\n";
        if($embed)
        {
            $s .= '$file = \''.$info['File']."';\n";
            if($type=='Type1')
            {
                $s .= '$size1 = '.$info['Size1'].";\n";
                $s .= '$size2 = '.$info['Size2'].";\n";
            }
            else
                $s .= '$originalsize = '.$info['OriginalSize'].";\n";
        }
        $s .= "?>\n";
        $this->SaveToFile($file, $s, 't');
    }

    private function MakeFont($enc='cp1252', $embed=true)
    {
        $fontfile = $this->_sourceFile;
        // Generate a font definition file
        if(get_magic_quotes_runtime())
            @set_magic_quotes_runtime(0);
        ini_set('auto_detect_line_endings', '1');

        if(!file_exists($fontfile))
            $this->Error('Font file not found: '.$fontfile);
        $ext = strtolower(substr($fontfile,-3));
        $type = '';
        if($ext=='ttf' || $ext=='otf')
            $type = 'TrueType';
        elseif($ext=='pfb')
            $type = 'Type1';
        else
            $this->Error('Unrecognized font file extension: '.$ext);

        $map = $this->LoadMap($enc);

        if($type=='TrueType')
            $info = $this->GetInfoFromTrueType($fontfile, $embed, $map);
        else
            $info = $this->GetInfoFromType1($fontfile, $embed, $map);

        $basename = dirname(__FILE__).'/font/'.$this->_filename;
        if($embed)
        {
            if(function_exists('gzcompress'))
            {
                $file = $basename.'.z';
                $this->SaveToFile($file, gzcompress($info['Data']), 'b');
                $info['File'] = basename($file);
                $this->Message('Font file compressed: '.$file);
            }
            else
            {
                $info['File'] = basename($fontfile);
                $this->Notice('Font file could not be compressed (zlib extension not available)');
            }
        }

        $file = $basename.'.php';
        $this->MakeDefinitionFile($file, $type, $enc, $embed, $map, $info);
        $this->Message('Font definition file generated: '.$basename.'.php');
    }
}