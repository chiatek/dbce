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

class view extends html
{
	protected $page = 1;
	protected $limit = 10;
	protected $maxchars = 150;
	protected $order = "ASC";
	protected $dashboard = false;

	public function set_page($page) {
		$this->page = $page;
	}

	public function set_order($order) {
		$this->order = $order;
	}

    public function set_table1($table1, $pkey, $pid) {
        $this->table1 = $table1;
        $this->pkey = $pkey;
        $this->pid = $pid;
    }

    public function set_table2($table2, $fkey, $fid) {
        $this->table2 = $table2;
        $this->fkey = $fkey;
        $this->fid = $fid;
	}
	
	// Return the offset for pagination.
	public function get_offset() {
		if($this->page != 1) {
			return $this->limit * ($this->page - 1);
		}
		else {
			return 0;	
		}
	}

	// Return the page count given the record count from a result set.
	public function get_page_count($rec_count) {
		$page_count = ceil($rec_count / $this->limit);
		return $page_count;
	}

	// Return the first record count of the current page.
	public function get_first_page_rec() {
		$first_rec = ($this->page * $this->limit) - ($this->limit - 1);
		return $first_rec;
	}

	// Return the last record count of the current page.
	public function get_last_page_rec($page_count, $rec_count, $offset) {
		if($page_count == $this->page) {
			$last_rec = $offset + ($rec_count - $offset);
		}
		else {
			$last_rec = $this->page * $this->limit;
		}
		return $last_rec;
	}

	// Get and set link fields from database table portal.links where linkid = user selected link.
	public function get_link_fields() {
		$field = $this->query_assoc_row("linkfields_sql");
		if ($field) {
			$this->set_table1($field["table1"], $field["pkey"], 0);
			$this->set_table2($field["table2"], $field["fkey"], 0);
			$this->order = $field["orderqry"];
			$this->limit = $field["limitqry"];
			$this->linkname = $field["linkname"];
			$this->linkicon = $field["description"];
			$this->maxchars = $field["characters"];

			return $field;
		}
		else {
			throw new Exception("An unexpected error has occured (get_link_fields).");
		}
	}

	public function set_table_data() {
		$field = $this->get_link_fields();
		$sql = $this->set_sql($field);
		$this->display_table($sql);
	}

