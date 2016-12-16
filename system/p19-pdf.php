<?php

# Load the fpdf.php library

require('fpdf.php');

# Create a new PDF object and start a new page

$pdf = new FPDF();

$pdf->AddPage();

# Add an image. Here we can use PHP's http wrapper to include a web image

$pdf->Image('http://static.php.net/www.php.net/images/php.gif',10);

# Now we'll set the font and add a header, plus some other text

$pdf->SetFont('Arial','B',24);

$pdf->Cell(0,30,'An Important Report','B',1,'C');

$pdf->SetFont('Arial',null,10);

$pdf->Cell(0,12,'Lots of really important text goes here. Etc.', null, 1);

# Generate a unique temporary file name

$filename = tempnam(sys_get_temp_dir(), "rep").'.pdf';

# Save the PDF to that temporary file

$pdf->Output($filename, 'F');

# Finally open the PDF for "print preview" in the default viewer

`xdg-open $filename`;
