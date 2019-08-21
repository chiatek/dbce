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

class sql {

    protected $dbms_database = "";
	protected $database = "";
    protected $username;
    protected $password;
    protected $linkid;
    protected $table;
    protected $table1;
    protected $table2;
    protected $pkey;
	protected $fkey;
	protected $pid;
	protected $fid;
    protected $order;
    protected $field1;
    protected $field2;
    protected $query;

    function __construct() 
    {}

    public function set_database($database) {
        $this->database = $database;
    }

    public function set_query($query) {
        $this->query = $query;
    }
	
	public function use_dbms_database() {
		$this->database = $this->dbms_database;
	}

    // All SQL statements for both DBCE and User databases
    public function sql($query) {

        switch($query) {

            /******* SQL for DBCE database *******/

            case "userpass_sql" :
                $sql = "SELECT username FROM ".$this->dbms_database.".users WHERE username='".$this->username."'AND password = sha1('".$this->password."')";
            break;	
            case "userinfo_sql" :
                $sql = "SELECT * FROM ".$this->dbms_database.".users WHERE username='".$this->username."'";
                break;	
            case "useravatar_sql" :
                $sql = "SELECT users.firstname, users.lastname, users.website, users.email, avatar.image FROM ".$this->dbms_database.".users 
                    INNER JOIN ".$this->dbms_database.".avatar
                    on users.avatarID=avatar.avatarID
                    WHERE users.username='".$this->username."'";
                break;	
            case "userlinks_sql" :
                $sql = "SELECT links.linkID, links.linkname, links.dashboard, icon.description FROM ".$this->dbms_database.".links
                    INNER JOIN ".$this->dbms_database.".icon
                    on links.iconID=icon.iconID
                    WHERE links.username = '".$this->username."'";
                break;
            case "usernotifications_sql" :
                $sql = "SELECT notifications.notificationID, notifications.title, notifications.description, notifyuser.nuID, notifyuser.dismiss FROM ".$this->dbms_database.".notifications
                    INNER JOIN ".$this->dbms_database.".notifyuser
                    on notifications.notificationID=notifyuser.notificationID
                    WHERE notifyuser.username = '".$this->username."' AND notifyuser.dismiss = 'N'";
                break;			
            case "linkfields_sql" :
                $sql = "SELECT links.linkname, icon.description, links.characters, links.table1, links.table2, links.pkey, links.fkey, links.column1, links.column2, links.column3, links.column4, links.column5, links.column6, links.column7, links.column8, links.column9, links.column10, links.limitqry, links.orderqry FROM ".$this->dbms_database.".links
                    INNER JOIN ".$this->dbms_database.".icon
                    on links.iconID=icon.iconID
                    WHERE links.username = '".$this->username."' AND links.linkID = '".$this->linkid."'";
                break;
            case "dashboard_sql" :
                $sql = "SELECT linkID FROM ".$this->dbms_database.".links WHERE username = '".$this->username."' AND dashboard = 'Y'";
                break;
            case "orderlinks_sql" :
                $sql = "UPDATE ".$this->dbms_database.".links SET orderqry = '".$this->order."' WHERE linkID = '".$this->linkid."'";
                break;
            case "notifydismiss_sql" :
                $sql = "UPDATE ".$this->dbms_database.".notifyuser SET dismiss = 'Y' WHERE notificationID = ".$this->query." AND username = '".$this->username."'";
                break;	
            case "multidelete_sql" :
                $sql = "DELETE FROM ".$this->query;
                break;
            case "icons_sql" :
                $sql = "SELECT * FROM ".$this->dbms_database.".icon";
                break;	
            case "wizardprepared_sql" :
                $sql = "INSERT INTO ".$this->dbms_database.".links (username, linkname, dashboard, characters, iconID, table1, table2, pkey, fkey, column1, column2, column3, column4, column5, column6, column7, column8, column9, column10, limitqry, orderqry) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                break;
            case "clientsettings_sql" :
                $sql = "SELECT username, password, firstname, lastname, website, email, dbase FROM ".$this->dbms_database.".users WHERE username='".$this->username."'";
                break;
            case "passwordtype_sql" :	
                $sql = "SELECT COLUMN_NAME AS name FROM information_schema.columns
                    WHERE TABLE_SCHEMA = '".$this->dbms_database."' AND table_name = '".$this->table."' AND COLUMN_NAME='password'";
                break;	
            case "clienttype_sql" :	
                $sql = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type, CHARACTER_MAXIMUM_LENGTH AS char_length, NUMERIC_PRECISION AS num_length, COLUMN_KEY AS ckey, IS_NULLABLE AS nullable
                    FROM information_schema.columns
                    WHERE TABLE_SCHEMA = '".$this->dbms_database."' AND table_name = '".$this->table."' AND NOT COLUMN_NAME='avatarID' AND NOT COLUMN_NAME='groupID'";
                break;
            case "user_sql" :
                $sql = "SELECT * FROM ".$this->dbms_database.".users WHERE username='".$this->username."'";
                break;	
            case "avatar_sql" :
                $sql = "SELECT * FROM ".$this->dbms_database.".avatar";
                break;
            case "viewlinks_sql" :
                $sql = "SELECT linkID as pkey, linkID, linkname, dashboard, table1, table2 FROM ".$this->dbms_database.".links
                    WHERE username = '".$this->username."'";
                break;
            case "usertype_sql" :	
                $sql = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type, CHARACTER_MAXIMUM_LENGTH AS char_length, NUMERIC_PRECISION AS num_length, COLUMN_KEY AS ckey, IS_NULLABLE AS nullable
                    FROM information_schema.columns
                    WHERE TABLE_SCHEMA = '".$this->dbms_database."' AND table_name = '".$this->table."'";
                break;	            	
            case "avatartype_sql" :	
                $sql = "SELECT COLUMN_NAME AS name FROM information_schema.columns
                    WHERE TABLE_SCHEMA = '".$this->dbms_database."' AND table_name = '".$this->table."' AND COLUMN_NAME='avatarID'";
                break;
            case "search_sql" :
                $sql = "SELECT ".$this->field1.", ".$this->field2." from ".$this->database.".".$this->table." where ".$this->field2." LIKE '%".$this->query."%'";
                break;
                
            /******* SQL for User/DBCE database *******/	

            case "selectall_sql" :	
                $sql = "SELECT * FROM ".$this->database.".".$this->table." WHERE ".$this->field1." = '".$this->query."'";
                break;
            case "select_sql" :	
                $sql = "SELECT * FROM ".$this->database.".".$this->table;
                break;
            case "datatype_sql" :	
                $sql = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type, CHARACTER_MAXIMUM_LENGTH AS char_length, NUMERIC_PRECISION AS num_length, COLUMN_KEY AS ckey, IS_NULLABLE AS nullable
                    FROM information_schema.columns 
                    WHERE TABLE_SCHEMA = '".$this->database."' AND table_name = '".$this->table."'";
                break;	
            case "typefkey_sql" :	
                $sql = "SELECT table_name AS table1, referenced_table_name AS table2, referenced_column_name AS foreign_key
                    FROM information_schema.key_column_usage
                    WHERE referenced_table_name is not null
                    AND table_schema = '".$this->database."' AND table_name = '".$this->table1."' AND referenced_column_name = '".$this->fkey."'";
                break;
            case "onetable_sql" :
                $sql = "SELECT column_name AS column_name FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = '".$this->database."' AND TABLE_NAME = '".$this->table."'";
                break;
            case "twotables_sql" :
                $sql = "SELECT column_name AS column_name, table_name AS table_name FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = '".$this->database."' AND TABLE_NAME IN ('".$this->table1."' , '".$this->table2."')";
                break;			
            case "autoincrement_sql" :	
                $sql = "SELECT auto_increment
                    FROM information_schema.tables
                    WHERE table_schema = '".$this->database."' AND table_name = '".$this->table."'";
                break;
            case "parenttable_sql" :
                $sql = "SELECT table_name, column_name
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = '".$this->database."' AND COLUMN_KEY = 'PRI'";
                break;
            case "childtable_sql" :
                $sql = "SELECT table_name AS table1, referenced_table_name AS table2, referenced_column_name AS foreign_key
                    FROM information_schema.key_column_usage
                    WHERE referenced_table_name is not null AND table_schema = '".$this->database."'";
                break;	
            case "pkey_sql" :
                $sql = "SELECT column_name
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = '".$this->database."' AND TABLE_NAME = '".$this->table1."' AND COLUMN_KEY = 'PRI'";
                break;				
            default: 
                echo "Invalid SQL paramater.";
                return;
                break;			
        }
        return $sql;
    }

