<?php

function showContent ($areaId, $buttonId) {
    echo <<<EOT
     
   <script type="text/javascript">
            $("#$areaId").hide();
            $("#$buttonId").click(function()
            {
                $("#$areaId").fadeIn(500);
                $("#$buttonId").hide();
            });
   </script>
            
EOT;
}

function loggedOut () {
    echo <<<EOT
     $('element_to_pop_up').bPopup({
            modalClose: false,
            opacity: 0.6,
            positionStyle: 'fixed' 
        });
EOT;
}

