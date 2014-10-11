<?php
if(isset($_POST['e_language'])){
    include_once('simplehtmldom_1_5/simple_html_dom.php');

    //Connecting to MySql with the database named 'SO_scraper', replace password with your own.
    $conn=mysqli_connect("localhost", "root","","p");

    if (!(mysqli_connect_errno())) {

     //Creating tables for the database
     $sql="CREATE TABLE IF NOT EXISTS questions(QUES_ID INT NOT NULL , question VARCHAR(100), user VARCHAR(30), score INT, votes INT, answers INT, views INT, PRIMARY KEY (QUES_ID) )";
     $sql1="CREATE TABLE IF NOT EXISTS lang(language VARCHAR(20), questionstagged INT,PRIMARY KEY (language) )";
     $sql2="CREATE TABLE IF NOT EXISTS ids(language VARCHAR(20), QUES_ID INT, FOREIGN KEY (QUES_ID) REFERENCES questions(QUES_ID),FOREIGN KEY (language) REFERENCES lang(language))";
     if(mysqli_query($conn, $sql)&&mysqli_query($conn, $sql1)&&mysqli_query($conn, $sql2)) {
       //Retrieving data from the form
       $entry=$_POST['e_language'];
       $tags=explode(",", $entry);
       foreach ($tags as $i) {
         $target_url = "http://stackoverflow.com/questions/tagged/".$i;
         $html = new simple_html_dom();
         $html->load_file($target_url);

         //Retrieving the total question tagged in the particular topic
         $summarycount=$html->find('.summarycount', 0);
         $summarycount= filter_var($summarycount, FILTER_SANITIZE_NUMBER_INT);

         foreach ($html->find('.question-summary') as $ques_sum) {
           $id=filter_var($ques_sum->id, FILTER_SANITIZE_NUMBER_INT);
           $id=str_replace("-", "", $id);


             // Question Summary
           $summary=$ques_sum->find(".summary",0);

           $qlink=$summary->find(".question-hyperlink",0);
           $qlink= $qlink->innertext;

           $userinfo=$summary->find(".user-details",0);

           $name= $userinfo->find("a",0);
           $name=$name->innertext;

           $repscore=$userinfo->find(".reputation-score",0);
           $repscore= filter_var($repscore->innertext, FILTER_SANITIZE_NUMBER_INT);

             //STATS
           $stats=$ques_sum->find(".statscontainer",0);
           $votes=$stats->find(".vote-count-post",0);

           $votes=filter_var($votes, FILTER_SANITIZE_NUMBER_INT);

           $answers=$stats->find(".status strong",0);
           $answers = filter_var($answers, FILTER_SANITIZE_NUMBER_INT);


           $views=$stats->find(".views",0);
           $views=filter_var($views, FILTER_SANITIZE_NUMBER_INT);


           //Inserting the data into the tables
           $sql_insert="INSERT INTO questions (QUES_ID,question,user,score,votes,answers,views) VALUES('$id','$qlink', '$name', '$repscore', ' $votes','$answers','$views')";
           $sql_insert1="INSERT INTO ids (language,QUES_ID) VALUES('$i','$id')";
           $sql_insert2="INSERT INTO lang (language,questionstagged) VALUES('$i','$summarycount')";

           mysqli_query($conn, $sql_insert);
           mysqli_query($conn, $sql_insert1);
           mysqli_query($conn, $sql_insert2);


         }
       }
     }
     else{
       echo "Error Creating Table:".mysql_error($conn) ;}
     }
     else {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();# code...
}

mysqli_close($conn);

header("Location: /scrape/viz.php");
die();
}

?>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/tags.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery/1.9.1/jquery-1.9.1.min.js"></script>
<script src="js/tags.js"></script>

</head>
<body>
<div class="container">
<h2> Stack Overflow Data scraper </h2>
    <div class="row-fluid">
  <form action="" method="post">
  <div class="col-md-5"><p>Enter the language desired: <input type="text" name="e_language"  id="languages"/></p>
  <p><input type="submit" class="btn btn-primary" /></p> </div>
  <script type="text/javascript">
      $('#languages').tagsInput();
  </script>
</div > </div>
</body>
</html>