    // Function to create an SQL statement from database query result of links table.
    public function set_sql($row) {

        $column = array();
        $table1 = $row["table1"];
        $table2 = $row["table2"];
        $pkey = $row["pkey"];
        $fkey = $row["fkey"];
        $description = $row["description"];
        $linkname = $row["linkname"];
        $order = $row["orderqry"];

        for($i = 1; $i <= 10; $i++) {
            if($row["column".$i]) {
                $column[$i] = $row["column".$i];
                $last_column = $column[$i];			
            }
        }

        if($table2) {
            $sql = "SELECT ".$table1.".".$pkey." AS pkey, ".$table2.".".$fkey." AS fkey, ";
            for($i = 1; $i <= count($column); $i++) {
                $tbl = explode('.', $column[$i]);
                if($column[$i] == $last_column) {
                    $sql = $sql.$column[$i].' AS '.$tbl[1];
                }
                else {
                    $sql = $sql.$column[$i].' AS '.$tbl[1].", ";
                }
            }
            $sql = $sql." FROM ".$this->database.".".$table2." INNER JOIN ".$this->database.".".$table1." on ".$table2.".".$fkey."=".$table1.".".$fkey;
        }
        else {
            $sql = "SELECT ".$pkey." AS pkey, ";
            for($i = 1; $i <= count($column); $i++) {
                if($column[$i] == $last_column) {
                    $sql = $sql.$column[$i].' AS '.$column[$i];
                }
                else {
                    $sql = $sql.$column[$i].' AS '.$column[$i].", ";
                }
            }
            $sql = $sql." FROM ".$this->database.".".$table1;
        }
        return $sql;
    }

