<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="tableExport.js"></script>
<script type="text/javascript" src="jquery.base64.js"></script>
<script type="text/javascript" src="jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="jspdf/jspdf.js"></script>
<script type="text/javascript" src="jspdf/libs/base64.js"></script>




<form name='box' method="post" >
	<select name="type" onchange="document.box.submit();">
		<option>Select</option>
		<option value="FPDF">FPDF</option>
		<option value="TCPDF">TCPDF</option>
		<option value="JqueryPlugin">Jquery Plugin</option>
	</select>
</form>
<?php
ob_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
	// Load data
	function LoadData($file)
	{
		$data = array();
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "dreamhome";
		
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("SELECT position , MAX(salary) FROM staff GROUP BY position"); 
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			extract($row);
			array_push($data,array($row['position'],$row['MAX(salary)']));
		}
		
		
		return $data;
	}

	// Simple table
	function BasicTable($header, $data)
	{
		// Header
		foreach($header as $col)
			$this->Cell(40,7,$col,1);
		$this->Ln();
		// Data
		foreach($data as $row)
		{
			foreach($row as $col)
				$this->Cell(40,6,$col,1);
			$this->Ln();
		}
	}
}

if (isset($_POST["type"])){
	if ($_POST["type"] == 'FPDF'){
		ob_start();
		$pdf = new PDF();
		$header = array('Position', 'Salary');
		// Data loading
		$data = $pdf->LoadData('countries.txt');
		$pdf->SetFont('Arial','',14);
		$pdf->AddPage();
		$pdf->BasicTable($header,$data);
		$pdf->AddPage();
		$pdf->Output();
		ob_end_flush();
	}
	else if ($_POST["type"] == 'TCPDF'){
		ob_start();
		require_once('tcpdf/tcpdf.php');
		$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
		$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
		$obj_pdf->setPrintHeader(false);
		$obj_pdf->setPrintFooter(false);
		$obj_pdf->SetAutoPageBreak(TRUE, 10);
		$obj_pdf->SetFont('helvetica', '', 12);
		$obj_pdf->AddPage();
		$content = '';
		$content .= '
      <h3 align="center">Export HTML Table data to PDF using TCPDF in PHP</h3><br /><br />
      <table border="1" cellspacing="0" cellpadding="5">
           <tr>
                <th width="30%">Position</th>
                <th width="30%">Salary</th>
           </tr>
      ';
		$content .= fetch_data();
		$content .= '</table>';
		$obj_pdf->writeHTML($content);
		$obj_pdf->Output('sample.pdf', 'I');
	}
	else if ($_POST["type"] == 'JqueryPlugin'){
		echo "<table id='table' style='border: solid 1px black;'>";
		echo "<thead><tr><th>position</th><th>salary</th></tr></thead>";
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "dreamhome";
		
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("SELECT position , MAX(salary) FROM staff GROUP BY position");
			$stmt->execute();
		
			// set the resulting array to associative
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
				echo $v;
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
		echo "</table>";
		
		echo "<script>$('#table').tableExport({type:'pdf',escape:'false'});</script>";
		
		
		
	}
}



function fetch_data() 
{
	$output = '';
	
	
	$data = array();
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "dreamhome";

	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare("SELECT position , MAX(salary) FROM staff GROUP BY position");
	$stmt->execute();
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);
		$output .= '<tr>
                          <td>'.$row["position"].'</td>
                          <td>'.$row["MAX(salary)"].'</td>
                     </tr>
                          ';
	}
	return $output;
}






//NO.17 group by
echo "<h2 > NO.17 group by <h2>";
echo "<table id='table' style='border: solid 1px black;'>";
echo "<tr><th>position</th><th>salary</th></tr>";

class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() { 
        echo "<tr>"; 
    } 

    function endChildren() { 
        echo "</tr>" . "\n";
    } 
} 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dreamhome";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT position , MAX(salary) FROM staff GROUP BY position"); 
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
        echo $v;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";

?>