<?php
      include 'dbMirage_connect.php';
         //if(isset($_GET['unit_name'])) {
            //$dbhost = 'MYSQL5013.site4now.net';
            //$dbuser = 'a3f5da_lobby';
            //$dbpass = 'relays82';
            //$conn = mysql_connect($dbhost, $dbuser, $dbpass);
         
           // if(! $conn ) {
            //   die('Could not connect: ' . mysql_error());
           // }

            if(! get_magic_quotes_gpc() ) {
               $UnitName = addslashes ($_POST['unit_name']);
               $Location = addslashes ($_POST['location']);
			   $AreaServed = addslashes ($_POST['area_served']);
               $FilterSize = addslashes ($_POST['filter_size']);
               $FilterType = addslashes ($_POST['filter_type']);
			   $FiltersDue = addslashes ($_POST['filters_due']);
               $FiltersLastChanged = addslashes ($_POST['filters_last_changed']);
               $Notes = addslashes ($_POST['notes']);
			   $Rotation = addslashes ($_POST['rotation']);
               $Belts = addslashes ($_POST['belts']);
            } else {
               $UnitName = $_POST['unit_name'];
               $Location = $_POST['location'];
			   $AreaServed = $_POST['area_served'];
               $FiltersDue = $_POST['filters_due'];
               $FiltersLastChanged = $_POST['filters_last_changed'];
               //$FiltersType = $_POST['filter_type'];
			   $FilterSize = $_POST['filter_size'];
               $Rotation = $_POST['rotation'];
			   $Notes = $_POST['notes'];
			   $Belts = $_POST["belts"];
            }
      
            //echo $UnitName;
            //echo "Unit name=".$UnitName." location=". $Location." area_served=".$Areaserved. " belts=".$Belts. " FiltersDue=".$FiltersDue." filtersize=". $FilterSize. " rotation=" .$Rotation." notes=".$Notes;
             //print_r($_POST);
            $sql="INSERT INTO equipment(unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation, filter_type, filters_last_changed) VALUES ('$UnitName','$Location','$AreaServed','$FilterSize','$FiltersDue','$Belts','$Notes','$Rotation','$FiltersType','$FiltersLastChanged')";
               //echo $sql;
               //mysqli_select_db ( $con, 'db_a3f5da_lobby' );
            $retval = mysqli_query($con,$sql);
         echo $retval;
            if(! $retval ) {
echo "could not enter data" . mysqli_error($con);
               die('Could not enter data: ' . mysqli_error($con));

            }
         //echo $sql;
            echo $UnitName . " was entered successfully!";
            mysqli_close($con);
		 

      ?>