    // Function to append order to the end of an existing SQL statement.
    public function set_order_sql($sql, $pkey, $order) {
        $sql = $sql." ORDER BY ".$pkey." ".$order;
        return $sql;
    }

    // Function to append offset to the end of an existing SQL statement.
    public function set_offset_sql($sql, $limit, $offset) {
        $sql = $sql." LIMIT ".$limit." OFFSET ".$offset;
        return $sql;
    }

    // Function to create an update prepared statement given the following paramaters:
    // (database query result, table name, primary key, primary key value)
    public function set_update_sql($result, $table, $pkey, $pid) {
        $sql = "";
        
        if ($result->num_rows > 0) {
            $total = $result->num_rows;
            $count = 0;

            $sql = "UPDATE ".$this->database.".".$table." SET";
            while($row = $result->fetch_assoc()) {
                $sql = $sql." ".$row["name"]."=?";
                if($count != ($total - 1)) {
                    $sql = $sql.", ";
                }
                $count++;
            }
            $sql = $sql." WHERE ".$pkey."='".$pid."'";
        }
        return $sql; 
    }

    // Function to create an insert prepared statement given the following paramaters:
    // (database query result, table name, primary key)
    public function set_insert_sql($result, $table, $pkey) {
        $sql = "";
        $qmark = "";
        
        if ($result) {
            $total = $result->num_rows;
            $count = 0;

            $sql = "INSERT INTO ".$this->database.".".$table." (";
            while($row = $result->fetch_assoc()) {
                $sql = $sql." ".$row["name"];
                if($count == ($total - 1)) {
                    $sql = $sql.") VALUES (";
                    $qmark = $qmark." ?";
                }
                else {
                    $sql = $sql.", ";
                    $qmark = $qmark." ?,";
                }
                $count++;
            }
            $sql = $sql.$qmark.")";
        }
        return $sql;
    }
}

?>