<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Cetak</title>

	<style type="text/css">

        body {
            font-family: arial;
			font-size: 13px;
        }

	@media print {

		table {
			width: 310px;
		}

		td {
			font-size: 11px;
		}

		@page {
			size: A4 portrait;
			margin: 5mm 5mm 5mm 5mm;
		}
	}

	#Header,
	#Footer {
		display: none !important;
	}
</style>

</head>
<body>

	@foreach ($data as $data1) 

@endforeach  

<table widht="100%" style="border: 3px solid black;">
	<tr>
	  <td width="100%">
		 <table width="100%">
			   <tr>
					<td align="center" style="font-weight: bold">SPESIFIKASI PC</td>
			   </tr>
			   <tr>
					<td align="center" style="font-weight: bold; border-bottom: 2px solid black;">PT. Lestari Mulia Sentosa</td>
			   </tr>	                   
			</table>
			<table width="100%" cellpadding="2">
			   <tr>
				  <td width="40%">Computer Name</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->ComputerName }}</td>
			   </tr>
			   <tr>
				  <td width="40%">Type</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Type }}</td>
			   </tr>
			  
			</table>
			<table width="100%" cellpadding="2">
			   <tr>
				  <td width="100%">Spesifikasi Software</td>
			   </tr>	                
			</table>
			<table width="100%" cellpadding="2">	
			   <tr>
				   <td width="5%"></td>
				  <td width="35%">MAC Address</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->MACAddress }}</td>
			   </tr>		                   
			   <tr>
				  <td width="5%"></td>
				  <td width="35%">IP Address</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->IPAddress }}</td>
			   </tr> 
			   <tr>
			   <td width="5%"></td>
			   <td width="36%">Operating System</td>
			   <td width="2%">:</td>
			   <td width="100%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->OperatingSystem }}</td>
			</tr>
			<tr>
			   <td width="5%"></td>
			   <td width="35%">Domain</td>
			   <td width="2%">:</td>
			   <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Domain }}</td>
			</tr>
			<tr>
			<td width="5%"></td>
			<td width="35%">Antivirus</td>
			<td width="2%">:</td>
			<td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Antivirus }}</td>
			 </tr>
			</table>
			<table width="100%" cellpadding="2">                
			   <tr>
				  <td width="100%">Spesifikasi Hardware</td>
			   </tr>
			</table>
			<table width="100%" cellpadding="2">  
			   <tr>
				  <td width="5%"></td>
				  <td width="35%">Mainboard</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Mainboard }}</td>
			   </tr>	
			   <tr>
				  <td width="5%"></td>
				  <td width="35%">Processor</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Processor }}</td>
			   </tr>		                                      	
			   <tr>
				  <td width="5%"></td>
				  <td width="35%">Memory 1</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Memory1 }}</td>
			   </tr>
			   <tr>
			   <td width="5%"></td>
			   <td width="35%">Memory 2</td>
			   <td width="2%">:</td>
			   <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Memory2 }}</td>
				</tr>		                                      			                   	
			   <tr>
				  <td width="5%"></td>
				  <td width="35%">Storage 1</td>
				  <td width="2%">:</td>
				  <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Storage1 }}</td>
			   </tr>
			   <tr>
			   <td width="5%"></td>
			   <td width="35%">Storage 2</td>
			   <td width="2%">:</td>
			   <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->Storage2 }}</td>
			   </tr>
			   <tr>
			   <td width="5%"></td>
			   <td width="35%">VGA</td>
			   <td width="2%">:</td>
			   <td width="58%" style="font-weight: bold; border-bottom: 1px dotted black;">{{ $data1->VGA }}</td>
				</tr>
			   </tr>
	  </td>
	</tr>
  </table>
	
</body>
</html>





  <script>
	window.onload = function() {
		window.print();
	}
</script>
