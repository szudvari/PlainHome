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

function popUp ($message) {
    echo <<<EOT
    <script type="text/javascript">
                   BootstrapDialog.show({
            message: '$message'
        });
    </script>
   
    
EOT;
}

