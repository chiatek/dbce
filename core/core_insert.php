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

class insert extends modify
{
    // Query the database of the current table or two tables.
    // Call display_insert_form() with the field name and result.
    public function set_insert_form() {

        if($this->table2) {
            $this->table = $this->table1;
            $tbl1_field = $this->query_assoc("datatype_sql");
            $tbl1_pri_val = $this->query_assoc("autoincrement_sql");

            $this->table = $this->table2;
            $tbl2_field = $this->query_assoc("datatype_sql");
            $tbl2_pri_val = $this->query_assoc("autoincrement_sql");

            $this->display_insert_form($tbl1_field, $tbl1_pri_val, $this->table1);
            $this->display_insert_form($tbl2_field, $tbl2_pri_val, $this->table2);
        }
        else {
            $this->table = $this->table1;
            $tbl1_field = $this->query_assoc("datatype_sql");
            $tbl1_pri_val = $this->query_assoc("autoincrement_sql");

            $this->display_insert_form($tbl1_field, $tbl1_pri_val, $this->table1);
        }   
    }

    // Function to display the database table field and foreign key values in form view.
    public function display_insert_form($tbl_field, $tbl_pri_val, $table) {

        print ('<form action="insert.php" method="post" id="insert">');

        printf('<div class="w3-panel w3-card w3-display-container w3-white window">
        <div class="card-wrapper w3-display-topleft">%s​</div>
        <span onclick="this.parentElement.style.display=\'none\'"
        class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span><hr class="hr"><br>', ucfirst($table));

        if($table == $this->table2) {
            printf('<input type="hidden" name="tbl" value="%s" />', $this->table2);
            printf('<input type="hidden" name="key" value="%s" />', $this->fkey);
            printf('<input type="hidden" name="link" value="%s" />', $this->linkid);
        }
        else {
            printf('<input type="hidden" name="tbl" value="%s" />', $this->table1);
            printf('<input type="hidden" name="key" value="%s" />', $this->pkey);
            printf('<input type="hidden" name="link" value="%s" />', $this->linkid);
        }

        print('<div class="card-body"><br>');

        if ($tbl_field) { 
            if ($tbl_pri_val) {
                $val = $tbl_pri_val->fetch_object();
            }
            while ($column = $tbl_field->fetch_assoc()) {
                printf('<div class="w3-col m2">
                <h5 class="card-txt">%s</h6>
                </div>
                <div class="w3-col m10">', $column["name"]);
                if($column["ckey"] === "PRI") {
                    printf('<input class="w3-input border" type="text" name="%s" value="%s">', $column["name"], $val->auto_increment);
                }
                else if($column["ckey"] === "MUL") {
                    $fkey = $this->fkey_query($column["name"], $table);
                    $value = $this->fkey_description();
                    if (!$fkey) {
                        printf('<input class="w3-input border" type="text" name="%s" maxlength="%s">', $column["name"], $column["char_length"]);
                    } 
                    else {
                        printf('<select class="w3-select border" name="%s" required>', $column["name"]);
                        printf('<option value="" disabled selected></option>');
                        while($fkey_row = $fkey->fetch_assoc()) {
                            if ($value) {
                                printf('<option value="%s">%s - %s</option>', $fkey_row[$column["name"]], $fkey_row[$column["name"]], $fkey_row[$value]);
                            }
                            else {
                                printf('<option value="%s">%s</option>', $fkey_row[$column["name"]], $fkey_row[$column["name"]]);
                            }
                        }
                        print('</select>');
                    }
                }                                  
                else if($column["type"] === "text" && $column["name"] === "html" || $column["name"] === "description") {
                    printf('<textarea name="%s"></textarea>', $column["name"]);
                }
                else if($column["type"] === "text") {
                    printf('<textarea id="summernote" name="%s"></textarea>', $column["name"]);
                }
                else if($column["type"] === "varchar" && $column["name"] === "video") {
                    printf('<div class="w3-col s11">
                        <input class="w3-input border" id="video-upload" type="text" name="%s" maxlength="%s">', $column["name"], $column["char_length"]);
                    print('</div><div class="w3-col s1">
                        <button id="btn-video" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-file-movie-o"></i></button>
                        </div>
                        <div class="file-upload">
                        <input type="file" id="upload-video" />
                        </div>');
                }
                else if($column["type"] === "varchar" && ($column["name"] === "image" || $column["name"] === "poster")) {
                    printf('<div class="w3-col s11">
                        <input class="w3-input border" id="image-upload" type="text" name="%s" maxlength="%s">', $column["name"], $column["char_length"]);
                    print('</div><div class="w3-col s1">
                        <button id="btn-image" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-file-picture-o"></i></button>
                        </div>
                        <div class="file-upload">
                        <input type="file" id="upload-image" />
                        </div>');
                }
                else if($column["type"] === "varchar" && $column["name"] === "title") {
                    printf('<input class="w3-input border" type="text" name="%s" maxlength="%s">', $column["name"], $column["char_length"]);
                }
                else if($column["type"] === "varchar") {
                    printf('<input class="w3-input border" type="text" name="%s" maxlength="%s">', $column["name"], $column["char_length"]);
                }
                else if($column["type"] === "char" && $column["name"] === "password") {
                    printf('<input class="w3-input border" type="password" name="%s" maxlength="%s">', $column["name"], $column["char_length"]);
                }
                else if($column["type"] === "date") {
                    printf('<input class="w3-input border" type="date" name="%s" value="%s">', $column["name"], date("Y-m-d"));
                }                          
                else if($column["type"] === "int") {
                    printf('<input class="w3-input border" type="number" name="%s" maxlength="%s">', $column["name"], $column["num_length"]);
                }
                else {
                    printf('<input class="w3-input border" type="text" name="%s">', $column["name"]);
                }
                print('</div>
                <div class="clearfix"></div>
                <br>');
            }
        }
        else {
            throw new Exception('An error has occured processing your request (display_insert_form).');
        }

        printf('<div class="card-body"><br><hr><br>
        <p><button class="w3-button w3-round-medium teal hover-teal save-btn">Save changes</button>​ <a href="view.php?link=%s" class="w3-button w3-white w3-hover-white w3-round-medium border">Cancel</a></p>
        </div></form>', $this->linkid); 

        print('</div></div>');
    }
}

?>