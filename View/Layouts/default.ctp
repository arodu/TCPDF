<?php
  //error_reporting(false);
  $css = '<style>
    th { text-align: center; font-weight: bold; }
    td { padding-left: 10em; }
    a:link, a:visited { text-decoration: none; color: black; }
    .hidden { visibility: hidden; overflow: hidden; width: 0px; height: 0px; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-justify { text-align: justify; }
    hr { height: 0; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; }
  </style>';
  $css .= $this->fetch('css');
  $html = $this->fetch('content');

  $this->Pdf->init();

  $this->Pdf->SetFont('helvetica', '', 8);
  $this->Pdf->AddPage();
  $this->Pdf->writeHTML($css.$html);
  $this->Pdf->lastPage();
  $this->Pdf->Output($this->fetch('title').'.pdf', 'I');

