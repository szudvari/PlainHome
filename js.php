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
     $(document).ready(function(){
      show.($('#$elementId').reveal();)
  });
    </script>
    
EOT;
}

