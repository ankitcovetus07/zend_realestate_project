<?php

class Report_PdfController extends Zend_Controller_Action 
{

    public function indexAction() 
    {
        require_once(APPLICATION_PATH.'/tcpdf/config/lang/eng.php');
        require_once(APPLICATION_PATH.'/tcpdf/tcpdf.php');
        
        $id = $this->getRequest()->getParam('id');
        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');
        $payment = new Application_Model_Payment();
        $paymentData = $payment->getPaymentDetails($id, $fromDate, $toDate);
        $this->view->paymentData = $paymentData;
        
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(0, PDF_MARGIN_TOP, 0,0);
        $pdf->setPrintHeader(false);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, 0);
        
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 8, '', true);
        
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
        
        ob_start();
        $pdf->AddPage();
        
        $pdf->writeHTMLCell(0, 0, '', '', $this->view->render('pdf/index.phtml'));
        ob_end_flush();
        $pdf->Output('report.pdf', 'D');
        die();
    }

}

