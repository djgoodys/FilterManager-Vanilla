<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"
    name="viewport" content="width=device-width, initial-scale=1">
    <title>Filter manager. edit unit</title>
    <style>
        * {
            box-sizing: border-box;
        }

		input[type=text]{
			border: 3px solid #ddd;
			background-color:blue;
			color:white;
			border-color:'blue';
			height:30px;
			font-size:14px;
		}
		
textarea {
    width: 200px;
    padding: 18px 22px;
    border: none;
    border-radius: 5px;
    color: white;
    background-color: blue;  
}
        #myInput {
           
            background-position: 10px 12px; /* Position the search icon */
            background-repeat: no-repeat; /* Do not repeat the icon image */
            width: 100%; /* Full-width */
            font-size: 16px; /* Increase font-size */
            padding: 12px 20px 12px 40px; /* Add some padding */
            border: 5px solid #ddd; /* Add a grey border */
            margin-bottom: 12px; /* Add some space below the input */
        }

        #myTable {
            border-collapse: collapse; /* Collapse borders */
            width: 100%; /* Full-width */
            border: 1px solid #ddd; /* Add a grey border */
            font-size: 18px; /* Increase font-size */
        }

        #myTable th, #myTable td {
            text-align: left; /* Left-align text */
            padding: 12px; /* Add padding */
			font-weight:bold;
        }

        #myTable tr {
            /* Add a bottom border to all table rows */
            border-bottom: 1px solid #ddd;
        }

        #myTable tr.header {
            /* Add a grey background color to the table header and on hover */
            background-color: #f1f1f1;
            position: fixed;
            top: 0px; display:none;
        }
    </style>
<script>
function confirm_delete()
{
    if (confirm('Are you sure you want to permentaly delete this unit?')) 
        {
          document.getElementById('frmDelete').submit();
        } else 
        {
          alert('delete canceled');
        }
}
function setfilters()
{
$f1=document.getElementById("fsize1");
$f2=document.getElementById("fsize2");
$f3=document.getElementById("fsize3");
$amount=document.getElementById("amount1");
$filters=document.getElementById("filters");
if($f1.value > $f2.value){
   $hold = $f1;
   $f1=$f2;
   $f2=$hold;
}
$filters.value="("+$amount.value+")"+$f1.value + "x" + $f2.value +"x" + $f3.value;
}

function setfilters2()
{
$f1=document.getElementById("fsize1");
$f2=document.getElementById("fsize2");
$f3=document.getElementById("fsize3");
$f4=document.getElementById("fsize4");
$f5=document.getElementById("fsize5");
$f6=document.getElementById("fsize6");
$amount1=document.getElementById("amount1");
if($f1.value > $f2.value){
   $hold = $f1;
   $f1=$f2;
   $f2=$hold;
}
if($f4.value > $f5.value){
   $hold = $f4;
   $f4=$f5;
   $f5=$hold;
}
$filterset1="("+ $amount1.value +")" + $f1.value + "x" + $f2.value + "x" + $f3.value;
$amount2=document.getElementById("amount2");
$filters=document.getElementById("filters");
$filters.value=$filterset1 + " (" + $amount2.value + ")" + $f4.value + "x" + $f5.value +"x" + $f6.value;
}
</script>
</head>
<body>

<?php
error_reporting(0);
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//or, if you DO want a file to cache, use:
header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
//echo var_dump($_GET);

if(ISSET($_POST["unit_name"])) {
    //echo "from post=" . $_POST["_id"] . " name: " . $_POST["unit_name"];
}
include 'dbDemo_connect.php';
$UnitID="";
if(ISSET($_POST["_id"])){
$UnitID = $_POST["_id"];
}
if(isset($_POST["Delete_image"]))
   {
      $file = $_POST["image_name"];
      if (!unlink($file)) 
         {  
            echo ("$file cannot be deleted due to an error");  
         }  
      else 
         {  
         $query = "UPDATE equipment SET image='' WHERE _id='" . $UnitID."';";
            if (mysqli_query($con, $query)) 
               {
                  echo ("$file has been be deleted. Upload new image if you wish."); 
               }
            //header("Location: webEditUnit.php?_id=".$UnitID); 
            //exit;  
         }  
   }


