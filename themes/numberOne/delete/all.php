<?php

if (session_status () !== PHP_SESSION_ACTIVE)
	session_start ();

if(file_exists($_SESSION['autoload'])) {
	require_once($_SESSION['autoload']);
}


Main\return_new_dialogbox("", "200", "200", "-1", "-1", "delete/delete_all.php", "", true);

?>

<script>
<?php 
$to_index = str_replace(\Main\info()['root_path'], "", \Main\info()['active_theme_path']) . "/index.php";
$to_pass_new_vars = str_replace(\Main\info()['root_path'], "", \Main\info()['active_theme_path']) . "/required/pass_new_variables.php";
$to_index_table = str_replace(\Main\info()['root_path'], "", \Main\info()['active_theme_path']) . "/required/index_table.php";
echo "var php_index = '{$to_index}';";
echo "var php_pass = '{$to_pass_new_vars}';";
echo "var to_index_table = '{$to_index_table}';";
?>
var delete_button;
var index_table;

function update_table() {
	var body_content = document.getElementById("body_content");
	var nav_info = document.getElementById("nav-info");
	$(body_content).load(window.location.href + php_index);
	$(nav_info).html("<b>Home</b>");
	$(index_table).load(window.location.href + to_index_table);
}

$(document).ready(function() {
	delete_button = document.getElementById('delete_submit');
	index_table = document.getElementById('body_table');
	$(delete_button).click(function () {
		var output = new Array("delete_all");
		$.post(
			php_pass,
			{ output: output },
			function(data) {
				$('#dialogbox_echo').html(data);
			}
		);
		update_table();
	});
});


</script>