<?php 
ini_set('max_execution_time', 300);
function get_http_response_code($theURL) {
    $headers = get_headers($theURL);
    return substr($headers[0], 9, 3);
}
function getPage($link){ 
    
   if ($fp = fopen($link, 'r')) { 
      $content = ''; 
         
      while ($line = fread($fp, 1024)) { 
         $content .= $line; 
      } 
   } 

   return $content;   
} 

function pingLink($domain){ 
    $file      = @fopen($domain,"r"); 
    $status    = -1; 

    if (!$file) { 
       $status = -1;  // Site is down 
    } 
    else { 
        $status = 1; 
        fclose($file); 
    } 
    return $status; 
} 

function checkPage($content){ 
   $links = array(); 
   $textLen = strlen($content);  
   echo $textLen; 
   if ( $textLen > 10){ 
      $startPos = 0; 
      $valid = true; 
       
      while ($valid){ 
         $spos  = strpos($content,'<a ',$startPos); 
         if ($spos < $startPos) $valid = false; 
         $spos     = strpos($content,'href',$spos); 
         $spos     = strpos($content,'"',$spos)+1; 
         $epos     = strpos($content,'"',$spos); 
         $startPos = $epos; 
         $link = substr($content,$spos,$epos-$spos); 
         // if (strpos($link,'http://') !== false || strpos($link,'https://') !== false){
          $links[] = $link; 
         // } 
      } 
   } 
    
   return $links; 
} 

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd"> 
<html> 
<body> 
   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="domain" id="domain"> 
         
        <table width="100%"> 
          <tr><td>URL to check:</td><td><input class="text" name="myurl" type="text" size="45"></td></tr> 
          <tr><td align="center" colspan="2"><br/><input class="text" type="submit" name="submitBtn" value="Check links"></td></tr> 
        </table>   
   </form> 
<?php     
    if (isset($_POST['submitBtn'])){ 
        $url = isset($_POST['myurl']) ? $_POST['myurl'] : ''; 

        echo 'URL:'.$url;

        filter_var($url, FILTER_SANITIZE_URL);
        // Validate url
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            echo("<p>$url is a valid URL</p>");
        } else {
            echo("<h3>$url is not a valid URL</h3>");
            echo("<p>Please ensure the url contains http:// or https://</p>");
            return;
        }

?> 
        <table width="100%"> 
<?php 
         $txt = getPage($url); 

         $linkArray = checkPage($txt); 
         foreach ($linkArray as $value) { 
          // echo '<pre>';

          // var_dump(parse_url($value));
          // echo '</pre>';
          // parse_url($url, PHP_URL_SCHEME);
          // $headers = get_headers($value);

          if(get_http_response_code($value) == ){

          }

            if (pingLink($value) <= 0){ 
               $status = pingLink($value) . " = INVALID"; 
            } else { 
               $status = pingLink($value) . " = OK"; 
            } 
             echo "<tr><td align='left'>$value</td><td>$status</td></tr>"; 
             // sleep(1); 
             @ob_flush(); 
             flush(); 
         } 

?> 
        </table> 
<?php             
    } 
?> 
</body>    