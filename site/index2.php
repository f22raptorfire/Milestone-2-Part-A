<?php include("dbconnect.php");
 
	$courses_per_page = 10; //Total number of courses to be displayed per page
	
	//Get the total number of items in the database
	$row_query = "SELECT * FROM course_data";
	$row_result = mysql_query($row_query);
	$num_elements = mysql_num_rows($row_result);
	
	$page_count = $num_elements / $courses_per_page;//Total number of pages
	$page_count = intval($page_count);
	if($page_count % $courses_per_page != 0)
	    $page_count++;  
	
	$page_number = 1;
	if(isset($_GET['page']))
	    $page_number = $_GET['page'];
?>
<html>
	<head>
	    <title>
	        Canvas Courses - Group 4
	    </title>
	    <style>
			body {
			    background:url("images/bg.jpg");
			}
			table {
			    border-radius: 10px;
			
			}
			th {
			    background-color:gray;
			    border-radius: 10px;
			}
			td {
			    background-color:white;
			    border-radius: 10px;
			}
	    </style>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    </head>


    <body>
    	
		<div class="container">
		    <h1 align = "center">
		        Canvas Courses
		    </h1>
		
		    <p>
			   
		    </p>
		
	        <table border='2' cellspacing ='1' bordercolor='#000000' width = '1400' align = 'center'>
	            <tr>
	                <th>Course Name</th>
	                <th width = '120'>Course Image</th>
	                <th> Description </th>
	                <th width = '200'>Professor(s)</th>
	                <th width = '120'>Instructor Image</th>
	                <th width = '150'>Start Date</th>
	                <th width = '75'>Course Length (weeks)</th>
	            </tr>
	            <?
	                $offset = 0;//Indexing in my local DB was messed up. This offset is the id of the first course in the DB.
	                
	                $start_elem = $offset + ($page_number - 1) * $courses_per_page;
	                $end_elem = $start_elem + $courses_per_page - 1;
	
	                //selection query
	                $query = "SELECT * FROM course_data WHERE id BETWEEN $start_elem AND $end_elem";
	                $result = mysql_query($query);
	
	
	                while($row = mysql_fetch_assoc($result))
	                {
	                    $course_id = $row['id'];
	                    $title = $row['title'];
	                    $short_desc = $row['short_desc'];
	                    $long_desc = $row['long_desc'];
	                    $course_link = $row['course_link'];
	                    $video_link = $row['video_link'];
	                    $start_date = $row['start_date'];
	                    $course_length = $row['course_length'];
	                    $course_image = $row['course_image'];
	                    $category = $row['category'];
	                    $site = $row['site'];
	
	                    $detail_id = $course_id;
	                    $query_detail = "SELECT * FROM coursedetails WHERE id = '$detail_id'";
	                    $result_detail = mysql_query($query_detail);
	                    list($id, $profname, $profimage, $cid) = mysql_fetch_array($result_detail);
	
	                    //Rows
	                    echo "
	                    <tr height = '100'>
	                        <td width = '200' align='center'>
	                            <a href = '$course_link'>
	                                $title
	                            </a>
	                            ";
	
	                    if($video_link)
	                    {
	                        echo "
	                            <a href = '$video_link'>
	                                <img src = 'images/play_btn_icon.png' width ='10' height='10'>
	                            </a>";
	                    };
	
	
	                    echo" 
	                        </td>
	                        
	                        <td align='center'>
	                            <a href='$course_link'>
	                                <img src='$course_image' border='1' width='100' height='100'>
	                            </a>
	                        </td>
	                        
	                        <td align='center'>
	                            $short_desc
	                        </td>
	
	                        <td align='center'>$profname</td>
	
	                        <td align='center'>
	                            <a>
	                                <img src='$profimage' border='1' width='100' height='100'>
	                            </a>
	                        </td>
	                        
	                        <td align='center'>$start_date</td>";
	
	                    if($course_length!=-1)
	                    {
	                        echo 
	                        "<td width='50' align='center'>$course_length</td>
	                        </tr> ";
	                    }
	                    else
	                    {
	                        echo 
	                        "<td width='50' align='center'>indefinite</td>
	                        </tr> ";
	                    }
	                   
	                }
	            ?>
	        </table>
	
	        <!-- Page selections -->
	        <p align = 'center'>
	            <?
	                if($page_number == 1)
	                    echo "<<";
	                else
	                {
	                    $prev_page = $page_number-1;
	                    echo "<a href = index.php?page=$prev_page> << </a>"; 
	                }
	
	                for($i=1;$i<=$page_count;$i++)
	                    if($page_number == $i)
	                        Echo " <a href = index.php?page=$i>  [$i]  <a>";
	                    else
	                        Echo " <a href = index.php?page=$i>  $i  <a>";
	                
	                if($page_number == $page_count)
	                    echo ">>";
	                else
	                {
	                    $next_page = $page_number + 1;
	                    echo "<a href = index.php?page=$next_page> >> </a>"; 
	                }
	            ?>
	        </p>
        </div>
    </body>
</html>