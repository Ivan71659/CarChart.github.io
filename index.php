<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php
	    $db = new SQLite3("cars.db");
		$db->exec ("
			CREATE TABLE IF NOT EXISTS cars (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				model TEXT,
				year INTEGER,
				mileage INTEGER,
				make TEXT,
				condition TEXT,
				status TEXT,
				price DECIMAL
			)
		");

		$player_count = $db->querySingle("SELECT COUNT(*) FROM cars");
		
		if ($player_count == 0) {	
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('Camry',2021,31000,'Toyota','new','available',25000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('Civic',2023,22000,'Honda','used','Pending sale',18000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('F-150',2022,68000,'Ford','used','available',35000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('Corvette',2023,5000,'Chevrolet','new','available',65000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('X5',2024,2000,'BMW','new','available',55000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('3 series',2019,150,'BMW','used','sold',100000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('RAV4',2024,7000,'Toyota','new','Pending sale',28000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('Mustang',2020,29000,'Ford','used','available',22000.00)");
			$db->exec ("INSERT INTO cars (model,year,mileage,make,condition,status,price) VALUES ('Accord',2022,18500,'Honda','used','Pending sale',15000.00)");

		}
		echo "<h4>Vehicle Search By:</h4>";
		echo"<label> Search keyword </label>";
		echo"<input id = 'search_keyword' type = 'text' required placeholder = 'Make or Model'>";

		echo"<label> Maximum Price </label>";
		echo"<input id = 'max_price' type = 'text' required placeholder = 'e.g 30000'>";

		echo "<label> Make </label>";
		echo "<select id = 'make'>"; 
			echo "<option value = ''> Any </option>";
			echo "<option value = 'bmw'> BMW </option>";
			echo "<option value = 'honda'> Honda </option>";
			echo "<option value = 'toyota'> Toyota </option>";
			echo "<option value = 'ford'> Ford </option>";
			echo "<option value = 'chevrolet'> Chevrolet </option>";
		echo "</select>";
		echo"<br>";
		echo"<br>";

		echo "<label> Condition </label>";
		echo "<select id = 'condition'>"; 
			echo "<option value = ''> Any </option>";
			echo "<option value = 'new'> New </option>";
			echo "<option value = 'used'> Used </option>";
		echo "</select>";
		echo"<br>";
		echo"<br>";
		
		echo "<label> Sort By </label>";
		echo "<select id = 'sort_by'>"; 
			echo "<option value = ''> Any </option>";
			echo "<option value = 'lowest_price'> Lowest Price </option>";
			echo "<option value = 'highest_price'> Highest Price </option>";
			echo "<option value = 'year_desc'> Year descending </option>";
			echo "<option value = 'mileage_desc'> Mileage descending </option>";
			echo "<option value = 'make_desc'> Make descending </option>";
			echo "<option value = 'lowest_price_by_mileage'> Lowest price by mileage </option>";
		echo "</select>";
		echo"<br>";
		echo"<br>";

		echo"<button onclick='sortScores()'>Apply Filters</button>";
		echo"<button onclick=\"window.location.href = '?'\">Reset</button>";

		$queryStuff = isset($_GET['q']) ? $_GET['q'] : "SELECT * FROM cars ORDER BY price DESC";
		$result = $db->query ($queryStuff); 
		echo "<table border='1'>";
   		echo"<tr> <th>Year</th> <th>Make</th> <th>Model</th> <th>Condition</th> <th>Price</th> <th>Mileage</th> <th>Status</th></tr>";		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			echo "<tr>";
			echo "<td>" . $row['year'] . "</td>";
			echo "<td>" . $row['make'] . "</td>";
   			echo "<td>" . $row['model'] . "</td>";
			echo "<td>" . $row['condition'] . "</td>";
			echo "<td>" . $row['price'] . "</td>";
			echo "<td>" . $row['mileage'] . "</td>";
			echo "<td>" . $row['status'] . "</td>";
			echo "</tr>"; 
		}
		echo "</table>";

		$db->close ();
	?>

	<script>
		var sorting = 'SELECT * FROM cars';

		function sortScores () {
			var sort_by = document.getElementById("sort_by").value; 
			var condition = document.getElementById("condition").value; 
			var make = document.getElementById("make").value; 
			var max_price = document.getElementById("max_price").value; 
			var search_keyword = document.getElementById("search_keyword").value.replace(/'/g, "''");

			var missing_condition = [];

			if (condition != "") {
				missing_condition.push ("condition = '" + condition + "'");
			}

			if (make != "") {
				missing_condition.push ("LOWER(make) = '" + make + "'");
			}

			if (max_price != "") {
				missing_condition.push ("price <= " + max_price);
			}

			if (search_keyword != "") {
				missing_condition.push ("(make LIKE '%" + search_keyword + "%' OR model LIKE '%" + search_keyword + "%')");
			}

			if (missing_condition.length > 0) {
				sorting += " WHERE " + missing_condition.join(" AND ");
			}
			
			if (sort_by == "highest_price"){
				sorting += " ORDER BY price DESC";
			}
			if (sort_by == "lowest_price"){
				sorting += " ORDER BY price ASC";
			}
			if (sort_by == "year_desc"){
				sorting += " ORDER BY year DESC";
			}
			if (sort_by == "mileage_desc"){
				sorting += " ORDER BY mileage DESC";
			}
			if (sort_by == "make_desc"){
				sorting += " ORDER BY make DESC";
			}
			if (sort_by == "lowest_price_by_mileage"){
				sorting += " ORDER BY price/mileage ASC";
			}
			
			window.location.href = '?q=' + encodeURIComponent(sorting);
		}
	</script>
</body>
</html>
