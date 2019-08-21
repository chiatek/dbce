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

class search extends view
{
    protected $limit = 5;

    public function set_query($query) {
        $this->query = $query;
	}
	
	public function set_search() {
		// If a search query has been set display the result otherwise display the search form.
		if (isset($this->query)) {
			$this->display_search();
		}
		else {
			print('<div class="w3-panel w3-card w3-display-container w3-white window">	
			<div class="card-wrapper"><i class="fa fa-search"></i> Search</div>
			<span onclick="this.parentElement.style.display=\'none\'"
			class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>');
	
			print('<hr><br>
			<form class="card-body card-wrapper" action="search.php" method="get" id="search">');
	
			print('<div class="w3-col s10">
			<input class="input border" type="text" name="search">');
			print('</div><div class="w3-col s2">
				<button id="search" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-search"></i></button>
				</div><br><br><br><br>
				</div>');
		}
	}

	// Prepare search for pagination and display results with call to set_search_sql()
	public function display_search() {
        $offset = $this->get_offset();
        $result_count = $this->set_search_sql(0, 0, true);
        $page_count = $this->get_page_count($result_count);
        $first_rec = $this->get_first_page_rec();
        $last_rec = $this->get_last_page_rec($page_count, $result_count, $offset);

		print('<div class="w3-panel w3-card w3-display-container w3-white window">	
		<div class="card-wrapper"><i class="fa fa-search"></i> Search Results</div>
		<span onclick="this.parentElement.style.display="none"
		class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>');

		print('<hr><br>
		<form class="card-body card-wrapper" action="search.php" method="get" id="search">');

		printf('<div class="w3-col s10">
		<input class="input border" type="text" name="search" value="%s">', $this->query);
		print('</div><div class="w3-col s2">
			<button id="search" class="w3-button w3-round-small w3-right teal hover-teal" style="font-size:18px"><i class="fa fa-search"></i></button>
			</div>');

		print('<div class="clearfix"></div>');
		if($result_count == 0) {
			print("<br><br>No results found.");
		}
		else {
			printf("<br><i>(results %s - %s of %s)</i><br><br>", $first_rec, $last_rec, $result_count);
			$result_total = $this->set_search_sql($first_rec, $last_rec);
		}

		print('<br><div class="w3-center">
		<div class="w3-bar">');

        $this->pagination($page_count, $result_count, "search.php", $this->query);

		print('</div></div></form></div>');
	}

	// Function to query all tables in the database select data containing words "like" the search term.
	public function set_search_sql($first_rec, $last_rec, $rec_count = false) {
		$count = 0;
		$result_tbl = $this->query_assoc("parenttable_sql");

		if ($result_tbl) {
			while ($table = $result_tbl->fetch_assoc()) {
				$this->table = $table["table_name"];
				$this->field1 = $table["column_name"];
				$result_fld = $this->query_assoc("onetable_sql");
				if ($result_fld) {
					while ($field = $result_fld->fetch_assoc()) {

						$this->field2 = $field["column_name"];
						$result_val = $this->query_assoc("search_sql");
						
						if($result_val) {

							while ($value = $result_val->fetch_assoc()) {
                                $count++;
                                if(!$rec_count) {
									if($count >= $first_rec && $count <= $last_rec) {
										$summary = $this->get_summary($value[$field["column_name"]]);
										printf('<i><small>id: %s / table: %s / field: %s</small></i>', $value[$table["column_name"]], $table["table_name"], $field["column_name"]);
										if($field["column_name"] == "html") {
											printf('<br><a href="modify.php?link=0&ptbl=%s&pk=%s&pid=%s">%s</a>', $table["table_name"], $table["column_name"], $value[$table["column_name"]], $this->format_search($summary));
										}
										else {
											printf('<br><a href="modify.php?link=0&ptbl=%s&pk=%s&pid=%s">%s</a>', $table["table_name"], $table["column_name"], $value[$table["column_name"]], $summary);
										}
									}
								}
							}
						}
					}
				}
			}	
		}
		else {
			throw new Exception("An unexpected error has occured (set_search_sql).");
		}
		return $count;
	}
}

?>