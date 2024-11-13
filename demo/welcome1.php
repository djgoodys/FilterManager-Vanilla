<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
 <script src="../d3.min.js"></script>
    <title></title>

</head>
<?php
include('dbDemo_connect.php');
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

    <div align="center" style="color:forestgreen;>  For issues with this site call or text DJ (702)237-1097</div>
    <div align="center" style="color:forestgreen;height:100px;width:50%;font-weight:bold;font-size:30px;">
        Welcome to Filter Manager<br> <font color="Blue">
            Login above(Login in disabled for demo) or<br>
        </font>
        
            <tr>
                <td align="center" height="50px">
                    <input type="submit" style="font-size:30px;" value="New user setup..." class="myButton" onclick="alert('not available in demo mode');">
                    <input type="hidden" name="action" value="new_user_setup">
                </td>
            </tr>
       
    </div>
    <br>
    <br>

    <img src="filters.png" " alt="Filter Manager by DJ" height="900" width="900">
    <Table style="width%100";>
<tr><td><div style="background-color:red; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $OD." UNITS OVER DUE"; ?>&nbsp;&nbsp;</div></td>
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
</td>
<td><div style="background-color:green; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $P." COMPLETED"; ?>&nbsp;&nbsp;</div></td>
</tr></table>
</body>
</html>