if(ISSET($_GET["_id"])){
$UnitID = $_GET["_id"];
$UserName = $_GET["username"];
$UnitName = "unit_name";
$AreaServed = "area_served";
$Location = "location";
$FilterSize = "filter_size";
$FiltersDue = "filters_due";
$FiltersLastChanged = "filters_last_changed";
$Rotation = "rotation";
$Belts = "belts";
$notes = "notes";
}
if(ISSET($_POST["action"])){$Action=$_POST["action"];}
         if (isset($_GET['_id'])) 
         {
             $RecID = $_GET['_id'];
             $UnitName = $_GET['unit_name'];
             $Location = $_GET['location'];
             $AreaServed = $_GET['area_served'];
             $FilterSize = $_GET['filters'];
             $FilterType  = $_GET['filters_type'];  $FiltersDue = $_GET['filters_due'];
             $FiltersDue = $_GET['filters_due'];
             $Rotation = $_GET['rotation'];
             $Belts = $_GET['belts'];
             $Notes = $_GET['notes'];
           }
    // echo "Unit name=" . $_POST['unit_name'];//&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){

//if(isset($_POST['_id'])&&isset($_POST['unit_name'])&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){
     if (isset($_POST['_id'])) 
     {
         $RecID = $_POST['_id'];
         $UnitName = $_POST['unit_name'];
         $Location = $_POST['location'];
         $AreaServed = $_POST['area_served'];
         $FilterSize = $_POST['filters'];
         //echo "pos=".strpos($FilterSize,"(");
         if(!strpos($FilterSize,")")>0){
         $FilterSize = "(1)".$FilterSize;
         }
         $FilterType  = $_POST['filter_type'];
         $FiltersDue = $_POST['filters_due'];
         $Rotation = $_POST['filter_rotation'];
         $Belts = $_POST['belts'];
         $Notes = $_POST['notes'];
     }

     if(strcmp("$Action","delete_unit")==0)
     {
        $query = "DELETE FROM equipment WHERE _id=" . $RecID.";";
             if (mysqli_query($con, $query)) {
                echo $UnitName." has been deleted from database.";
             } else {
                 echo "Error deleting record." . mysqli_error($con);
             }
     }
if(strcmp("$Action","updatenow")==0)
     {
     //$query = "UPDATE equipment SET unit_name=?,area_served=?,filter_size=?,filters_due=?,location=?,filter_rotation=?,belts=?,notes=? WHERE _id='".$UnitName."'";
     $query = "UPDATE equipment SET unit_name='" . $UnitName . "',area_served='" . $AreaServed . "',filter_size='" . $FilterSize . "',filters_due='" . $FiltersDue . "',location='" . $Location . "',filter_rotation='" . $Rotation . "',belts='" . $Belts . "',notes='" . $Notes . "', filter_type='". $FilterType. "' WHERE _id=" . $RecID;
     if (mysqli_query($con, $query)) {
        echo $UnitName." updated successfully.";
     } else {
         echo "Error updating record: " . mysqli_error($con);
     }
 }
 //echo "UnitID=".$UnitID."<br>";
