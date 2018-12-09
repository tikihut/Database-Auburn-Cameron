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
<h1>Welcome</h1>
<img src="https://images-na.ssl-images-amazon.com/images/I/6101Je6svzL._SX425_.jpg" class="dab" />
<br>
<p> Search The Database</p>
<form action="" method="post">
  <input type="text" name="firstname" id="firstname" size="40" >
  <input type="submit" name="submit" value="Submit">
</form>	
<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Big Balls')">Big Balls</button>
  <button class="tablinks" onclick="openTab(event, 'Bigger Balls')">Bigger Balls</button>
  <button class="tablinks" onclick="openTab(event, 'My Balls')">My Balls</button>
</div>
?>
<div id="Big Balls" class="tabcontent">
  <h3>Big Balls</h3>
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
//
//Show Tables based on tables active in DB
//
$result2 = mysqli_query($conn, 'Show Tables');
$tables_array = array();
$i=1;
echo "Testing Tables <br>";
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
					echo "<table><tr><th>$field</th>";
					while($row = $result_tab->fetch_assoc()) {
						echo "<tr><td>".$row["$field"]."</td><td>";
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
echo "END Testing Tables <br>";
//
//Query Search Section
//
if(isset($_POST['submit']))
{
	echo "Submited <br></br>";
	// Take input
	$test = $_POST['firstname'];
	$test4="";
	$test4 .= $test;
	echo "Running Query: ";
	// Strip slashes from input
	$testStrip = stripslashes($test4);
	//run query from input
	$result = mysqli_query($conn, $testStrip);
	//Fetch Feilds
	$fieldinfo=mysqli_fetch_fields($result);
	$search_phrase = "show columns from ";
	// get the table name of query result so that i can
	// use it in data display
	foreach ($fieldinfo as $val)
	{
    	//printf("Name: %s\n",$val->name);
		$search_table =$val->table;
    	//printf("max. Len: %d\n",$val->max_length);
    }
	//add in correct table to the column search
	$search_phrase .=$search_table;
	//Run query Show Solumns from table
	//then find all fields of that table
	//so that i can display correct rows
	$results = mysqli_query($conn, $search_phrase);
	echo "<br>";
	if (mysqli_num_rows($results) > 0)
	{
		while($row = mysqli_fetch_assoc($results))
		{
			$contents = $row["Field"];
			//Run original query again
			//now with the needed rows
			$result2 = mysqli_query($conn, $testStrip);
			if (mysqli_num_rows($result2) > 0){
				while($row = mysqli_fetch_assoc($result2))
				{	
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
}
//
// End Query Search
//
?>