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

class settings extends navlink
{
    private $change_password = false;

    // User has clicked the change password button, set change_password to true to enable display_change_password_form().
    public function display_change_password() {
        $this->change_password = true;
    }

    // Display settings navigation tab
    public function display_navigation_tab() {

        print('<div class="w3-panel w3-card w3-display-container w3-white window">
            <div class="w3-bar blue">
            <button class="w3-bar-item w3-button tablink teal hover-teal" onclick="open_setting(event, \'user\')">User Settings</button>
            <button class="w3-bar-item w3-button tablink hover-teal" onclick="open_setting(event, \'avatar\')">Avatar</button>
            <button class="w3-bar-item w3-button tablink hover-teal" onclick="open_setting(event, \'sidenav\')">Nav Links</button>
            <button class="w3-bar-item w3-button tablink hover-teal" onclick="open_setting(event, \'about\')">About</button>
            </div><br><br>');

        print('<div id="user" class="w3-container setting">');
        $this->user_settings();
        print('</div>');

        print('<div id="avatar" class="w3-container setting" style="display:none">');
        $this->avatar_settings();
        print('</div>');

        print('<div id="sidenav" class="w3-container setting" style="display:none">');
        $this->sidenav_settings();
        print('</div>');

        print('<div id="about" class="w3-container setting" style="display:none">');
        $this->about_settings();
        print('</div></div>');
    }

    // Query portal.users table and display the form.
    public function user_settings() {
        print('<form action="settings.php" method="post" id="settings">');

        $this->table = "users";

        $user_field = $this->query_assoc("clienttype_sql");
        $user_record = $this->query_assoc("clientsettings_sql");

        if($this->change_password) {
            $this->display_change_passwd_form();
        }
        else {
            print('<input type="hidden" name="user" value="true" />');
            $this->display_modify_form($user_field, $user_record, $this->table);
        }
        
        print('<div class="card-body"><br><hr><br>
            <p><button class="w3-button w3-round-medium teal hover-teal save-btn">Save changes</button>​ <a href="index.php" class="w3-button w3-white w3-hover-white border w3-round-medium">Cancel</a></p>
            </div></form>');
    }

    // Query portal.users table and display all avatars with radio buttons.
    public function avatar_settings() {
        $avatar = $this->query_assoc("avatar_sql");
        $user = $this->query_object("user_sql");

        print('<form action="settings.php" method="post" id="settings">');

        if ($user) {
            $avatar_id = $user->avatarID;
        }
        else {
            $avatar_id = 20001;
        }
        
        if ($avatar) {
            print('<div class="image-row">');
            while($row = $avatar->fetch_assoc()) {  
                print('<div class="image-column w3-center">'); 
                printf('<img class="img-avatar" src="%s">', $row["image"]); 
                if($row["avatarID"] === $avatar_id) {
                    printf('<br><input class="w3-radio" type="radio" name="avatarID" value="%s" checked>', $row["avatarID"]);
                } 
                else {
                    printf('<br><input class="w3-radio" type="radio" name="avatarID" value="%s">', $row["avatarID"]);
                }            
                print('</div><br><br>');
            }
            print('</div>');
        } 

        print('<div class="card-body"><br><hr><br>
        <p><button class="w3-button w3-round-medium teal hover-teal save-btn">Save changes</button>​ <a href="index.php" class="w3-button w3-white w3-hover-white border w3-round-medium">Cancel</a></p>
        </div></form>');
    }

    // Query database table portal.links and call display_links_table().
    public function sidenav_settings() {

        $this->table1 = "links";
        $this->pkey = "linkID";   

        $link_record = $this->query_assoc("viewlinks_sql");

        $this->display_links_table($link_record);
    }

    // Database and Portal version info for about tab.
    public function about_settings() {
        print('<div class="card-body"><h4>');

        printf("<strong>DBCE version:</strong> %s", $this->version."<br>");
        printf("<strong>Client library version:</strong> %d<br>", mysqli_get_client_version());

        $this->conn_info();

        print("</h4></div>");
    }

    // Display result set from portal.links in table view.
    public function display_links_table($table_data) {	
                
        print('<form action="settings.php" method="post" id="delete">
            <div class="card-body card-wrapper"><br>
            <div class="w3-bar border light-grey card-header w3-center">');

        if ($table_data) {
            print('<button Onclick="return confirm_delete();" type="submit" form="delete" id="delete-btn" class="w3-button w3-left teal hover-teal" value="1"><i class="fa fa-trash-o"></i></button>');
        }
        else {
            print('<button Onclick="return confirm_delete();" type="submit" form="delete" id="delete-btn" class="w3-button w3-left w3-disabled teal hover-teal" value="1"><i class="fa fa-trash-o"></i></button>');
        }

        printf('<h3 class="bold card-title">%s</h3>', "Links");

        printf('<a href="navlink.php" class="w3-button w3-right teal hover-teal"><i class="fa fa-plus-square-o"></i></a>
        <input type="hidden" name="link" value="%s" />', $this->linkid);

        print('</div>
        <div class=clearfix"></div>');
        
        if ($table_data) {
            print('<div class="w3-responsive">
                <table class="w3-table-all w3-hoverable">');

            printf('<th></th><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th>', "linkID", "linkname", "table1", "table2", "dashboard");
            while ($row = $table_data->fetch_assoc()) {								
                print('<tr>');
                if($row["pkey"]) {
                    printf('<td><input class="w3-check delete-chk" name="delete[]" type="checkbox" value="%s.%s WHERE %s=%s"></td>', $this->dbms_database, $this->table1, $this->pkey, $row["pkey"]);
                }			
                printf('<td><a href="settings.php?link=%s&ptbl=%s&pk=%s&pid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $row["linkID"]);
                printf('<td><a href="settings.php?link=%s&ptbl=%s&pk=%s&pid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $row["linkname"]);
                printf('<td><a href="settings.php?link=%s&ptbl=%s&pk=%s&pid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $row["table1"]);
                printf('<td><a href="settings.php?link=%s&ptbl=%s&pk=%s&pid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $row["table2"]);
                printf('<td><a href="settings.php?link=%s&ptbl=%s&pk=%s&pid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $row["dashboard"]);

                printf('</tr>'); 
            }
            printf('</table></div>');
        }
        else {
            print('<br><h4>Click <a href="navlink.php">here</a> to add links to your side navigation menu.</h4>');
        }
        print('</div></form><br>');
    }
}

?>