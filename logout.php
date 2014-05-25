<?php
session_start();
session_unset();
session_destroy();
include_once 'js.php';
include_once 'html.php';
include_once 'config.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
echo <<<EOT
<div id="myModal" class="reveal-modal" data-reveal-id="myModal">
			<h1>Reveal Modal Goodness</h1>
			<p>This is a default modal in all its glory, but any of the styles here can easily be changed in the CSS.</p>
			<a class="close-reveal-modal">&#215;</a>
</div>
EOT;
popUp("myModal");
htmlEnd();

//include 'index.php';

?>