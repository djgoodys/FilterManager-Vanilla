<style>::after.filter-manager {
  background-color: black;
  color: #39ff14;
  width: 200px;
  height: 200px;
  position: relative;
  animation-name: rotate;
  animation-duration: 10s;
  animation-iteration-count: 1;
  animation-timing-function: linear;
}

@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
    opacity: 0;
  }
}
@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
    opacity: 0;
  }
}
</style>
<html>
<?php 
$today = date('Y-m-d');
$lastDigit = (int)substr($today, -1);

if ($lastDigit % 2 === 0) {
  echo "<div style='font-size:2em;text-align:center;background-color: #6C91AC;color:whitesmoke;'><img src='images/welcome.jpg' style='width:50%;height:auto;'></div><script defer>document.body.style.backgroundColor = '#6C91AC';</script>";
} else {
  echo "<div style='font-size:2em;text-align:center;background-color: green;color:whitesmoke;'>Welcome to Filter Manager. Please Login</p><img src='images/fm1.jpg'></div>";
}
?>

</html>