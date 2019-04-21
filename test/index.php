<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 4/21/19
 * Time: 9:35 PM
 */

require_once '../vendor/autoload.php';

$converter = new \DedeGunawan\PdfConverterClient\Converter();
\DedeGunawan\PdfConverterClient\Converter::setApiUrl('https://pdf-converter.cioray.tech/');
\DedeGunawan\PdfConverterClient\Converter::setApiKey('yourapikey');
\DedeGunawan\PdfConverterClient\Converter::setSecretKey('yoursecretkey');
$converter->setFile('test.docx');
$converter->convert();

$converter->showPdf();

