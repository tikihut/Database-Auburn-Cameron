<?php
// Author: Cameron Taylor
// School: Auburn
// Class: Comp 5120
?>
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
<h1>Welcome To Cameron Taylor's SQL DB Search</h1>
<br>
<p><h1>Search The Database</h1></p>
<form action="" method="post">
  <input type="text" name="firstname" id="firstname" size="40" >
  <input type="submit" name="submit" value="Submit">
</form>	

<script>
function openTab(evt, cityName) {
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
echo "Connected to the SQL server successfully <br>";
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
//**********************************
//Query Search Section
//**********************************
if(isset($_POST['submit']))
{
	echo "Submited Query: <br>";
	// Take input
	$test = $_POST['firstname'];
	$test4="";
	$test4 .= $test;
	echo $test4."<br><br>";
	
	//$dabcheck = "Dab";
	/*This is just something i put in at 2am for no reason
	if($test4=$dabcheck){
		echo "<h1>Dab on these haters</h1><br>";
		?><img src="https://images-na.ssl-images-amazon.com/images/I/6101Je6svzL._SX425_.jpg" class="dab" /><br><?php
	}*/
	echo "Checking Query: ";
	// Strip slashes from input
	$testStrip = stripslashes($test4);
	//run query from input
	$result = mysqli_query($conn, $testStrip);
	//Fetch Feilds
	$fieldinfo=mysqli_fetch_fields($result);
	$search_phrase = "show columns from ";
	// get the table name of query result so that i can
	// use it in data display
	if (mysqli_num_rows($results) > 0)
	{
		?>
		<font size="4" color="Green">Vallid query submited: </font>
		<?php
		echo "<br>";
		while($row = mysqli_fetch_assoc($results))
		{
			$contents = $row["Field"];
			//Run original query again
			//now with the needed rows
			$result2 = mysqli_query($conn, $testStrip);
			if (mysqli_num_rows($result2) > 0){
					
					//Ensure only rows with content
					//are posted to site
		//TODO: Make the format better
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
   	
	}
	else {
		?>
		<font size="4" color="red">Non Valid SQL Submited</font>
		<?php
	}
}
// ****************
// End Query Search
// ****************


//*************************************
//Show Tables based on tables active in DB
//*************************************
$result2 = mysqli_query($conn, 'Show Tables');
$tables_array = array();
$i=1;
echo "<h1>Database Tables:</h1> <br>";
while($row = mysqli_fetch_assoc($result2))
{	
	//$tables_array[$i]=$row['Tables_in_cdt0020db'];
	?>
	<div class="tab">
		<button class="tablinks" onclick="openTab(event, '<?php echo $row['Tables_in_cdt0020db']?>')"><?php echo $row['Tables_in_cdt0020db'];?></button>
	</div>

	<div id="<?php echo $row['Tables_in_cdt0020db']?>" class="tabcontent">
 	<h3><?php echo $row['Tables_in_cdt0020db']?></h3>
  	<p>
		<?php 
		//Find all columns of tab
		$find_columns = "show columns from ";
		$current_table = $row["Tables_in_cdt0020db"];
		$find_columns .= $current_table;
		$search_for_tab = mysqli_query($conn, $find_columns);
		if (mysqli_num_rows($search_for_tab) > 0)
		{
			while($row = mysqli_fetch_assoc($search_for_tab))
			{
				$field = $row["Field"];
				$select_from = "SELECT * FROM ";
				$select_from .= $current_table;
				$result_tab = mysqli_query($conn, $select_from);
				if (mysqli_num_rows($result_tab) > 0) {
					echo "<table><tr><nobr><th><nobr>$field</th>";
					while($row = $result_tab->fetch_assoc()) {
						echo "<tr><nobr><td><nobr>".$row["$field"]."</td><td>";
					}
					echo "</table>";
				} 
				else {
					echo "0 results<br>";
				}
			} 
		}
		?>
	</p>
	</div>
<?php
	//$i++;
}
//**********
//END Tables
//**********

?>