$sql = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation, filters_last_changed, assigned_to,filter_type, image FROM equipment WHERE _id=" . $UnitID . ";";
$result = $con->query($sql);
if ($result->num_rows > 0) {
echo "<table style='myTable' id='myTable' border='1'><tr><td>";
while($row = $result->fetch_assoc()) {
//if ($row["image"] != null){
if(file_exists($row["image"])) {
   echo "<img src='".$row["image"]."' width='200px' height='200px'><br>
   <form action='webEditUnit.php' method='POST'><input type='hidden' name='image_name' value='".$row["image"]."'>
   <input type='hidden' name='_id' value='".$UnitID."'>
   <input type='submit' style='height:30px;width:130px;' name='Delete_image' value='Delete_image'></form>
   <img src='images/download.gif' id='imgUpload' width='200px' height='200px' style='display: none';>";
}
else
{
   
?>

    <tr><td><div style="background-color:green; color:white; fontsize:18px;"><?php echo $row["unit_name"] ?>
    <form action="webEditUnit.php" method="post" enctype="multipart/form-data">
    Select image to upload if you wish.<br> (Max file size 500 kb.)<br>
    <input type='file' style='height:50px;width:220px;' name="fileToUpload" style='height:30px;width130px;' value='Upload file now'><br>
    <input type="hidden" name="_id" value="<?php echo $row["_id"] ?>">
    <input type="submit" style='height:30px;width130px;' onclick='document.getElementById("imgUpload").style.display='visible''; value="Upload Image" name="submit_file" id='btnUploadFile'></div>
</form>
<?php
}
?>
    </td><td style="text-align:center;"><form method="POST" action="webEditUnit.php" id="frmDelete"><input type="button" style="background-color:red;border:none;color:white;text-align:center;font-weight: bold;font-size: 16px;height:100px;width:500px;" value="DELETE UNIT FROM DATABASE" onclick="confirm_delete();"><input type="hidden" name="unit_name" value="<?php echo $row["unit_name"] ?>"><input type="hidden" name="_id" value="<?php echo $UnitID ?>"><input type="hidden" name="action" value="delete_unit"></form></td></tr>
    <tr><td><form action="webEditUnit.php" method="post">
        <?php
        echo "<tr><input type='hidden' id='". $row["_id"]. "' name='_id' value='". $row["_id"]. "'>";
        echo "<td>Unit name</td><td><input type='text' id='". $row["unit_name"] . "' name='unit_name' value='". $row["unit_name"]. "'></td></tr>";
        echo "<tr><td>Location</td><td><input type='text' id='". $row["location"] . "' name='location' value='". $row["location"]. "'></td></tr>";
        echo "<tr><td>Area served</td><td><input type='text' id='". $row["area_served"] . "' name='area_served' value='". $row["area_served"]. "'></td></tr>";
        echo "<tr><td>Filter size</td><td><div>". $row["filter_size"] . "</div></td></tr>";
        
        echo "<tr><td>Filter size #1:</td><td>Amount (<input type='text' onkeyup='setfilters();' maxlength='3' style='width:30px; overflow: hidden; max-width: 4ch;' id='amount1' name='filter_amount' value=''>)&nbsp;Filter size<input type='text' onkeyup='setfilters();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize1' name='fsize1' value=''>x<input type='text' onkeyup='setfilters();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize2' name='filter_size2' value=''>x<input type='text' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize3' name='fsize3' value='' onkeyup='setfilters();'>&nbsp;&nbsp;<input type='text' id='filters' name='filters' value='". $row["filter_size"] . "'></td></tr>";
         echo "<tr><td>Filter size #2:</td><td>Amount (<input type='text' onkeyup='setfilters2();' maxlength='3' style='width:30px; overflow: hidden; max-width: 4ch;' id='amount2' name='filter_amount' value=''>)&nbsp;Filter size<input type='text' onkeyup='setfilters2();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize4' name='fsize4' value=''>x<input type='text' onkeyup='setfilters2();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize5' name='filter_size5' value=''>x<input type='text' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize6' name='fsize6' value='' onkeyup='setfilters2();'></td></tr>";
        ?>
        <tr><td>Filter Type</td><td> <Select name="filter_type">
        <option value="<?php echo $row["filter_type"] ?>"><?php echo $row["filter_type"] ?></option>
        <option value="Extended 12 inch">Extended 12 inch</option>
        <option value="Extended 2 inch">Extended  2 inch</option>
        <option value="Paper">Paper</option>
        <option value="Cut Media">Cut Media</option>
        </Select></td></tr>
        <?php
        echo "<tr><td>Filter due date</td><td><input type='text' id='". $row["filters_due"] . "' name='filters_due' value='". $row["filters_due"]. "'></td></tr>";
        echo "<tr><td>Filter Rotation</td><td><input type='text' id='" . $row["filter_rotation"] . "' name='filter_rotation' value='". $row["filter_rotation"]. "'></td></tr>";
        //echo "Filter last changed<input type='text' id='" . $row["filters_last_changed"] . "' name='filters_last_changed' value='". $row["filters_last_changed"]. "'></td></tr>";
        echo "<tr><td>Belts</td><td><input type='text' id='". $row["belts"] . "' name='belts' value='". $row["belts"]. "'></td></tr>";
        echo "<tr><td>Notes</td><td><textarea rows ='5' id='". $row["notes"] . "' name='notes'>". $row["notes"]. "</textarea></td></tr>";
        echo "<input type='hidden' id='' name='action' value='updatenow'>";
        echo "<tr><td></td><td><input type='submit' style='background-color:green;color:gold;width:150px;height:50px;font-size:20px;font-weight: bold;' value='Update'></form>";
        echo "<form action='filtertype.php' method='get'>
        <input type='hidden' name='page' value='ListEquipment.php'>
        <input type='hidden' name='filtertype' value='".$row["filter_type"] ."'> 
        <input type='hidden' name='username' value='". $UserName."'>
        <input type='hidden' name='filters_due' value='". $FiltersDue."'>
        <input type='hidden' name='filter_rotation' value='".$row["filter_rotation"]."'>
        <input type='hidden' name='unit_id' value='".$row["_id"] ."'>
        <input type='hidden' name='unit_name' value='".$row["unit_name"] ."'>
        <input type='hidden' name='filter_size' value='".$row["filter_size"]."'>
        <input type='hidden' name='filters_used' value='".$row["filter_size"]."'>
        <input type='submit' value='FILTERS DONE' style='background-color:green;color:gold;width:150px;height:50px;font-size:20px;font-weight: bold;'></form>";
        echo "<form action='ListEquipment.php'><input type='submit' style='background-color:green;color:gold;width:150px;height:50px;font-size:20px;font-weight: bold;' value='Cancel'  style='height:50px; width:180px'></form></td></tr>";
        echo "</td></tr>";
        }
        //$con->close();
        
        } else {
            //echo "0 results";
        }







