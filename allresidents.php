<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';
@$id= $_POST['id'];
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if (isset($_GET["active"]))
{
    popUp("Felhasználó státusza sikeresen módosítva");
}
if (isset($_GET["admin"]))
{
    popUp("Felhasználó admin rangja sikeresen módosítva");
}
if (isset($_GET["password"]))
{
    popUp("Felhasználó jelszava sikeresen módosítva");
}
echo <<<EOT
   <script type="text/javascript">
            $(document).on("click", ".open-AddBookDialog", function () {
     var myBookId = $(this).data('id');
     $(".modal-body #bookId").val( myBookId );
});
</script>
EOT;
$con = connectDb();
listResidents();
closeDb($con);

htmlEnd();
