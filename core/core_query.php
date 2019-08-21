<?php
/*
MIT License

Copyright (c) 2019 Chiatek

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

class query extends sql {

    private $conn;

	// Connect to the database
    function __construct() {
        $this->conn = db_connect();
    }

	// Return query result as an associative array using function SQL (portal_sql)
	public function query_assoc($sql) {
        $result = $this->conn->query($this->sql($sql));
        if ($result->num_rows > 0) {
            return $result;
		}
		else {
			return false;
		}
	}

	// Return query result as an associative array given any SQL statement
	public function query_assoc_sql($sql) {
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
		}
		else {
			return false;
		}
	}

	// Return result row as an associative array using function SQL (portal_sql)
	public function query_assoc_row($sql) {
        $result = $this->conn->query($this->sql($sql));
        if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row;
        }
        else {
            return false;
        }
	}

	// Returns the current row of a result set as an object
	public function query_object($sql) {
        $result = $this->conn->query($this->sql($sql));
        if ($result->num_rows > 0) {
            $obj = $result->fetch_object();
            return $obj;
		}
		else {
			return false;
		}
	}

	// Output database connection info for about settings.
	public function conn_info() {
		printf("<strong>Host info:</strong> %s<br>", $this->conn->host_info);
        printf("<strong>Protocol version:</strong> %d<br>", $this->conn->protocol_version);
        printf("<strong>Server version:</strong> %s<br>", $this->conn->server_info);
	}

	// Returns the row count of a SQL result set.
	public function get_row_count($sql) {
		$result = $this->conn->query($sql);
        return $result->num_rows;
	}

	// Update data for MySQL table given an SQL statament. 
	public function update_query($query) {
		$result = $this->sql($query);
		if (!($this->conn->query($result) === TRUE)) {
			throw new Exception('Error updating record: '.$this->conn->error);
		} 
	}

	// Delete data for MySQL table.
	public function delete_query($value) {
		for ($i = 0; $i < count($value); $i++) {
			$this->query = $value[$i];
			if(!$this->conn->query($this->sql("multidelete_sql"))) {
				throw new Exception('Error updating record: '.$this->conn->error);
			}
		}
	} 

	// Prepared statement for nav link wizard (portal_navlink)
	public function prepared_stmt($input) {
		$stmt = $this->conn->prepare($this->sql("wizardprepared_sql"));
		$stmt->bind_param("sssiissssssssssssssis", $username, $linkname, $dashboard, $maxchars, $iconID, $table1, $table2, $pkey, $fkey, $column1, $column2, $column3, $column4, $column5, $column6, $column7, $column8, $column9, $column10, $limitqry, $orderqry);
			
		$linkname = $input['linkname'];
		$username = $_SESSION['valid_user'];
		$dashboard = $input['dashboard'];
		$maxchars = $input["chars"];
		$iconID = $input['iconID'];
		$table1 = $input['table1'];
		$table2 = $input['table2'];
		$pkey = $input['pkey'];
		$fkey = $input['fkey'];
		$column1 = $input['column1'];
		$column2 = $input['column2'];
		$column3 = $input['column3'];
		$column4 = $input['column4'];
		$column5 = $input['column5'];
		$column6 = $input['column6'];
		$column7 = $input['column7'];
		$column8 = $input['column8'];
		$column9 = $input['column9'];
		$column10 = $input['column10'];
		$limitqry = $input['limit'];
		$orderqry = "ASC";
		$stmt->execute();
		$stmt->close();
	}

	// Create an SQL insert prepared statement, get query result set, and run prepared update.
	public function set_prepared_insert($query, $input, $table, $pkey) {
		$this->table = $table;

		if(isset($input['password'])) { 
			$input['password'] = sha1($input['password']);
		}

		$result = $this->conn->query($this->sql($query));
		$sql = $this->set_insert_sql($result, $table, $pkey);

		$result = $this->conn->query($this->sql($query));
		$this->prepared_update($sql, $result, $input);
	}

	// Create an SQL update prepared statement, get query result set, and run prepared update.
	public function set_prepared_update($query, $input, $table, $pkey, $pid) {
		$this->table = $table;
		$result = $this->conn->query($this->sql($query));
		$sql = $this->set_update_sql($result, $table, $pkey, $pid);

		$result = $this->conn->query($this->sql($query));
		$this->prepared_update($sql, $result, $input);
	}

	// Assemble and execute statement for prepared update with user input.
	public function prepared_update($sql, $result, $input) {
		$i = 0;
		$param_list = array();
		$total = $result->num_rows;

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$param_list[$i] = $input[$row["name"]];
				$i++;
			}
			$stmt = $this->conn->prepare($sql);
			$this->dynamic_bind_variables($stmt, $param_list);

			$stmt->execute();
			$stmt->close();
		}
		else {
			throw new Exception('Error updating database (prepared_update).');
		}
	}

	// Function to execute a prepared statement using dynamic parameters.
	public function dynamic_bind_variables($stmt, $params) {
		if ($params != null) {
			$types = '';	// Generate the Type String (eg: 'issisd')
			foreach($params as $param)
			{
				if(is_int($param)) {
					$types .= 'i';	// Integer
				} 
				else if (is_float($param)) {
					$types .= 'd';	// Integer
				} 
				else if (is_string($param)) {
					$types .= 's';	// String
				} 
				else {
					$types .= 'b';	 // Blob and Unknown
				}
			}

			$bind_names[] = $types;	// Add the Type String as the first Parameter

			for ($i=0; $i<count($params); $i++) {	// Loop thru the given Parameters
				$bind_name = 'bind' . $i;	// Create a variable Name
				$$bind_name = $params[$i];	// Add the Parameter to the variable Variable
				$bind_names[] = &$$bind_name;	// Associate the Variable as an Element in the Array
			}
			
			// Call the Function bind_param with dynamic Parameters
			call_user_func_array(array($stmt,'bind_param'), $bind_names);
		}
	}

}

?>