if (isset($_POST['_id'])) 
   {
      $UnitID = $_POST['_id'];
      $UnitID = (int)$UnitID;
      //echo "Unit id=". $UnitID;
   }
if(isset($_POST["submit_file"]))
   {

   //echo "Your file=".basename($_FILES["fileToUpload"]["name"]);
$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_ext = strstr($target_file, '.');
//echo "ext=".$file_ext."<br>";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit_file"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".<br>";
        $uploadOk = 1;
    } else {
        echo "File is not an image.<br>";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else 
   {
    
            $path = $_FILES['image']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $newfilename = $target_dir.$UnitID.$file_ext;
           // $oldfile=$target_file;
            //echo "temp name=".$_FILES["fileToUpload"]["tmp_name"];
                //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). "<br> was successfully uploaded.";
                //resizeImage($oldFile, $newfilename);
                $newfilename="images/".$UnitID.$file_ext;
                //echo "<br>".$newfilename;
                $imgData = resizeImage($_FILES["fileToUpload"]["tmp_name"], $newfilename, 300, 300);
               
               imagepng($imgData, $newfilename);
               //unlink($filename);
            $query = "UPDATE equipment SET image='" . $newfilename. "' WHERE _id='" . $UnitID."';";
            if (mysqli_query($con, $query)) 
               {
                  echo "<img src='" .$newfilename."' height='120px' width='120px'><br>";
                 
                } else 
                {
                    echo "Error updating img url: " . mysqli_error($con);
                }
      
   }
}

function resizeImage($file, $newFileName, $w, $h, $crop=false) {

    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    
    //Get file extension
    $exploding = explode(".",$file);
    $ext = end($exploding);
    
    switch($ext){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
}


        ?>

</body>
</html>