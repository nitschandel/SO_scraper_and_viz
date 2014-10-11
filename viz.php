<!DOCTYPE html>
<html>
<head>
	<title> Visualization </title>
	<link rel="stylesheet" href="css/viz.css">
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<script src="js/viz.js"></script>
<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>

</head>
<body>
<?php

$conn=mysqli_connect("localhost", "root","password","SO_scraper");
$filedata;
if (!(mysqli_connect_errno())) {

$result = mysqli_query($conn,"SELECT * FROM questions INNER JOIN ids ON questions.QUES_ID=ids.QUES_ID"); 

$dataset = array();
$dataset_BIG= array();
while($row = mysqli_fetch_array($result)) {

$dataset ["language"] = $row['language'];
$dataset ["question"] = $row['question'] ;
$dataset ["user" ]    = $row ['user'];
$dataset ["score" ]   = $row ['score'];
$dataset ["votes" ]   = $row ['votes'];
$dataset ["answers"]  = $row ['answers'];
$dataset ["views"]    = $row ['views'];

$file = "json/batman.json";

$json = json_decode(file_get_contents($file),true);

$json[$row["QUES_ID"] ] = array($dataset);

file_put_contents($file, json_encode($json));


}
}

else 

{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>

<script type="text/javascript">
init();
</script>
</body>
</html>
