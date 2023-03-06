<?php

$chave = filter_input(INPUT_GET, "chave", FILTER_SANITIZE_STRING);
echo $chave;
?>