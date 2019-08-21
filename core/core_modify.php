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

class modify extends search
{
    public function set_modify($table, $pkey, $pid) {
        $this->table = $table;
        $this->field1 = $pkey;
        $this->query = $pid;
    }

    // Query the database of the current table or two tables.
    // Call display_modify_form() with the field name and result.
    public function set_modify_form() {

        if ($this->table1 == "notifications") {
            $this->database = $this->dbms_database;
            print('<form action="modify.php" method="post" id="modify">');
        }
        else if ($this->linkid == 0) {
           print('<form action="settings.php" method="post" id="update">');
        }
        else {
            print('<form action="modify.php" method="post" id="modify">');
        }

        if($this->table2) {
            printf('<div class="w3-panel w3-card w3-display-container w3-white window">
                <div class="card-wrapper w3-display-topleft">%s​</div>
                <span onclick="this.parentElement.style.display=\'none\'"
                class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span><hr class="hr"><br>', ucfirst($this->table1)." / ".ucfirst($this->table2));

            $this->set_modify($this->table1, $this->pkey, $this->pid);
            $tbl1_field = $this->query_assoc("datatype_sql");    
            $tbl1_record = $this->query_assoc("selectall_sql");

            $this->set_modify($this->table2, $this->fkey, $this->fid);
            $tbl2_field = $this->query_assoc("datatype_sql");
            $tbl2_record = $this->query_assoc("selectall_sql");

            printf('<input type="hidden" name="link" value="%s" />', $this->linkid);
            printf('<input type="hidden" name="ptbl" value="%s" />', $this->table1);
            printf('<input type="hidden" name="pk" value="%s" />', $this->pkey);
            printf('<input type="hidden" name="pid" value="%s" />', $this->pid);
            printf('<input type="hidden" name="ftbl" value="%s" />', $this->table2);
            printf('<input type="hidden" name="fk" value="%s" />', $this->fkey);
            printf('<input type="hidden" name="fid" value="%s" />', $this->fid);  

            $this->display_modify_form($tbl1_field, $tbl1_record, $this->table1);
            print('<div class="card-body"><hr><br></div>');
            $this->display_modify_form($tbl2_field, $tbl2_record, $this->table2);
        }
        else {
            printf('<div class="w3-panel w3-card w3-display-container w3-white window">
                <div class="card-wrapper w3-display-topleft">%s​</div>
                <span onclick="this.parentElement.style.display=\'none\'"
                class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span><hr class="hr"><br>', ucfirst($this->table1));

            $this->set_modify($this->table1, $this->pkey, $this->pid);
            $tbl1_field = $this->query_assoc("datatype_sql"); 
            $tbl1_record = $this->query_assoc("selectall_sql");

            printf('<input type="hidden" name="link" value="%s" />', $this->linkid);
            printf('<input type="hidden" name="ptbl" value="%s" />', $this->table1);
            printf('<input type="hidden" name="pk" value="%s" />', $this->pkey);
            printf('<input type="hidden" name="pid" value="%s" />', $this->pid);

            $this->display_modify_form($tbl1_field, $tbl1_record, $this->table1);
        }

        if ($this->table1 == "notifications") {
            print('<div class="card-body"><br><hr><br>
            <p><button class="w3-button w3-round-medium teal hover-teal dismiss-btn">Dismiss</button>​ <a href="index.php" class="w3-button w3-white w3-hover-white border w3-round-medium">Cancel</a></p>
            </div></div></form>'); 
        }      
        else if ($this->linkid == 0) {
            print('<div class="card-body"><br><hr><br>
            <p><button class="w3-button w3-round-medium teal hover-teal save-btn">Save changes</button>​ <a href="index.php" class="w3-button w3-white w3-hover-white border w3-round-medium">Cancel</a></p>
            </div></div></form>'); 
        }
        else {
            printf('<div class="card-body"><br><hr><br>
            <p><button class="w3-button w3-round-medium teal hover-teal save-btn">Save changes</button>​ <a href="view.php?link=%s" class="w3-button w3-white w3-hover-white border w3-round-medium">Cancel</a></p>
            </div></div></form>', $this->linkid); 
        }

    }

    // Function to display the database table field and value with foreign keys in form view.
    public function display_modify_form($tbl_field, $tbl_record, $table) {

        print('<div class="card-body"><br>');     

        if ($tbl_field && $tbl_record) {
            $row = $tbl_record->fetch_assoc();
            while ($column = $tbl_field->fetch_assoc()) {
                printf('<div class="w3-col m2">
                <h5 class="card-txt">%s</h5>
                </div>
                <div class="w3-col m10">', $column["name"]);
                if($column["name"] === "column1" || $column["name"] === "column2" || $column["name"] === "column3" || $column["name"] === "column4" || 
                    $column["name"] === "column5" || $column["name"] === "column6" || $column["name"] === "column7" || $column["name"] === "column8" || 
                    $column["name"] === "column9" || $column["name"] === "column10" || $column["name"] === "username" || $column["name"] === "dashboard" || 
                    $column["name"] === "table1" || $column["name"] === "table2" || $column["name"] === "pkey" || $column["name"] === "fkey" || $column["name"] === "orderqry") {
                    printf('<input class="w3-input border" type="text" value="%s" disabled>', $row[$column["name"]]);
                    printf('<input type="hidden" name="%s" value="%s" />', $column["name"], $row[$column["name"]]);
                } 
                else if($column["ckey"] === "PRI") {
                    if($table === $this->table2) {
                        printf('<input class="w3-input border" type="text" value="%s" disabled>', $row[$column["name"]]);
                    }
                    else {
                        printf('<input class="w3-input border" type="text" value="%s" disabled>', $row[$column["name"]]);
                        printf('<input type="hidden" name="%s" value="%s" />', $column["name"], $row[$column["name"]]);
                    }
                }
                else if($column["ckey"] === "MUL") {
                    $fkey = $this->fkey_query($column["name"]);
                    $value = $this->fkey_description();
                    printf('<select class="w3-select border" name="%s">', $column["name"]);
                    while($fkey_row = $fkey->fetch_assoc()) {
                        if(($row[$column["name"]] === $fkey_row[$column["name"]]) && $value) {
                            printf('<option value="%s" selected>%s - %s</option>', $fkey_row[$column["name"]], $fkey_row[$column["name"]], $fkey_row[$value]);
                        }
                        else if($row[$column["name"]] === $fkey_row[$column["name"]]) {
                            printf('<option value="%s" selected>%s</option>', $fkey_row[$column["name"]], $fkey_row[$column["name"]]);
                        }
                        else if($value) {
                            printf('<option value="%s">%s - %s</option>', $fkey_row[$column["name"]], $fkey_row[$column["name"]], $fkey_row[$value]);
                        }
                        else {
                            printf('<option value="%s">%s</option>', $fkey_row[$column["name"]], $fkey_row[$column["name"]]);
                        }
                    }
                    print('</select>');
                }                                  
                else if($column["type"] === "text" && $column["name"] === "html" || $column["name"] === "description") {
                    printf('<textarea name="%s">%s</textarea>', $column["name"], $this->format_column($row[$column["name"]], $column["char_length"]));
                }
                else if($column["type"] === "text") {
                    printf('<textarea id="summernote" name="%s">%s</textarea>', $column["name"], $this->format_column($row[$column["name"]], $column["char_length"]));
                }
                else if($column["type"] === "varchar" && $column["name"] === "video") {
                    printf('<div class="w3-col s10">
                        <input class="input border" id="video-upload" type="text" name="%s" maxlength="%s" value="%s">', $column["name"], $column["char_length"], $row[$column["name"]]);
                    print('</div><div class="w3-col s2">
                        <button id="btn-video" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-file-movie-o"></i></button>
                        </div>
                        <div class="file-upload">
                        <input type="file" id="upload-video" />
                        </div>');
                }
                else if($column["type"] === "varchar" && ($column["name"] === "image" || $column["name"] === "poster")) {
                    printf('<div class="w3-col s10">
                        <input class="input border" id="image-upload" type="text" name="%s" maxlength="%s" value="%s">', $column["name"], $column["char_length"], $row[$column["name"]]);
                    print('</div><div class="w3-col s2">
                        <button id="btn-image" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-file-picture-o"></i></button>
                        </div>
                        <div class="file-upload">
                        <input type="file" id="upload-image" />
                        </div>');
                }
                else if($column["type"] === "varchar" && $column["name"] === "title") {
                    printf('<input class="w3-input border" type="text" name="%s" maxlength="%s" value="%s">', $column["name"], $column["char_length"], $this->format_column($row[$column["name"]], $column["char_length"]));
                }
                else if($column["type"] === "varchar") {
                    printf('<input class="w3-input border" type="text" name="%s" maxlength="%s" value="%s">', $column["name"], $column["char_length"], $row[$column["name"]]);
                }
                else if($column["type"] === "char" && $column["name"] === "password" && $table === "users") {
                    printf('<div class="w3-col s10">
                    <input class="input border" type="password" name="%s" maxlength="%s" value="%s" disabled>', $column["name"], $column["char_length"], $row[$column["name"]]);
                    printf('<input type="hidden" name="%s" value="%s" />', $column["name"], $row[$column["name"]]);
                    print('</div><div class="w3-col s2">
                        <a href="settings.php?chg_passwd=true" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-gears"></i></a>
                        </div>');
                }
                else if($column["type"] === "char" && $column["name"] === "password") {
                    printf('<input class="w3-input border" type="password" name="%s" maxlength="%s" value="%s">', $column["name"], $column["char_length"], $row[$column["name"]]);
                }
                else if($column["type"] === "date") {
                    printf('<input class="w3-input border" type="date" name="%s" value="%s">', $column["name"], $row[$column["name"]]);
                }                          
                else if($column["type"] === "int") {
                    printf('<input class="w3-input border" type="number" name="%s" maxlength="%s" value="%s">', $column["name"], $column["num_length"], $row[$column["name"]]);
                }
                else {
                    printf('<input class="w3-input border" type="text" name="%s" value="%s">', $column["name"], $row[$column["name"]]);
                }
                print('</div>
                <div class="clearfix"></div>
                <br>');
            }
        }
        else {
            throw new Exception("Error: table data not found (display_modify_form).");
        }
        print('</div>');
    }

    // Given a foreign key field name this function will set the referenced table name then 
    // query the database by selecting all from that table and return the result.
    public function fkey_query($fkey) {
		
        $this->fkey = $fkey;
        $db = $this->query_object("typefkey_sql");

        if ($db) {
            $this->table = $db->table2;
        }
		else {
			$this->table1 = $this->table2;
			$db = $this->query_object("typefkey_sql");
			if ($db) {
				$this->table = $db->table2;
			}
			else {
				throw new Exception("Foreign key not found: ".$fkey." (fkey_query).");
			}
		}

        $query = $this->query_assoc("select_sql");
        return $query;
    }

    // Function to query the fields of the current table and return the field if there is a match.
    public function fkey_description() {
        $field = NULL;
        $result = $this->query_assoc("onetable_sql");
        if ($result) {
            while ($row = $result->fetch_assoc()) {

                if($row["column_name"] === "name") {
                    $field = "name";
                    break;
                }
                else if($row["column_name"] === "title") {
                    $field = "title";
                    break;
                }
                else if($row["column_name"] === "URL") {
                    $field = "URL";
                    break;
                }
                else if($row["column_name"] === "description") {
                    $field = "description";
                    break;
                }
                else {
                    continue;
                }
            }
        }
        else {
            throw new Exception("Unable to set foreign key field (fkey_description).");
        }    
        return $field;
    }
}

?>