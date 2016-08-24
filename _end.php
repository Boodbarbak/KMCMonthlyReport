<?php
$scriptTime = round(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"],2);
echo "<br>\r\n<br>\r\nScript processed in $scriptTime seconds";
