<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<HTML> 
<HEAD> 
   <title>Chained selects demo(class DHTMLSuite.chainedSelects) </title> 
   <link rel="stylesheet" href="css/demos.css" media="screen" type="text/css"> 
   <style type="text/css"> 
   </style>    
   <script type="text/javascript" src="../Templates/theme1/js/ajax.js"></script>    
   <script type="text/javascript" src="../Templates/theme1/js/separateFiles/dhtmlSuite-common.js"></script> 
 	<script tpe="text/javascript">
 	DHTMLSuite.include("chainedSelect");
	alert("here1");
 	</script>
</head> 
<body>    
    

<table cellpadding="5" cellspacing="5"> 
	<tr> 
	   <td>county</td> 
	   <td> 
		   <select id="country"> 
		      <option>select a country</option> 
		      <option value="turkey">Turkey</option> 
		      <option value="denmark">Denmark</option> 
		      <option value="norway">Norway</option> 
		   </select> 
	   </td> 
	</tr> 
	<tr> 
	   <td>city</td> 
	   <td> 
	   	<select id="city"></select>    
	   </td> 
	</tr> 
	<tr> 
	   <td>universty</td> 
	   <td> 
	   	<select id="university"></select>    
	   </td> 
	</tr> 
</table> 

<script type="text/javascript"> 
chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('country','city','includes/getCities.php'); 
chainedSelects.addChain('city','university','includes/getUniversities.php'); 
chainedSelects.init(); 

</script> 

</body>
</html>