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
	    </style>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>


    <body>
    	
		<div class="container">
		    <h1 align = "center">
		        Canvas Courses
		    </h1>
	        <table width="800" class="table table-striped">
	        	<thead>
		            <tr>
		                <th>Name</th>
		                <th></th>
		                <th> Description </th>
		                <th>Professor(s)</th>
		                <th></th>
		                <th>Start</th>
		                <th>Length (Weeks)</th>
	            	</tr>
          		</thead>
          		<tbody>
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
					?>
		                    <tr height = '100'>
		                        <td width = '200' align='center'>
		                            <a href = '<? echo $course_link ?>'>
		                                <? echo $title ?>
		                            </a>
		                            
					<? if($video_link) { ?>
		                <a href = '<? echo $video_link ?>'>
		                    <img src = 'images/play_btn_icon.png' width ='15' height='15'>
		                </a>
		            <? }; ?>
	                    </td>
	                    
	                    <td width="100" align='center'>
	                        <a href='<? echo $course_link ?>'>
	                            <img src='<? echo $course_image ?>' border='1' width='100' height='100'>
	                        </a>
	                    </td>
	                    
	                    <td align='center'>
	                        <? echo $short_desc ?>
	                    </td>
	
	                    <td width="100" align='center'><? echo $profname ?></td>
	
	                    <td width="100" align='center'>
                        	<img src='<? echo $profimage ?>' border='1' width='100' height='100'>
	                    </td>
		                        
                        <td align='center'><? echo $start_date ?></td>
		
                    <? if($course_length!=-1) { ?>
	                    <td width='50' align='center'><? echo $course_length ?></td>
	                    </tr>
                    <? } else { ?>
	                    <td width='50' align='center'>indefinite</td>
	                    </tr>		                    
                   	<? } ?>
		                   
	        	<? } ?>
		           
	            </tbody>
	        </table>
	
	        <!-- Page selections -->
	        <div class="pagination">
	        	<ul>
	            <? if($page_number == 1) { ?>
	            	<li class="active"><a>Prev</a></li>
	            <? } else {  $prev_page = $page_number-1; ?>
		        	<li><a href="index.php?page=<? echo $prev_page ?>">Prev</a></li>
	            <? }
				for($i=1;$i<=$page_count;$i++) {
                	if($page_number == $i)  { ?>
                        <li class="active"><a href="index.php?page=<? echo $i ?>"><? echo $i ?></a></li>
                    <? } else { ?>
                        <li><a href="index.php?page=<? echo $i ?>"><? echo $i ?></a></li>
					<? } 
				}
				
                if($page_number == $page_count) { ?>
                	<li class="active"><a>Next</a></li>
                <? } else { $next_page = $page_number + 1; ?>
                	<li><a href=index.php?page=<? echo $next_page ?>>Next</a></li>
                <? } ?>
	            </ul>
	        </div>
        </div>
    </body>
</html>