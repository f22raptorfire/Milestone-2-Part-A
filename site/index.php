
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
echo "<table>\n\t<tr>\n\t\t<thead>\n";
for ($i=0; $i < mysql_num_fields($result); $i++) {
	$col_name[$i] = mysql_field_name($result, $i);
	echo "\t\t\t<th scope=\"col\">" . $col_name[$i] . "</th>\n";
}
echo "\t\t</thead>\n\t</tr>\n";

while($row = mysql_fetch_array($result)){   //Creates a loop to loop through results
	echo "\t<tr>\n";
	for ($i=0; $i < mysql_num_fields($result); $i++) {
		$temp = $row[$col_name[$i]];
		if(!empty($temp)) {
			if(preg_match('/^http/', $temp)) {
				$temp = "<img width =\"130px\" heigth=\"95px\" src=\"{$temp}\">";
			} else {
				$temp = "<p>{$temp}</p>";
			}
		}
		echo "\t\t<td>" . $temp . "</td>\n";
	}
	echo "\t</tr>\n";
}
echo "</table>\n"; //Close the table in HTML

mysql_close(); //Make sure to close out the database connection
?>
</body>
</html>