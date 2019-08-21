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

class dashboard extends settings
{
    // Display Google Analytics
    public function display_windows() {
        print('<div class="window-left">
                <div class="w3-panel w3-card w3-display-container w3-white window">
                    <div class="card-wrapper w3-display-topleft">Window 1</div>
                    <span onclick="this.parentElement.style.display=\'none\'"
                        class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>	
                    <hr class="hr">
                    <div class="card-wrapper">
                        <h1>Window 1</h1>
                        <h6>Insert code here</h6>
                        <br>
                    </div>
                </div>
            </div>	
            <div class="window-right">
                <div class="w3-panel w3-card w3-display-container w3-white window">
                    <div class="card-wrapper w3-display-topleft">Window 2</div>
                    <span onclick="this.parentElement.style.display=\'none\'"
                        class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>	
                    <hr class="hr">
                    <div class="card-wrapper">
                        <h1>Window 2</h1>
                        <h6>Insert code here</h6>
                        <br>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>');
    }
    
    // Display table on dashboard for all user nav links where dashboard = Y.
    public function display_tables() {
        $result = $this->query_assoc("dashboard_sql");
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $this->dashboard = true;
                $this->set_link($row["linkID"]);
                $this->set_table_data();
            }
        }
        else {
            print('<div class="w3-panel w3-card w3-display-container w3-white window">
                    <div class="card-wrapper w3-display-topleft">New Window​</div>
                    <span onclick="this.parentElement.style.display=\'none\'"
                        class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>
                    
                    <hr class="hr"><br>
                    
                    <div class="card-body">
                        <h4>Click <a href="navlink.php">here</a> to add content to your dashboard. 
                        Select "Add to dashboard instead of navigation menu."</h4><br>
                    </div></div>');
        }
    }
}

?>