<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
 <script src="../d3.min.js"></script>
    <title></title>
<style>
     @property --rotate {
  syntax: "<angle>";
  initial-value: 132deg;
  inherits: false;
}

:root {
  --card-height: 65vh;
  --card-width: calc(var(--card-height) / 1.5);
}

img{
  border-radius: 8px;
}
body {
  min-height: 100vh;
  background-image: url("images/filters.png");
  background-repeat: repeat-x; 
  background-size: 200px 100px;
  display: flex;
  align-items: center;
  flex-direction: column;
  padding-top: 2rem;
  padding-bottom: 2rem;
  box-sizing: border-box;
}


.card {
  background: #191c29;
  width: 120px;
  height: 120px;
  padding: 3px;
  position: relative;
  border-radius: 6px;
  justify-content: center;
  align-items: center;
  text-align: center;
  display: flex;
  font-size: 1.5em;
  color: rgb(88 199 250 / 0%);
  cursor: pointer;
  font-family: cursive;
}

.card:hover {
  color: rgb(88 199 250 / 100%);
  transition: color 1s;
}
.card:hover:before, .card:hover:after {
  animation: none;
  opacity: 0;
}


.card::before {
  content: "";
  width: 104%;
  height: 102%;
  border-radius: 8px;
  background-image: linear-gradient(
    var(--rotate)
    , #5ddcff, #3c67e3 43%, #4e00c2);
    position: absolute;
    z-index: -1;
    top: -1%;
    left: -2%;
    animation: spin 2.5s linear infinite;
}

.card::after {
  position: absolute;
  content: "";
  top: calc(var(--card-height) / 6);
  left: 0;
  right: 0;
  z-index: -1;
  height: 100%;
  width: 100%;
  margin: 0 auto;
  transform: scale(0.8);
  filter: blur(calc(var(--card-height) / 6));
  background-image: linear-gradient(
    var(--rotate)
    , #5ddcff, #3c67e3 43%, #4e00c2);
    opacity: 1;
  transition: opacity .5s;
  animation: spin 2.5s linear infinite;
}

@keyframes spin {
  0% {
    --rotate: 0deg;
  }
  100% {
    --rotate: 360deg;
  }
}

a {
  color: #212534;
  text-decoration: none;
  font-family: sans-serif;
  font-weight: bold;
  margin-top: 2rem;
}
.square {
  width: 100px;
  height: 100px;
  background-color: green;
  position: relative;
  animation: rotate 5s linear;
  animation-iteration-count: 4;
  
}

.letter {
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
}

@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
</head>
<?php
include('../dbMirage_connect.php');
//GET DATE OF LAST DATA BACK UP
$sql = "SELECT * FROM misc ORDER BY _id DESC LIMIT 1";
   
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   $LastBackup = $row["last_backup"];  
                }
        }

     function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false)
{

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
       
       switch( $str_interval){
           case "y": 
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h": 
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i": 
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s": 
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if($diff->invert)
       {
            return -1 * $total;
       }else
       {
            return $total;
       }
}

$query="SELECT * FROM equipment;";
if ($stmt = $con->prepare($query)) 
    {
        $stmt->execute();
        $stmt->bind_result($unitId, $UnitName, $location, $AreaServed, $FilterSize, $FiltersDue, $FilterType, $belts, $notes, $FilterRotation, $FiltersLastChanged, $AssignedTo, $Image );
        $X=0;
        $ND=0;
        $OD=0;
        $EquipmentList="";
        while ($stmt->fetch()) 
            {
                $EquipmentList= $EquipmentList . "{\"units\":[  {\"_id\":\"".$unitId."\", \"unit_name\":\"".$UnitName."\",\"location\":\"".$location."\"}]}";
                $today = date('Y-m-d');
                $someDate = new DateTime($FiltersDue);
                $daysoverdue= s_datediff("d",$today,$someDate,true);
                if($daysoverdue < 0)
                    {
                        //echo "<font color='red'>".$UnitName."is".$daysoverdue." days overdue.<br />";
                        $OD = $OD + 1;
                    }
                if((int)$daysoverdue > 0)
                    {
                       // echo "<font color='black'>".$UnitName."is not".$daysoverdue." days overdue.<br />";
	                    $ND=$ND+1;
                    }
                    $X=$X+1;
            }
        $P = $X - $OD;
        //echo "P=".$P." X=".$X." OD=".$OD." ND=".$ND."<br>";
}
?>
<body>

<div class="card">
  <div style="color:green;">Filter Manager</div>
</div>
    <h2 style="color:red;"></h2>
    <h3 style="color:black;">Last data back up performed on:<?php echo $LastBackup ?></h3>
    <div style="color:black;">Bug fixes 4/18/2023: Reassigning a task is now working.</div>
    <span style="height:40px;"></span>
    
    <!--<img src="images/filters.png" " alt="Filter Manager by DJ" height="200" width="200">-->
    <Table style="width%100";>
<tr><td><div style="background-color:red; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $OD." UNITS OVER DUE"; ?>&nbsp;&nbsp;</div></td></tr><tr>
<td>
 <svg width="500" height="300"> </svg>
<script>
    var data = [<?php echo $OD .", ".$P; ?>];

    var svg = d3.select("svg"),
        width = svg.attr("width"),
        height = svg.attr("height"),
        radius = Math.min(width, height) / 2,
        g = svg.append("g").attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    //var color = d3.scaleOrdinal(['#4daf4a','#377eb8']);
    var color = d3.scaleOrdinal([d3.color("red"),'#4daf4a'])
    // Generate the pie
    var pie = d3.pie();

    // Generate the arcs
    var arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);

    //Generate groups
    var arcs = g.selectAll("arc")
                .data(pie(data))
                .enter()
                .append("g")
                .attr("class", "arc")

    //Draw arc paths
    arcs.append("path")
        .attr("fill", function(d, i) {
            return color(i);
        })
        .attr("d", arc);
</script>
</td></tr><tr>
<td><div style="background-color:green; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $P." COMPLETED"; ?>&nbsp;&nbsp;</div></td>
</tr></table>
</body>
</html>