	// Assemble SQL statement and display database result set in table view.
	public function display_table($sql) {	
		$offset = $this->get_offset();
		$rec_count = $this->get_row_count($sql);
		$page_count = $this->get_page_count($rec_count);
		$first_rec = $this->get_first_page_rec();
		$last_rec = $this->get_last_page_rec($page_count, $rec_count, $offset);

		$sql = $this->set_order_sql($sql, $this->pkey, $this->order);
		$sql = $this->set_offset_sql($sql, $this->limit, $offset);
		$table_data = $this->query_assoc_sql($sql);
		
		printf('<div class="w3-panel w3-card w3-display-container w3-white window">
			<div class="card-wrapper w3-display-topleft"><i class="%s"></i>  %s​</div>
			<span onclick="this.parentElement.style.display=\'none\'"
			class="w3-button w3-small w3-hover-white w3-display-topright"><i class="fa fa-remove window-icons"></i></span>', $this->linkicon, $this->linkname);
							
		print('<hr class="hr"><br>
			<form class="card-body card-wrapper" action="view.php" method="post" id="delete">');

		if(!$this->dashboard) {	
			print('<div class="w3-bar border light-grey card-header w3-center">
				<button Onclick="return confirm_delete();" type="submit" form="delete" id="delete-btn" class="w3-button w3-left teal hover-teal" value="1"><i class="fa fa-trash-o"></i></button>');
			if($this->order === "DESC") {
				printf('<a href="view.php?link=%s&order=ASC" class="w3-button header-btn w3-left teal hover-teal"><i class="fa fa-sort-alpha-asc"></i></a>
					<a href="view.php?link=%s&order=DESC" class="w3-button header-btn w3-left w3-disabled teal hover-teal"><i class="fa fa-sort-alpha-desc"></i></a>', $this->linkid, $this->linkid);
			}
			else {
				printf('<a href="view.php?link=%s&order=ASC" class="w3-button header-btn w3-left w3-disabled teal hover-teal"><i class="fa fa-sort-alpha-asc"></i></a>
					<a href="view.php?link=%s&order=DESC" class="w3-button header-btn w3-left teal hover-teal"><i class="fa fa-sort-alpha-desc"></i></a>', $this->linkid, $this->linkid);							
			}
			printf('<h3 class="bold card-title">%s</h3> <h4 class="bold card-title-results"> (%s - %s of %s)</h4>', $this->linkname, $first_rec, $last_rec, $rec_count);
			if($this->table2) {
				printf('<a href="insert.php?link=%s&ptbl=%s&pk=%s&ftbl=%s&fk=%s" class="w3-button w3-right teal hover-teal"><i class="fa fa-plus-square-o"></i></a>
				<input type="hidden" name="link" value="%s" />', $this->linkid, $this->table1, $this->pkey, $this->table2, $this->fkey, $this->linkid);
			}
			else {
				printf('<a href="insert.php?link=%s&ptbl=%s&pk=%s" class="w3-button w3-right teal hover-teal"><i class="fa fa-plus-square-o"></i></a>
				<input type="hidden" name="link" value="%s" />', $this->linkid, $this->table1, $this->pkey, $this->linkid);
			}
			print('</div>
			<div class=clearfix"></div>');
		}

		if ($table_data) {
			print('<div class="w3-responsive">
				<table class="w3-table-all w3-hoverable">');
				if(!$this->dashboard) {
					print('<th></th>');
				}
				while ($field = $table_data->fetch_field()) {
					if ($field->name == "pkey" || $field->name == "fkey") {
						print("<th></th>");
					}
					else {
						printf('<th>%s</th>', $field->name);
					}	
				}	

				$field = $table_data->fetch_fields();										
				while ($row = $table_data->fetch_assoc()) {
					print("<tr>");
					
					if($row["pkey"] && !$this->dashboard) {
						printf('<td><input class="w3-check delete-chk" name="delete[]" type="checkbox" value="%s.%s WHERE %s=\'%s\'"></td>', $this->database, $this->table1, $this->pkey, $row["pkey"]);
					}	
					
					foreach ($field as $val) {								
						if($this->table2) {
							if ($val->name == "pkey" || $val->name == "fkey") {
								print('<td></td>');
							}
							else {
								printf('<td><a href="modify.php?link=%s&ptbl=%s&pk=%s&pid=%s&ftbl=%s&fk=%s&fid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $this->table2, $this->fkey, $row["fkey"], $this->format_column($row[$val->name], $this->maxchars));
							}
						}
						else {
							if ($val->name == "pkey") {
								print('<td></td>');
							}
							else {
								printf('<td><a href="modify.php?link=%s&ptbl=%s&pk=%s&pid=%s" class="card-link">%s</a></td>', $this->linkid, $this->table1, $this->pkey, $row["pkey"], $this->format_column($row[$val->name], $this->maxchars));
							}
						}
					}
					print('</tr>');
				}
				$table_data->close();
				
			print('</table></div>');
		}

		print('<br><div class="w3-center">
			<div class="w3-bar">');

		$this->pagination($page_count, $rec_count, "view.php");
									
		print('</div></div></form></div>');
	}

	// Function to display pagination.
	public function pagination($page_count, $rec_count, $page_name, $search = "") {

		if($this->page > 1 && $this->page < $page_count) { // In between first and last page
			printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">&laquo;</a>', $page_name, $this->linkid, $search, $this->page - 1);
			for($i = 1; $i <= $page_count; $i++) {
				if($i == $this->page) {
					printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button teal hover-teal">%s</a>', $page_name, $this->linkid, $search, $i, $i);
				}
				else {
					printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">%s</a>', $page_name, $this->linkid, $search, $i, $i);
				}
			}
			printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">&raquo;</a>', $page_name, $this->linkid, $search, $this->page + 1);
		}
		else if($this->page == $page_count && $rec_count > $this->limit) { // Last Page
			printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">&laquo;</a>', $page_name, $this->linkid, $search, $this->page - 1);
			for($i = 1; $i <= $page_count; $i++) {
				if($i == $this->page) {
					printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button teal hover-teal">%s</a>', $page_name, $this->linkid, $search, $i, $i);
				}
				else {
					printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">%s</a>', $page_name, $this->linkid, $search, $i, $i);
				}
			}
		}
		else if($this->page == $page_count) { // Only one page
			printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button teal hover-teal">%s</a>', $page_name, $this->linkid, $search, $this->page, $this->page);
		}
		else { //First Page
			for($i = 1; $i <= $page_count; $i++) {
				if($i == $this->page) {
					printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button teal hover-teal">%s</a>', $page_name, $this->linkid, $search, $i, $i);
				}
				else {
					printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">%s</a>', $page_name, $this->linkid, $search, $i, $i);
				}
			}
			printf('<a href="%s?link=%s&search=%s&page=%s" class="w3-button hover-teal">&raquo;</a>', $page_name, $this->linkid, $search, $this->page + 1);
		}
	}
}

?>