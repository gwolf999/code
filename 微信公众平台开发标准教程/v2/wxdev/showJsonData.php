<?php
header('Content-type:text/html;charset=utf-8');
echo "<pre>";
print_r(json_decode(file_get_contents('./show_data.json') , true));
?>
