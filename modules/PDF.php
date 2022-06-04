<?php
namespace modules;

class PDF extends FAPDF
{
// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

function LoadRow($arr)
{
    // Read file lines
    /*$data = array();
    foreach($arr as $arr) {
        $data[] = explode(';',trim($arr));
    }*/
    return $arr;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    $i = 0;
    foreach($header as $col) {
        if ($i == 0) {
            $this->Cell(15,7,$col,1);
        }elseif ($i == 2 || $i == 3 || $i == 4) {
            $this->Cell(20,7,$col,1);
        }else {
            $this->Cell(40,7,$col,1);
        }
        $i++;
    }
    $this->Ln();
    // Data
    
    foreach($data as $row)
    {
        $i = 0;
        foreach($row as $col) {
            if ($i == 0) {
                $this->Cell(15,7,$col,1);
            }elseif ($i == 2 || $i == 3 || $i == 4) {
                $this->Cell(20,7,$col,1);
            }else {
                $this->Cell(40,7,$col,1);
            }
            $i++;
        }
        $this->Ln();
        
    }
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(10, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(40, 35, 40, 45);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}