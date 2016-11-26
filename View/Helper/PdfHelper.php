<?php
define('K_PATH_IMAGES', WWW_ROOT.'img/');

App::import('Vendor', 'TCPDF.tcpdf_config',array('file' => 'tcpdf/config/tcpdf_config.php'));
App::import('Vendor', 'TCPDF.tcpdf',array('file' => 'tcpdf/tcpdf.php'));

class PdfHelper extends AppHelper {
  public $helpers = array('Html');

  public $core = null;

  private $options = array(
    'format'=>'default',  // default, memo, none
    'header'=>array(
      'logo'=> 'cake.icon.png',  //PDF_HEADER_LOGO,
      'logo_width' => 10,
      'title' => PDF_HEADER_TITLE,
      'string' => PDF_HEADER_STRING,
    ),
    'footer'=>array(
    ),
    'pdf'=>array(
      'title' => PDF_CREATOR,
      'page_orientation' => PDF_PAGE_ORIENTATION,
      'unit' => PDF_UNIT,
      'page_format' => PDF_PAGE_FORMAT,
      'creator' => PDF_CREATOR,
      'font_name_main' => PDF_FONT_NAME_MAIN,
      'font_size_main' => PDF_FONT_SIZE_MAIN,
      'font_name_data' => PDF_FONT_NAME_DATA,
      'font_size_data' => PDF_FONT_SIZE_DATA,
      'font_monospaced' => PDF_FONT_MONOSPACED,
      'margin_left' => PDF_MARGIN_LEFT,
      'margin_top' => PDF_MARGIN_TOP,
      'margin_right' => PDF_MARGIN_RIGHT,
      'margin_header' => PDF_MARGIN_HEADER,
      'margin_footer' => PDF_MARGIN_FOOTER,
      'margin_bottom' => PDF_MARGIN_BOTTOM,
      'image_scale_ratio' => PDF_IMAGE_SCALE_RATIO,
    )
  );

  public function setOptions($options){
    $this->options = array_replace_recursive($this->options, $options);
  }

  public function setCore($format = 'default'){
    $obj=null;
    if($format == 'default'){
      $obj = new TypeDefault(
        $this->options['pdf']['page_orientation'],
        $this->options['pdf']['unit'],
        $this->options['pdf']['page_format'],
        true,
        'UTF-8',
        false
      );
    }elseif($format == 'memo'){
      $obj = new TypeMemo(
        $this->options['pdf']['page_orientation'],
        $this->options['pdf']['unit'],
        $this->options['pdf']['page_format'],
        true,
        'UTF-8',
        false
      );
    }else{
      $obj = new TCPDF(
        $this->options['pdf']['page_orientation'],
        $this->options['pdf']['unit'],
        $this->options['pdf']['page_format'],
        true,
        'UTF-8',
        false
      );
    }
    return $obj;
  }

