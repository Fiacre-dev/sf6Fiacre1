<?php

namespace App\service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private  $domPdf;

    public function __construct()
    {
        $this->domPdf = new DomPdf();

        $pdfOptions = new Options();
        $pdfOptions ->set('defaultFont','Garamond');

        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html){
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream( "details.pdf",[
            'Attachement'=> false
        ]);

    }
    public function generateBinaryPDF($html){
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->output();
    }
}