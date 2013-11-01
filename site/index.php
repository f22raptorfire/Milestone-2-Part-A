<!DOCTYPE html>
<html>
 <head>
   <title>Home</title>
   <link rel="stylesheet" type="text/css" href="casablanca.css">
 </head>
 <body>
<?php
$connection = mysql_connect('localhost', 'root', ''); //The Blank string is the password
mysql_select_db('courses');

$query = "SELECT * FROM canvas"; //You don't need a ; like you do in SQL
$result = mysql_query($query);
$col_name = array();
// start a table tag in the HTML
echo <<< END
   <table>
     <tr>
       <thead>\n
END;
for ($i=0; $i < mysql_num_fields($result); $i++) {
	$col_name[$i] = mysql_field_name($result, $i);
	echo "         <th scope=\"col\">" . $col_name[$i] . "</th>\n";
}
echo <<<END
       </thead>
      </tr>\n
END;
while($row = mysql_fetch_array($result)){   //Creates a loop to loop through results
	echo "      <tr>\n";
	for ($i=0; $i < mysql_num_fields($result); $i++) {
		$temp = $row[$col_name[$i]];
		if(!empty($temp)) {
			if(preg_match('/(download\?|(jpg|png|gif|bmp)$)/i', $temp)) {
				$temp = "<img width =\"130px\" heigth=\"95px\" src=\"{$temp}\">";
			} else if(preg_match('/^http/', $temp)) {
				$temp = "<a href=\"{$temp}\">Link</a>";
			} else {
				$temp = "<p>{$temp}</p>";
			}
		} else {
			$temp = '<img width ="130px" heigth="95px" src="https://scontent-a-sjc.xx.fbcdn.net/hphotos-prn1/250250_257757674235389_5197433_n.jpg">';
		}
		echo "        <td>" . $temp . "</td>\n";
	}
	echo "       </tr>\n";
}
echo "   </table>\n"; //Close the table in HTML
mysql_close(); //Make sure to close out the database connection
?>
 </body>
</html>