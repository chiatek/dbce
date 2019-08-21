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

class navlink extends insert
{
    // Function to display all tables and tables with a foreign key in the current database.
    // Output the result in a select drop down list.
    public function display_table_options() { 
        $parent = $this->query_assoc("parenttable_sql"); 
        $child = $this->query_assoc("childtable_sql");
    
        print('<select class="w3-select border" name="table" onchange="getdata(this.value)">;
        <option value="null" disabled selected>Select table...</option>');
        if ($parent) {
            while($row = $parent->fetch_assoc()) {
                printf('<option value="%s@%s">%s</option>', $row["table_name"], $row["column_name"], $row["table_name"]);
            }
        } 
        if ($child) {
            while($row = $child->fetch_assoc()) {
                printf('<option value="%s@%s.%s">%s and %s</option>', $row["table1"], $row["table2"], $row["foreign_key"], $row["table1"], $row["table2"]);
            }
        }
        print('</select><br><br>');
    }

    // Function to display all fields of the selected tables.
    // Output the result in a select drop down list.
    public function display_column_options($table1, $table2 = null, $pkey = null, $fkey = null) {

        if($table2) {
            $this->table1 = $table1;
            $this->table2 = $table2;
            $column_list = $this->query_assoc("twotables_sql");
            $pk = $this->query_object("pkey_sql");

            printf('<input type="hidden" name="table1" value="%s" />', $table1);
            printf('<input type="hidden" name="table2" value="%s" />', $table2);
            printf('<input type="hidden" name="fkey" value="%s" />', $fkey);

            if ($pk) {
                printf('<input type="hidden" name="pkey" value="%s" />', $pk->column_name);
            }
        }
        else {
            $this->table = $table1;
            $column_list = $this->query_assoc("onetable_sql");

            printf('<input type="hidden" name="table1" value="%s" />', $table1);
            printf('<input type="hidden" name="pkey" value="%s" />', $pkey);
        }
        $result = $column_list->num_rows;
        $count = 1;
    
        while($result > 0 && $count <= 10) {
            
            if ($count == 1) {
                printf('<select class="w3-select border" name="column%s">
                <option value="null" disabled selected>Column %s</option>', $count, $count);
            }
            else {
                printf('<select class="w3-select border" name="column%s">
                <option value="" disabled selected>Column %s</option>', $count, $count);
            }

            if ($column_list) {
                while($row = $column_list->fetch_assoc()) {
                    if($table2) {
                        printf('<option value="%s.%s">%s</option>', $row["table_name"], $row["column_name"], $row["column_name"]);
                    }
                    else {
                        printf('<option value="%s">%s</option>', $row["column_name"], $row["column_name"]);
                    }
                }
            } 
            print('</select><br><br>');

            if($table2) {
                $column_list = $this->query_assoc("twotables_sql");
            }
            else {
                $column_list = $this->query_assoc("onetable_sql");
            }
            $result--;
            $count++;
        } 
    } 

    // Set the table and key to the user selected table from display_table_options().
    public function set_column_options($table) {
        if(strpos($table, "@") && strpos($table, ".")) {
            $link = explode('@', $table);
            $table1 = $link[0];
            $child = $link[1];
    
            $link = explode('.', $child);
            $table2 = $link[0];
            $fkey = $link[1];

            $this->display_column_options($table1, $table2, null, $fkey);
        }
        else {
            $link = explode('@', $_GET['table']);
            $table1 = $link[0];
            $pkey = $link[1];

            $this->display_column_options($table1, null, $pkey);
        }
    }
    
    // Query the database and display all icons with radio buttons.
    public function display_radio_buttons() {
        $icon = $this->query_assoc("icons_sql");
        
        if ($icon) {
            print('<div class="icon-row">');
            while($row = $icon->fetch_assoc()) {  
                print('<div class="icon-column w3-center">'); 
                printf('<i class="%s" style="font-size:48px;"></i>', $row["description"]); 
                if($row["iconID"] === "10001") {
                    printf('<br><input class="w3-radio" type="radio" name="iconID" value="%s" checked>', $row["iconID"]);
                } 
                else {
                    printf('<br><input class="w3-radio" type="radio" name="iconID" value="%s">', $row["iconID"]);
                }            
                print('</div><br><br>');
            }
            print('</div>');
        } 
    }
    
    // Display the nav link wizard.
    public function display_wizard() {
        ?>
        <div class="card-wrapper w3-display-topleft">Add a new link</div>
        <span onclick="this.parentElement.style.display='none'"
            class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>
            
        <hr class="hr"><br>
        
        <form id="regForm" class="card-body" action="navlink.php" method="post">

            <div class="w3-row">
                <div class="w3-col step w3-bottombar w3-padding">1. Select Table</div>
                <div class="w3-col step w3-bottombar w3-padding">2. Select Columns</div>
                <div class="w3-col step w3-bottombar w3-padding">3. Choose a Name</div>
                <div class="w3-col step w3-bottombar w3-padding">4. Choose Icon</div>
                <div class="w3-col step w3-bottombar w3-padding">5. Finish</div>
            </div>					
            <br><br>
            
            <!-- One "tab" for each step in the form: -->

            <div class="tab">
                <h3>Select your table.</h3><br>
                <?php $this->display_table_options(); ?>
                <br>
            </div>

            <div class="tab">
                <h3>Select columns from the table (limit 10).</h3><br>
                <div id="ajax_output"></div>
                <br>
            </div>

            <div class="tab">
                <h3>Name your link</h3>
                <p><input class="w3-input border" name="linkname" placeholder="Link name..."></p><br>
            </div>            
            
            <div class="tab">
                <h3>Select your icon.</h3>
                <?php $this->display_radio_buttons(); ?>
            </div>						

            <div class="tab">
                <h3>Results per page</h3>
                <p><input class="w3-input border" name="limit" value="10"></p>
                <h3>Maximum characters per field</h3>
                <p><input class="w3-input border" name="chars" value="150"></p><br>
                <h4><input class="w3-check" type="checkbox" name="dashboard" value="Y"><label> Add to dashboard instead of navigation menu</label></h4><br><br>
                <h3>To add this link to the navigation menu click finish.</h3>
            </div>

            <br>
            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" id="prevBtn" class="w3-button w3-round-medium teal hover-teal" onclick="nextPrev(-1)">Previous</button>
                    <button type="button" id="nextBtn" class="w3-button w3-round-medium teal hover-teal" onclick="nextPrev(1)">Next</button>
                </div>
            </div>

            <!-- Circles which indicates the steps of the form: -->
            <div style="text-align:center;margin-top:40px;">
                <span class="indicator"></span>
                <span class="indicator"></span>
                <span class="indicator"></span>
                <span class="indicator"></span>
                <span class="indicator"></span>
            </div>
        </form>
        <?php
    }  
}

?>