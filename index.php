
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial;}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<h1>Welcome</h1>
<img src="https://images-na.ssl-images-amazon.com/images/I/6101Je6svzL._SX425_.jpg" class="dab" />
<br>
<p> Search The Database</p>
<form action="" method="post">
  <input type="text" name="firstname" id="firstname" size="40" >
  <input type="submit" name="submit" value="Submit">
</form>	
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'Big Balls')">Big Balls</button>
  <button class="tablinks" onclick="openCity(event, 'Bigger Balls')">Bigger Balls</button>
  <button class="tablinks" onclick="openCity(event, 'My Balls')">My Balls</button>
</div>

<div id="Big Balls" class="tabcontent">
  <h3>Big Balls</h3>
	<?php
	
	?>
  <p>These are the balls mitch likes</p>
</div>

<div id="Bigger Balls" class="tabcontent">
  <h3>Bigger Balls</h3>
  <p>These are the balls Harshil likes</p> 
</div>

<div id="My Balls" class="tabcontent">
  <h3>My Balls</h3>
  <p>These are the balls everyone likes</p>
</div>

<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
     
</body>
</html> 
<script>

if (window.XMLHttpRequest) {
  // code for otherbrowsers
  xmlhttp = new XMLHttpRequest();
	
} else {
  // this code for IE
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) 
  {

    document.getElementById("thediv").innerHTML = "";
    document.getElementById("thediv").innerHTML = this.responseText;
  }
};
xmlhttp.open("GET", "connect.php", true);
xmlhttp.send();
</script>
<?php

/* connect to database */

$dbhost = "mysql.auburn.edu";
$dbuser = "cdt0020";
$dbpass = "cdtaylor12211221";
$dbname = "cdt0020db";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
/* detect error */
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
echo "Connected to the damn SQL server successfully <br>";
/* fetch result */

while ($row = mysqli_fetch_row($result))
echo $row[0];

while ($row = mysqli_fetch_assoc($result))
echo $row['column_name'];

//$num = mysqli_$affected_rows($con);
$str = mysqli_connect_error();
$err = mysqli_connect_errno();

$str = mysqli_error($con);
$err = mysqli_errno($con);

//Query Search 
if(isset($_POST['submit']))
{
	echo "Submited <br></br>";
	$test = $_POST['firstname'];
	//echo $test;
	$test4="";
	$test4 .= $test;
	echo "Running Query: ";
	$testStrip = stripslashes($test4);
	$result = mysqli_query($conn, $testStrip);
	//var_dump($result);
	/*
	if (mysqli_num_rows($result) > 0){
    	while($row = mysqli_fetch_assoc($result))
		{
        	echo "Table: " .$row["Title"] ."<br>";
    	}
	}
	else 
	{
		echo "0 results";
		echo "<br>";
	}*/
	$fieldinfo=mysqli_fetch_fields($result);
	$search_phrase = "show columns from ";
	foreach ($fieldinfo as $val)
	{
    //printf("Name: %s\n",$val->name);
		$search_table =$val->table;
    //printf("max. Len: %d\n",$val->max_length);
    }
		//$search_phrase .= $table_name;
		
		$search_phrase .=$search_table;
		//echo $search_phrase;
		//echo "<br>";
		$results = mysqli_query($conn, $search_phrase);
		//var_dump($results);
		echo "<br>";
		if (mysqli_num_rows($results) > 0)
		{
			$row_array=array();
			$row_num_temp=0;
			while($row = mysqli_fetch_assoc($results))
			{
        	//	echo "Row: ";
			//	echo $row["Field"];
			//	echo "<br>";
				$row_array[$row_num_temp] = $row["Field"];
				while($row = mysqli_fetch_assoc($results))
				{
					$contents = $row["Field"];
					$result2 = mysqli_query($conn, $testStrip);
					if (mysqli_num_rows($result2) > 0){
						while($row = mysqli_fetch_assoc($result2))
						{	
							if ($row[$contents]!=''){
							echo $contents .": ";
							echo $row[$contents];
							echo "<br>";
							}
						}
					}
					else 
					{
						echo "0 results";
					}
					echo $row[$row["Field"]] ."<br>";
				}
			//echo $row_array[$row_num_temp];
				$row_num_temp++;
    	}
	}
}
?>