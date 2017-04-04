<?php
class Utilities
{
	public static function Print_NoDataAvailable(){
		//echo "<div align='center'><img src='../Images/NoDataAvailable.png' alt='No data available' /></div>";
		echo "<div align='center'><b>Database is crashed!</b></div>";
	}
	
	public static function SanitizeString($var)
	{
		if (get_magic_quotes_gpc())
			$var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
	}
	
	public static function RedirectToPage($PageAddress)
	{
		header("Location: ".$PageAddress);
		/* Make sure that code below does not get executed when we redirect. */
		exit();
	}
	
	public static function CheckAuthentication($PathToRoot="",$ReturnUrl="")
	{
		if(!User::isAuthenticated())
			Utilities::RedirectToLoginPage($PathToRoot,$ReturnUrl);
	}
	
	public static function RedirectToLoginPage($PathToRoot="",$ReturnUrl="")
	{
		// redirect client to login page
		header('HTTP/1.1 307 temporary redirect');
		if($ReturnUrl!="")
			$ReturnUrl="?msg=SessionTimeOut&retUrl=".$ReturnUrl;
		header("Location:".$PathToRoot."index.php".$ReturnUrl);
		exit();
	}

	public static function ValidateString($Str)
	{
		if($Str=="")
			return false;
		return true;
	}

	public static function ValidateEmail($Email)
	{
		if (!((strpos($Email, ".") > 0) && (strpos($Email, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $Email))
			return false;
		return true;
	}
	
	public static function getDate()
	{
		return date('Y-m-d');
	}
	
	public static function getInfoBoxMapStyle(){
		$str="// *
  // START INFOWINDOW CUSTOMIZE.
  // The google.maps.event.addListener() event expects
  // the creation of the infowindow HTML structure 'domready'
  // and before the opening of the infowindow, defined styles are applied.
  // *
  google.maps.event.addListener(infowindow, 'domready', function() {

    // Reference to the DIV that wraps the bottom of infowindow
    var iwOuter = $('.gm-style-iw');

    /* Since this div is in a position prior to .gm-div style-iw.
     * We use jQuery and create a iwBackground variable,
     * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
    */
    var iwBackground = iwOuter.prev();

    // Removes background shadow DIV
    iwBackground.children(':nth-child(2)').css({'display' : 'none'});

    // Removes white background DIV
    iwBackground.children(':nth-child(4)').css({'display' : 'none'});

    // Moves the infowindow 115px to the right.
    iwOuter.parent().parent().css({left: '115px'});

    // Moves the shadow of the arrow 76px to the left margin.
    iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

    // Moves the arrow 76px to the left margin.
    iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: 76px !important;'});

    // Changes the desired tail shadow color.
    iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index' : '1'});

    // Reference to the div that groups the close button elements.
    var iwCloseBtn = iwOuter.next();

    // Apply the desired effect to the close button
    iwCloseBtn.css({opacity: '1', right: '38px', top: '3px', border: '7px solid #48b5e9', 'border-radius': '13px', 'box-shadow': '0 0 5px #3990B9'});

    // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
    if($('.iw-content').height() < 140){
      $('.iw-bottom-gradient').css({display: 'none'});
    }

    // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
    iwCloseBtn.mouseout(function(){
      $(this).css({opacity: '1'});
    });
  });";
		return $str;
	}
}
?>