	public function init( $options = array() ){
		$this->setOptions($options);

		$this->core = $this->setCore($this->options['format']);

		if($this->options['format'] == 'none'){
			$this->core->setPrintHeader(false);
			$this->core->setPrintFooter(false);
		}

		$this->core->SetCreator($this->options['pdf']['creator']);
		$this->core->SetAuthor('');
		$this->core->SetTitle($this->options['pdf']['title']);
		$this->core->SetSubject(''); //$this->core->SetSubject('TCPDF Tutorial');
		$this->core->SetKeywords(''); //$this->core->SetKeywords('TCPDF, PDF, example, test, guide');

		$this->core->url = $this->Html->url('/',true);

		// set default header data
		//$this->core->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
		$this->core->SetHeaderData(
			$this->options['header']['logo'],
			$this->options['header']['logo_width'],
			$this->options['header']['title'],
			$this->options['header']['string']
		);

		// set header and footer fonts
		$this->core->setHeaderFont(array($this->options['pdf']['font_name_main'], '', $this->options['pdf']['font_size_main']));
		$this->core->setFooterFont(array($this->options['pdf']['font_name_data'], '', $this->options['pdf']['font_size_data']));

		// set default monospaced font
		$this->core->SetDefaultMonospacedFont($this->options['pdf']['font_monospaced']);

		// set margins
		$this->core->SetMargins($this->options['pdf']['margin_left'], $this->options['pdf']['margin_top'], $this->options['pdf']['margin_right']);
		$this->core->SetHeaderMargin($this->options['pdf']['margin_header']);
		$this->core->SetFooterMargin($this->options['pdf']['margin_footer']);

		// set auto page breaks
		$this->core->SetAutoPageBreak(TRUE, $this->options['pdf']['margin_bottom']);

		// set image scale factor
		$this->core->setImageScale($this->options['pdf']['image_scale_ratio']);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$this->core->setLanguageArray($l);
		}
	}

  //public function __call($name, $arguments){
  //  return call_user_func_array(array(&$this->core, $name), $arguments);
  //}
  
  public function __call($name, $arguments) {
    if ($this->core) return call_user_func_array(array(&$this->core, $name), $arguments);
    return false;
  }
  
  public function SetFont($family, $style='', $size=null, $fontfile='', $subset='default', $out=true) {
    $this->core->SetFont($family, $style, $size, $fontfile, $subset, $out);
  }

  public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false) {
    $this->core->AddPage($orientation, $format, $keepmargins, $tocpage);
  }

  public function lastPage($resetmargins=false) {
    $this->core->lastPage($resetmargins);
  }

  public function Output($name='doc.pdf', $dest='I') {
    $this->core->Output($name, $dest);
  }

  public function writeHTML($html, $ln=true, $fill=false, $reseth=true, $cell=false, $align='') {
    $this->core->writeHTML($html, $ln, $fill, $reseth, $cell, $align);
  }

  public function SetMargins($left, $top, $right=-1, $keepmargins=false) {
    $this->core->SetMargins($left, $top,$right,$keepmargins);
  }
  /**/
}


