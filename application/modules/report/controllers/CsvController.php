<?php

error_reporting(E_ALL);

require_once APPLICATION_PATH . '/../library/Classes/PHPExcel.php';

class Report_CsvController extends Zend_Controller_Action 
{

    public function indexAction() 
    {
        ini_set('max_execution_time', 300);
        $id = $this->getRequest()->getParam('id');
        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');
        $payment = new Application_Model_Payment();
        $paymentData = $payment->getPaymentDetails($id, $fromDate, $toDate); 
       
        $results = array(); 
        $i = 5;
        $prevSuitNumber = 0;
        $amount = 0;
        $returned = 0;
        $totalTarionWithdraw = 0;
        $totalRegularWithdraw = 0;
        $totalTarion = 0;
        $totalRegular  = 0;
        $upgradeTotal  = 0;
        $upgradeWithdrawTotal  = 0;
        $upgradeReturnedTotal = 0;
        foreach($paymentData as $payment){
            $data = array();
            if($prevSuitNumber != $payment['suit_number']){
                if($i != 5){
                    $data['K' . $i] =  number_format($amount, 2, '.', ',');
                    $data['N' . $i] =  number_format($returned, 2, '.', ',');
                    $data['P' . $i] =  number_format($totalTarion - $totalTarionWithdraw, 2, '.', ',');
                    $data['R' . $i] =  number_format($totalRegular - $totalRegularWithdraw, 2, '.', ',');
                    $data['S' . $i] =  number_format($amount - $returned - $totalTarionWithdraw - $totalRegularWithdraw, 2, '.', ',');
                    $data['U' . $i] =  number_format($upgradeTotal, 2, '.', ',');
                    $data['X' . $i] =  number_format($upgradeReturnedTotal, 2, '.', ',');
                    $data['Z' . $i] =  number_format($upgradeTotal - $upgradeWithdrawTotal, 2, '.', ',');
                    $data['AA' . $i] =  number_format($upgradeTotal - $upgradeReturnedTotal - $upgradeWithdrawTotal, 2, '.', ',');
                    $results[] = $data;
                    $i++;
                    $amount = 0;
                    $returned = 0;
                    $totalTarionWithdraw = 0;
                    $totalRegularWithdraw = 0;
                    $totalTarion = 0;
                    $totalRegular  = 0;
                    $upgradeTotal  = 0;
                    $upgradeWithdrawTotal  = 0;
                    $upgradeReturnedTotal = 0;
                }
                
                $data['A' . $i] = $payment['suit_number'];
                $data['B' . $i] = $payment['suit_unit_number'];
                $data['C' . $i] = $payment['suit_level'];
                $data['D' . $i] = date('d-m-Y',strtotime($payment['purchase_date']));
                $data['E' . $i] = '';
                $data['F' . $i] = $payment['last_name'];
                $data['G' . $i] = $payment['first_name'];
                $data['H' . $i] = $payment['purchase_price'];
            }
            
            $data['I' . $i] = $payment['cheque_number'];
            if($payment['type'] == 'payment' && ($payment['payment_type'] == 'purchase' || $payment['payment_type'] == 'locker' || $payment['payment_type'] == 'parking')){                            
                $data['J' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $amount += $payment['amount'];
            }
            if($payment['status'] == 'NSF' && ($payment['payment_type'] == 'purchase' || $payment['payment_type'] == 'locker' || $payment['payment_type'] == 'parking')){
                $data['L' . $i] =  number_format($payment['NSF_fee'], 2, '.', ',');
                $data['M' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $returned += $payment['amount'] + $payment['NSF_fee'];
            }
            if($payment['type'] == 'withdraw' && $payment['widthdraw_type'] == 'tarion'){
                $data['O' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $totalTarionWithdraw += $payment['amount'];
            }
            if($payment['type'] == 'withdraw' && $payment['widthdraw_type'] == 'regular'){
                $data['Q' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $totalRegularWithdraw += $payment['amount'];
            }
            if($payment['type'] == 'payment' && $payment['payment_type'] == 'upgrade'){
                $data['T' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $upgradeTotal += $payment['amount'];
            }
            if($payment['type'] == 'payment' && $payment['payment_type'] == 'upgrade' && $payment['status'] == 'NSF'){
                $data['V' . $i] =  number_format($payment['NSF_fee'], 2, '.', ',');
                $data['W' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $upgradeReturnedTotal += $payment['amount'] + $payment['NSF_fee'];
            }
            if($payment['type'] == 'withdraw' && $payment['widthdraw_type'] == 'upgrade'){
                $data['Y' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $upgradeWithdrawTotal += $payment['amount'];
            }
            if ($payment['type'] == 'payment') {
                $totalTarion += $payment['tarion_amount'];
            }
            if ($payment['type'] == 'payment') {
                $totalRegular += $payment['regular_amount'];
            }
            $results[] = $data;
            $i++;
            $prevSuitNumber = $payment['suit_number'];
            
        }
        $data['J' . $i] = '';
        $data['K' . $i] =  number_format($amount, 2, '.', ',');
        $data['L' . $i] = '';
        $data['M' . $i] = '';
        $data['N' . $i] =  number_format($returned, 2, '.', ',');
        $data['O' . $i] = '';
        $data['P' . $i] =  number_format($totalTarion - $totalTarionWithdraw, 2, '.', ',');
        $data['Q' . $i] = '';
        $data['R' . $i] =  number_format($totalRegular - $totalRegularWithdraw, 2, '.', ',');
        $data['S' . $i] =  number_format($amount - $returned - $totalTarionWithdraw - $totalRegularWithdraw, 2, '.', ',');
        $data['T' . $i] = '';
        $data['U' . $i] =  number_format($upgradeTotal, 2, '.', ',');
        $data['V' . $i] = '';
        $data['W' . $i] = '';
        $data['X' . $i] =  number_format($upgradeReturnedTotal, 2, '.', ',');
        $data['Y' . $i] = '';
        $data['Z' . $i] =  number_format($upgradeTotal - $upgradeWithdrawTotal, 2, '.', ',');
        $data['AA' . $i] =  number_format($upgradeTotal - $upgradeReturnedTotal - $upgradeWithdrawTotal, 2, '.', ',');
        $results[] = $data;
       
        
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $autoSizeAarray = array('D','E','F','G','I','J','K','L','O');
        
        foreach($autoSizeAarray as $cell){
            $objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
        }
        
       $styleThinBrownBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF993300'),
                ),
            ),
            'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
        );
        
        //Header1
        $objPHPExcel->getActiveSheet()->mergeCells('A1:AA1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name of Development Project');
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->getStartColor()->setARGB('FFFF33');
  
        //header 2
        
        $numRows = count($results) + 5;
      
        $objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Property');
        
        $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('D2', 'Date');
        
        $objPHPExcel->getActiveSheet()->getStyle('E2:H2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('E2:H2');
        $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Agreement of Purchase & Sale');
        
        $objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('I2', 'Payment');
        
        $objPHPExcel->getActiveSheet()->getStyle('J2:S2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('J2:S2');
        $objPHPExcel->getActiveSheet()->setCellValue('J2', 'Purchase Deposits');
        
        $objPHPExcel->getActiveSheet()->getStyle('T2:AA2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('T2:AA2');
        $objPHPExcel->getActiveSheet()->setCellValue('T2', 'Upgrade Deposits');
        
        //Header 3
        $objPHPExcel->getActiveSheet()->getStyle('A3:Z3')->getFont()->setSize(11);
        $objPHPExcel->getActiveSheet()->getStyle('A3:Z3')->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Suite or Lot');
        
        $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Date');
        
        $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Terminated');
        
        $objPHPExcel->getActiveSheet()->getStyle('F3:G3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->setCellValue('F3', 'Purchasers');
        
        $objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('H3', 'Price');
        
        $objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('I3', 'Chq. #/Wire');
        
        $objPHPExcel->getActiveSheet()->getStyle('J3:K3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('J3:K3');
        $objPHPExcel->getActiveSheet()->setCellValue('J3', 'Received');
        
        $objPHPExcel->getActiveSheet()->getStyle('L3:N3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('L3:N3');
        $objPHPExcel->getActiveSheet()->setCellValue('L3', 'Returned');
        
        $objPHPExcel->getActiveSheet()->getStyle('O3:R3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('O3:R3');
        $objPHPExcel->getActiveSheet()->setCellValue('O3', 'Released');
        
        $objPHPExcel->getActiveSheet()->getStyle('S3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('S3', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('T3:U3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('T3:U3');
        $objPHPExcel->getActiveSheet()->setCellValue('T3', 'Received');
        
        $objPHPExcel->getActiveSheet()->getStyle('V3:X3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('V3:X3');
        $objPHPExcel->getActiveSheet()->setCellValue('V3', 'Returned');
        
        $objPHPExcel->getActiveSheet()->getStyle('Y3:Z3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('Y3:Z3');
        $objPHPExcel->getActiveSheet()->setCellValue('Y3', 'Released');
        
        $objPHPExcel->getActiveSheet()->getStyle('AA3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('AA3', 'Total');
        
        //Header 4
        $objPHPExcel->getActiveSheet()->getStyle('A4:AA4')->getFont()->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('A4:AA4')->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Number');
        
        $objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Unit');
        
        $objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Level');
        
        $objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'MM/DD/YYYY');
        
        $objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Yes/No');
        
        $objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Last Name');
        
        $objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'First Name');
        
        $objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('I4', 'Chq.#/wire');
        
        $objPHPExcel->getActiveSheet()->getStyle('J4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('J4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('K4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('K4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('L4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('L4', 'NSF');
        
        $objPHPExcel->getActiveSheet()->getStyle('M4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Refunded');
        
        $objPHPExcel->getActiveSheet()->getStyle('N4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('N4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('O4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('O4', 'Bonded/Tario');
        
        $objPHPExcel->getActiveSheet()->getStyle('P4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('P4', 'Balance');
        
        $objPHPExcel->getActiveSheet()->getStyle('Q4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('Q4', 'Excess');
        
        $objPHPExcel->getActiveSheet()->getStyle('R4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('R4', 'Balance');
        
        $objPHPExcel->getActiveSheet()->getStyle('S4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('S4', 'Net');
        
        $objPHPExcel->getActiveSheet()->getStyle('T4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('T4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('U4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('U4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('V4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('V4', 'NSF');
        
        $objPHPExcel->getActiveSheet()->getStyle('W4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('W4', 'Refunded');
        
        $objPHPExcel->getActiveSheet()->getStyle('X4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('X4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('Y4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('Y4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('Z4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('Z4', 'Balance');
        
        $objPHPExcel->getActiveSheet()->getStyle('AA4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('AA4', 'Net');
        
        
        foreach($results as $rows){
            foreach($rows as $cell => $value){
                $objPHPExcel->getActiveSheet()->setCellValue($cell, $value);
            }
        }
        
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
         // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        exit;
       
    }
    
    public function index2Action() 
    {
        ini_set('max_execution_time', 300);
        $id = $this->getRequest()->getParam('id');
        $fromDate = $this->getRequest()->getParam('from_date');
        $toDate = $this->getRequest()->getParam('to_date');
        $payment = new Application_Model_Payment();
        $paymentData = $payment->getPaymentDetails($id, $fromDate, $toDate); 
       
        $results = array(); 
        $i = 5;
        $prevSuitNumber = 0;
        $amount = 0;
        $returned = 0;
        $totalTarionWithdraw = 0;
        $totalRegularWithdraw = 0;
        $totalTarion = 0;
        $totalRegular  = 0;
        $upgradeTotal  = 0;
        $upgradeWithdrawTotal  = 0;
        $upgradeReturnedTotal = 0;
        foreach($paymentData as $payment){
            $data = array();
            if($prevSuitNumber != $payment['suit_number']){
                
                if($i != 5){
                    $data['J' . $i] = '';
                    $data['K' . $i] =  number_format($amount, 2, '.', ',');
                    $data['L' . $i] = '';
                    $data['M' . $i] = '';
                    $data['N' . $i] =  number_format($returned, 2, '.', ',');
                    $data['O' . $i] = '';
                    $data['P' . $i] =  number_format($totalTarion - $totalTarionWithdraw, 2, '.', ',');
                    $data['Q' . $i] = '';
                    $data['R' . $i] =  number_format($totalRegular - $totalRegularWithdraw, 2, '.', ',');
                    $data['S' . $i] =  number_format($amount - $returned - $totalTarionWithdraw - $totalRegularWithdraw, 2, '.', ',');
                    $data['T' . $i] = '';
                    $data['U' . $i] =  number_format($upgradeTotal, 2, '.', ',');
                    $data['V' . $i] = '';
                    $data['W' . $i] = '';
                    $data['X' . $i] =  number_format($upgradeReturnedTotal, 2, '.', ',');
                    $data['Y' . $i] = '';
                    $data['Z' . $i] =  number_format($upgradeTotal - $upgradeWithdrawTotal, 2, '.', ',');
                    $data['AA' . $i] =  number_format($upgradeTotal - $upgradeReturnedTotal - $upgradeWithdrawTotal, 2, '.', ',');
                    $results[] = $data;
                    $i++;
                    $amount = 0;
                    $returned = 0;
                    $totalTarionWithdraw = 0;
                    $totalRegularWithdraw = 0;
                    $totalTarion = 0;
                    $totalRegular  = 0;
                    $upgradeTotal  = 0;
                    $upgradeWithdrawTotal  = 0;
                    $upgradeReturnedTotal = 0;
                }
                
                $data['A' . $i] = $payment['suit_number'];
                $data['B' . $i] = $payment['suit_unit_number'];
                $data['C' . $i] = $payment['suit_level'];
                $data['D' . $i] = date('d-m-Y',strtotime($payment['purchase_date']));
                $data['E' . $i] = '';
                $data['F' . $i] = $payment['last_name'];
                $data['G' . $i] = $payment['first_name'];
                $data['H' . $i] = $payment['purchase_price'];
            }else{
                $data['A' . $i] = '';
                $data['B' . $i] = '';
                $data['C' . $i] = '';
                $data['D' . $i] = '';
                $data['E' . $i] = '';
                $data['F' . $i] = '';
                $data['G' . $i] = '';
                $data['H' . $i] = '';
                
            }
            $data['J' . $i] = '';
            $data['K' . $i] = '';
            $data['L' . $i] = '';
            $data['M' . $i] = '';
            $data['N' . $i] = '';
            $data['O' . $i] = '';
            $data['P' . $i] = '';
            $data['Q' . $i] = '';
            $data['R' . $i] = '';
            $data['S' . $i] = '';
            $data['T' . $i] = '';
            $data['U' . $i] = '';
            $data['V' . $i] = '';
            $data['W' . $i] = '';
            $data['X' . $i] = '';
            $data['Y' . $i] = '';
            $data['Z' . $i] = '';
            $data['AA' . $i] = '';
            $data['I' . $i] = $payment['cheque_number'];
            if($payment['type'] == 'payment' && ($payment['payment_type'] == 'purchase' || $payment['payment_type'] == 'locker' || $payment['payment_type'] == 'parking')){                            
                $data['J' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $amount += $payment['amount'];
            }
            if($payment['status'] == 'NSF' && ($payment['payment_type'] == 'purchase' || $payment['payment_type'] == 'locker' || $payment['payment_type'] == 'parking')){
                $data['L' . $i] =  number_format($payment['NSF_fee'], 2, '.', ',');
                $data['M' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $returned += $payment['amount'] + $payment['NSF_fee'];
            }
            if($payment['type'] == 'withdraw' && $payment['widthdraw_type'] == 'tarion'){
                $data['O' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $totalTarionWithdraw += $payment['amount'];
            }
            if($payment['type'] == 'withdraw' && $payment['widthdraw_type'] == 'regular'){
                $data['Q' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $totalRegularWithdraw += $payment['amount'];
            }
            if($payment['type'] == 'payment' && $payment['payment_type'] == 'upgrade'){
                $data['T' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $upgradeTotal += $payment['amount'];
            }
            if($payment['type'] == 'payment' && $payment['payment_type'] == 'upgrade' && $payment['status'] == 'NSF'){
                $data['V' . $i] =  number_format($payment['NSF_fee'], 2, '.', ',');
                $data['W' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $upgradeReturnedTotal += $payment['amount'] + $payment['NSF_fee'];
            }
            if($payment['type'] == 'withdraw' && $payment['widthdraw_type'] == 'upgrade'){
                $data['Y' . $i] =  number_format($payment['amount'], 2, '.', ',');
                $upgradeWithdrawTotal += $payment['amount'];
            }
            if ($payment['type'] == 'payment') {
                $totalTarion += $payment['tarion_amount'];
            }
            if ($payment['type'] == 'payment') {
                $totalRegular += $payment['regular_amount'];
            }
            $results[] = $data;
            $i++;
            $prevSuitNumber = $payment['suit_number'];
            
        }
        $data['J' . $i] = '';
        $data['K' . $i] =  number_format($amount, 2, '.', ',');
        $data['L' . $i] = '';
        $data['M' . $i] = '';
        $data['N' . $i] =  number_format($returned, 2, '.', ',');
        $data['O' . $i] = '';
        $data['P' . $i] =  number_format($totalTarion - $totalTarionWithdraw, 2, '.', ',');
        $data['Q' . $i] = '';
        $data['R' . $i] =  number_format($totalRegular - $totalRegularWithdraw, 2, '.', ',');
        $data['S' . $i] =  number_format($amount - $returned - $totalTarionWithdraw - $totalRegularWithdraw, 2, '.', ',');
        $data['T' . $i] = '';
        $data['U' . $i] =  number_format($upgradeTotal, 2, '.', ',');
        $data['V' . $i] = '';
        $data['W' . $i] = '';
        $data['X' . $i] =  number_format($upgradeReturnedTotal, 2, '.', ',');
        $data['Y' . $i] = '';
        $data['Z' . $i] =  number_format($upgradeTotal - $upgradeWithdrawTotal, 2, '.', ',');
        $data['AA' . $i] =  number_format($upgradeTotal - $upgradeReturnedTotal - $upgradeWithdrawTotal, 2, '.', ',');
        $results[] = $data;
        
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Praful Zalke")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
        
        $objPHPExcel->setActiveSheetIndex(0);
                 
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        
        $autoSizeAarray = array('D','E','F','G','I','J','K','L','O');
        
        foreach($autoSizeAarray as $cell){
            $objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
        }
        
        $styleThinBrownBorderOutline = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FF993300'),
                ),
            ),
            'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			),
        );
        
        //Header1
        $objPHPExcel->getActiveSheet()->mergeCells('A1:AA1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name of Development Project');
        
        $objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->getStartColor()->setARGB('FFFF33');
        
        
        //header 2
        
        $numRows = count($results) + 5;
        
        $objPHPExcel->getActiveSheet()->getStyle('A2:I' . $numRows)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A2:I' . $numRows)->getFill()->getStartColor()->setARGB('B0E0E6');
        
        $objPHPExcel->getActiveSheet()->getStyle('J2:AA' . $numRows)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('J2:AA' . $numRows)->getFill()->getStartColor()->setARGB('E6E6FA');
        
        $objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A2:Z2')->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Property');
        
        $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('D2', 'Date');
        
        $objPHPExcel->getActiveSheet()->getStyle('E2:H2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('E2:H2');
        $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Agreement of Purchase & Sale');
        
        $objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('I2', 'Payment');
        
        $objPHPExcel->getActiveSheet()->getStyle('J2:S2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('J2:S2');
        $objPHPExcel->getActiveSheet()->setCellValue('J2', 'Purchase Deposits');
        
        $objPHPExcel->getActiveSheet()->getStyle('T2:AA2')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('T2:AA2');
        $objPHPExcel->getActiveSheet()->setCellValue('T2', 'Upgrade Deposits');
        //die;
        //Header 3
        $objPHPExcel->getActiveSheet()->getStyle('A3:Z3')->getFont()->setSize(11);
        $objPHPExcel->getActiveSheet()->getStyle('A3:Z3')->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Suite or Lot');
        
        $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Date');
        
        $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Terminated');
        
        $objPHPExcel->getActiveSheet()->getStyle('F3:G3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
        $objPHPExcel->getActiveSheet()->setCellValue('F3', 'Purchasers');
        
        $objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('H3', 'Price');
        
        $objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('I3', 'Chq. #/Wire');
        
        $objPHPExcel->getActiveSheet()->getStyle('J3:K3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('J3:K3');
        $objPHPExcel->getActiveSheet()->setCellValue('J3', 'Received');
        
        $objPHPExcel->getActiveSheet()->getStyle('L3:N3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('L3:N3');
        $objPHPExcel->getActiveSheet()->setCellValue('L3', 'Returned');
        
        $objPHPExcel->getActiveSheet()->getStyle('O3:R3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('O3:R3');
        $objPHPExcel->getActiveSheet()->setCellValue('O3', 'Released');
        
        $objPHPExcel->getActiveSheet()->getStyle('S3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('S3', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('T3:U3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('T3:U3');
        $objPHPExcel->getActiveSheet()->setCellValue('T3', 'Received');
        
        $objPHPExcel->getActiveSheet()->getStyle('V3:X3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('V3:X3');
        $objPHPExcel->getActiveSheet()->setCellValue('V3', 'Returned');
        
        $objPHPExcel->getActiveSheet()->getStyle('Y3:Z3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->mergeCells('Y3:Z3');
        $objPHPExcel->getActiveSheet()->setCellValue('Y3', 'Released');
        
        $objPHPExcel->getActiveSheet()->getStyle('AA3')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('AA3', 'Total');
        
        //Header 4
        $objPHPExcel->getActiveSheet()->getStyle('A4:AA4')->getFont()->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('A4:AA4')->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Number');
        
        $objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Unit');
        
        $objPHPExcel->getActiveSheet()->getStyle('C4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Level');
        
        $objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'MM/DD/YYYY');
        
        $objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Yes/No');
        
        $objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Last Name');
        
        $objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'First Name');
        
        $objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('I4', 'Chq.#/wire');
        
        $objPHPExcel->getActiveSheet()->getStyle('J4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('J4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('K4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('K4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('L4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('L4', 'NSF');
        
        $objPHPExcel->getActiveSheet()->getStyle('M4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Refunded');
        
        $objPHPExcel->getActiveSheet()->getStyle('N4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('N4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('O4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('O4', 'Bonded/Tario');
        
        $objPHPExcel->getActiveSheet()->getStyle('P4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('P4', 'Balance');
        
        $objPHPExcel->getActiveSheet()->getStyle('Q4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('Q4', 'Excess');
        
        $objPHPExcel->getActiveSheet()->getStyle('R4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('R4', 'Balance');
        
        $objPHPExcel->getActiveSheet()->getStyle('S4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('S4', 'Net');
        
        $objPHPExcel->getActiveSheet()->getStyle('T4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('T4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('U4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('U4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('V4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('V4', 'NSF');
        
        $objPHPExcel->getActiveSheet()->getStyle('W4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('W4', 'Refunded');
        
        $objPHPExcel->getActiveSheet()->getStyle('X4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('X4', 'Total');
        
        $objPHPExcel->getActiveSheet()->getStyle('Y4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('Y4', 'Amount');
        
        $objPHPExcel->getActiveSheet()->getStyle('Z4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('Z4', 'Balance');
        
        $objPHPExcel->getActiveSheet()->getStyle('AA4')->applyFromArray($styleThinBrownBorderOutline);
        $objPHPExcel->getActiveSheet()->setCellValue('AA4', 'Net');
        
        
        foreach($results as $rows){
            foreach($rows as $cell => $value){
                $objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleThinBrownBorderOutline);
                $objPHPExcel->getActiveSheet()->setCellValue($cell, $value);
            }
        }
        
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        exit;
       
    }

}

