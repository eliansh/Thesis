<?php require_once "classes/template.class.php";
$Template=new Template("ERROR!");
$Template->getLoginHeader();
?>

	    <script src="Javascripts/clientdatacheck.js" type="text/javascript"></script>
	<body>
	
        	
				<div id="NoJsCookie_Warning" align="center">
					<p class="NoJsCookieAlert">
						<script type="text/javascript">
							if(isCookieEnabled()==false)
								document.write('Sorry: Cookies are disabled');
							else
								document.write('ERROR !!!');
						</script>
						<noscript>
							Sorry: Your browser does not support or has disabled javascript
						</noscript>
					</p>
				</div>
				<div align="center">
					<img alt="Error" src="Images/ErrorPage.png">
				</div>
				<div align="center">
					<a href="index.php"><img src="Images/Home-32.png" /><span>&nbsp;Home page</span></a>
				</div>
        		<br>
			
	                    
        <div class='clearing'>&nbsp;</div>
		
        <div align='center' id='footer'>
			<p>Designed and developed by <b>Elia Neishaboori (201919)</b></p>
        </div>
</BODY>
</HTML>
