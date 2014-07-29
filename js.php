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

function readOnlyUpdateForm ($id) {
    echo <<<EOT
    
<script type="text/javascript">
    var id = $id;
            if (id < 2) {
            $('.floor').prop('readonly', true);
                    $('.door').prop('readonly', true);
                    $('.area').prop('readonly', true);
                    $('.area_ratio').prop('readonly', true);
            }
</script>
EOT;
}

function hideArea ($areaId) {
echo <<<EOT
    
    <script type="text/javascript">
            $("#$areaId").hide();
    </script>
EOT;
}

function validateForm () {
    echo <<<EOT

<script>
	$.validate();
</script>
EOT;
}

function oCostFV ($id) {
echo <<<EOT
<script>
    $('#oCost-$id').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            cost: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required and cannot be empty'
                    }
                }
            }
        }
    }


    });
</script>
EOT;
}