class TypeDefault extends TCPDF{
	public function Header() {

		if ($this->header_xobjid === false) {
			// start a new XObject Template
			$this->header_xobjid = $this->startTemplate($this->w, $this->tMargin);
			$headerfont = $this->getHeaderFont();
			$headerdata = $this->getHeaderData();

			$this->y = $this->header_margin;
			if ($this->rtl) {
				$this->x = $this->w - $this->original_rMargin;
			} else {
				$this->x = $this->original_lMargin;
			}

			if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
				$imgtype = TCPDF_IMAGES::getImageFileType(K_PATH_IMAGES.$headerdata['logo']);
				if (($imgtype == 'eps') OR ($imgtype == 'ai')) {
					$this->ImageEps(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				} elseif ($imgtype == 'svg') {
					$this->ImageSVG(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				} else {
					$this->Image(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				}
				$imgy = $this->getImageRBY();
			} else {
				$imgy = $this->y;
			}

			$cell_height = $this->getCellHeight($headerfont[2] / $this->k);
			// set starting margin for text data cell
			if ($this->getRTL()) {
				$header_x = $this->original_rMargin + ($headerdata['logo_width'] * 1.1);
			} else {
				$header_x = $this->original_lMargin + ($headerdata['logo_width'] * 1.1);
			}
			$cw = $this->w - $this->original_lMargin - $this->original_rMargin - ($headerdata['logo_width'] * 1.1);
			$this->SetTextColorArray($this->header_text_color);
			// header title
			$this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
			$this->SetX($header_x);
			$this->Cell($cw, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
			// header string
			$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
			$this->SetX($header_x);
			$this->MultiCell($cw, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false, true, 0, 'T', false);

			// print an ending header line
			$this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $headerdata['line_color']));


			$this->SetY((2.835 / $this->k) + max($imgy, $this->y));
			if ($this->rtl) {
				$this->SetX($this->original_rMargin);
			} else {
				$this->SetX($this->original_lMargin);
			}
			$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
			$this->endTemplate();
		}
		// print header template
		$x = 0;
		$dx = 0;
		if (!$this->header_xobj_autoreset AND $this->booklet AND (($this->page % 2) == 0)) {
			// adjust margins for booklet mode
			$dx = ($this->original_lMargin - $this->original_rMargin);
		}
		if ($this->rtl) {
			$x = $this->w + $dx;
		} else {
			$x = 0 + $dx;
		}
		$this->printTemplate($this->header_xobjid, $x, 0, 0, 0, '', '', false);
		if ($this->header_xobj_autoreset) {
			// reset header xobject template at each page
			$this->header_xobjid = false;
		}

		$barcode = $this->getBarcode();
		if (!empty($barcode)) {
			//$this->Ln($line_width);
			$barcode_width = round(($this->w - $this->original_lMargin - $this->original_rMargin) / 3);
			$style = array(
				'border' => 0,
				'padding' => '8',
				'fgcolor' => array(0,0,0),
				'bgcolor' => array(255,255,255), //array(255,255,255)
				'module_width' => 1, // width of a single module in points
				'module_height' => 1 // height of a single module in points
			);
			$this->write2DBarcode($barcode, 'QRCODE,L', 175, 3, 30, 30, $style, 'N');
			//$pdf->Text(140, 205, 'QRCODE H - NO PADDING');
			//$this->write1DBarcode($barcode, 'C128', '', $cur_y + $line_width, '', (($this->footer_margin / 3) - $line_width), 0.3, $style, '');
		} /**/
	}

	/**
	 * This method is used to render the page footer.
	 * It is automatically called by AddPage() and could be overwritten in your own inherited class.
	 * @public
	 */
	public function Footer() {
		$cur_y = $this->y;
		$this->SetTextColorArray($this->footer_text_color);
		//set style for cell border
		$line_width = (0.85 / $this->k);
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $this->footer_line_color));

		/*
		//print document barcode
		$barcode = $this->getBarcode();
		if (!empty($barcode)) {
			$this->Ln($line_width);
			$barcode_width = round(($this->w - $this->original_lMargin - $this->original_rMargin) / 3);
			$style = array(
				'position' => $this->rtl?'R':'L',
				'align' => $this->rtl?'R':'L',
				'stretch' => false,
				'fitwidth' => true,
				'cellfitalign' => '',
				'border' => false,
				'padding' => 0,
				'fgcolor' => array(0,0,0),
				'bgcolor' => false,
				'text' => false
			);
			$this->write1DBarcode($barcode, 'C128', '', $cur_y + $line_width, '', (($this->footer_margin / 3) - $line_width), 0.3, $style, '');
		} */

		if (!empty($this->url)) {
			$this->Cell(0, 0, $this->url, 'T', 0, 'L');
		}

		$w_page = isset($this->l['w_page']) ? $this->l['w_page'].' ' : '';
		if (empty($this->pagegroups)) {
			$pagenumtxt = $w_page.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
		} else {
			$pagenumtxt = $w_page.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
		}
		$this->SetY($cur_y);
		//Print page number
		if ($this->getRTL()) {
			$this->SetX($this->original_rMargin);
			$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
		} else {
			$this->SetX($this->original_lMargin);
			$this->Cell(0, 0, $this->getAliasRightShift().$pagenumtxt, 'T', 0, 'R');
		}
	}
}


class TypeMemo extends TCPDF {
  public function Header() {
    $headerdata = $this->getHeaderData();
    $this->Image(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
    $this->Cell(0, 0, $headerdata['title'], 0, 1, 'C', 0, '', 0);
    $strings = explode("\n",$headerdata['string']);
    foreach ($strings as $string) {
      $this->Cell(0, 0, $string, 0, 1, 'C', 0, '', 0);
    }
  }
  
  public function Footer() {
  }
}


?>
