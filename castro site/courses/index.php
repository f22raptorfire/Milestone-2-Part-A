<?php include("dbconnect.php"); ?>
<html>
    <h1 align = "center">
        Canvas Courses
    </h1>
    
    <style>
        body 
        {
            background:url("images/bg.jpg");
        }
        table
        {
            border-radius: 10px;
            
        }
        th
        {
            background-color:gray;
            border-radius: 10px;
        }
        td
        {
            background-color:white;
            border-radius: 10px;
        }
    </style>

    <body>
        <table border="1" cellspacing ='0' bordercolor="#000000" width = "1400" align = "center">
            <tr>
                <th>Course Name</th>
                <th>Course Image</th>
                <th>Professor(s)</th>
                <th>Instructor Image</th>
                <th width = '150'>Start Date</th>
                <th width = '75'>Course Length (weeks)</th>
            </tr>
            <!--First row-->

            <?
               /* $test = "SELECT * FROM coursedetails WHERE id = '45'";
                $test_result = mysql_query($test);
                echo mysql_num_rows($test_result);

                echo $profname;*/
            ?>

            <?
                //selection query
                $query = "SELECT * FROM course_data";
                $result = mysql_query($query);

                while($row = mysql_fetch_assoc($result))
                {
                    $id = $row['id'];
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

                    $offset = 0; //Offset for id should it be needed

                    $detail_id = $id - $offset;
                    $query_detail = "SELECT * FROM coursedetails WHERE id = '$detail_id'";
                    $result_detail = mysql_query($query_detail);
                    list($id, $profname, $profimage, $cid) = mysql_fetch_array($result_detail);

                    echo "
                    <tr height = '100'>
                        <td width = '200' align='center'>
                            <a href = '$course_link'>
                                $title
                            </a>
                        </td>
                        
                        <td align='center'>
                            <a href='$course_link'>
                                <img src='$course_image' border='1' width='100' height='100'>
                            </a>
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
    </body>
</html>