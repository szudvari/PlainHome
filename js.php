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

function popUp ($elementId) {
    echo <<<EOT
    <script type="text/javascript">
     $(#$elementId).bPopup({
            modalClose: false,
            opacity: 0.6,
            positionStyle: 'fixed' 
        });
    </script>
    
EOT;
}

