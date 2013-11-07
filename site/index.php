<!-- Group 4, CS174-01, 2013 Fall -->
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="casablanca.css">
</head>
<body>
	<?php
		// no password on local mysql database (location, username, password)
		$connection = mysql_connect( 'localhost', 'root', '' );
		if (!$connection)
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db( 'courses' );
		$query = "SELECT * FROM canvas"; //You don't need a ; like you do in SQL
		$result = mysql_query( $query );
		$col_name = array( );
		// start a table tag in the HTML
	?>
	<table>
		<thead>
			<tr>
				<?php
					for ( $i = 0; $i < mysql_num_fields( $result ); $i++ )
					{
						$col_name[ $i ] = mysql_field_name( $result, $i );
						echo "         <th scope=\"col\">" . $col_name[ $i ] . "</th>\n";
					}
				?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php
					echo str_repeat( "<td></td>", mysql_num_fields( $result ) );
				?>
			</tr> 
		</tfoot>
		<tbody>
			<?php
				while ( $row = mysql_fetch_array( $result ) )
				//Creates a loop to loop through results
				{
					echo "      <tr>\n";
					for ( $i = 0; $i < mysql_num_fields( $result ); $i++ )
					{
						$temp = $row[ $col_name[ $i ] ];
						if ( !empty( $temp ) )
						{
							if ( preg_match( '/(download\?|(jpg|png|gif|bmp)$)/i', $temp ) )
							{
								$temp = "<img height=\"95px\" src=\"{$temp}\">";
							}
							else if ( preg_match( '/^http/', $temp ) )
							{
								$temp = "<a href=\"{$temp}\">Link</a>";
							}
							else
							{
								$temp = "<p>{$temp}</p>";
							}
						}	
						else
						{
							$temp = '<img width="95px" height="95px" src="http://www.travelervip.com/skin/frontend/default/travelervip/images/facebook-default-no-profile-pic.jpg">';
						}
						echo "        <td>" . $temp . "</td>\n";
					}
					echo "       </tr>\n";
				}
			?>
		</tbody>
	</table>
	<?php
		// Close the table in HTML
		mysql_close(); //Make sure to close out the database connection
	?>
</body>
</html>