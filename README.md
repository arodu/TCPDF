# TCPDF
TCPDF Plugin for Cakephp 2.x

## Instalation
  http://book.cakephp.org/2.0/en/plugins/how-to-install-plugins.html

## How to use

```php
// app/Controller/AnyController.php
public $helpers = array('TCPDF.Pdf');
```

```php
// app/Controller/AnyController.php
public function print(){
  $this->layout = 'TCPDF.default';
  $this->response->type('application/pdf');
}
```

```php
// app/View/Any/print.ctp
echo '<h1>Hello World!</h1>';
$this->Pdf->setOptions(array(
  // Options
));
```
  
## Options

```php
// * default option
array(
  'format'=>'default',  // *default, memo, none
  'header'=>array(
    'logo'=> 'cake.icon.png',  //any image in webroot/img/,
    'logo_width' => 10, 
    'title' => PDF_HEADER_TITLE, // String
    'string' => PDF_HEADER_STRING, // String
  ),
  'footer'=>array(
  ),
  'pdf'=>array(
    'title' => PDF_CREATOR,
    'page_orientation' => PDF_PAGE_ORIENTATION, // *P=portrait, L=landscape
    'unit' => PDF_UNIT, // pt=point, *mm=millimeter, cm=centimeter, in=inch
    'page_format' => PDF_PAGE_FORMAT, // *A4
    'creator' => PDF_CREATOR,
    'font_name_main' => PDF_FONT_NAME_MAIN, // *helvetica
    'font_size_main' => PDF_FONT_SIZE_MAIN, // *10
    'font_name_data' => PDF_FONT_NAME_DATA, // *helvetica
    'font_size_data' => PDF_FONT_SIZE_DATA, // *8
    'font_monospaced' => PDF_FONT_MONOSPACED, // *courier
    'margin_left' => PDF_MARGIN_LEFT, // *15
    'margin_top' => PDF_MARGIN_TOP, // *27
    'margin_right' => PDF_MARGIN_RIGHT, // *15
    'margin_header' => PDF_MARGIN_HEADER, // *5
    'margin_footer' => PDF_MARGIN_FOOTER, // *10
    'margin_bottom' => PDF_MARGIN_BOTTOM, // *25
    'image_scale_ratio' => PDF_IMAGE_SCALE_RATIO, // *1.25
    'timezone' => K_TIMEZONE, // *UTC
  )
);
```