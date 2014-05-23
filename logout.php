<?php
session_start();
session_unset();
session_destroy();
echo '<p id="logout">Sikeresen kijelentkezett!</p>';
include 'index.php';
?>