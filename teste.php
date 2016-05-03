<?php

$message = "Line 1\nLine 2\nLine 3";
$message = wordwrap($message, 70);
mail('alex.e@oi.net.br', 'My Subject', $message);
?>