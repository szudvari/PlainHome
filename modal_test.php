<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="page-header">
                    <h2>Sign up</h2>
                </div>

                <!--
                Change the "action" attribute to your back-end URL
                To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
                -->
                <form id="registrationForm" method="post" class="form-horizontal" action="..."
                    data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                    data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                    data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Username</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="username"
                                data-bv-notempty="true"
                                data-bv-notempty-message="The username is required and cannot be empty"

                                data-bv-stringlength="true"
                                data-bv-stringlength-min="6"
                                data-bv-stringlength-max="30"
                                data-bv-stringlength-message="The username must be more than 6 and less than 30 characters long"

                                data-bv-regexp="true"
                                data-bv-regexp-regexp="^[a-zA-Z0-9]+$"
                                data-bv-regexp-message="The username can only consist of alphabetical and number"

                                data-bv-different="true"
                                data-bv-different-field="password"
                                data-bv-different-message="The username and password cannot be the same as each other" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Email address</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="email"
                                data-bv-notempty="true"
                                data-bv-notempty-message="The email address is required and cannot be empty"

                                data-bv-emailaddress="true"
                                data-bv-emailaddress-message="The email address is not a valid" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Password</label>
                        <div class="col-lg-5">
                            <input type="password" class="form-control" name="password"
                                data-bv-notempty="true"
                                data-bv-notempty-message="The password is required and cannot be empty"

                                data-bv-stringlength="true"
                                data-bv-stringlength-min="8"
                                data-bv-stringlength-message="The password must have at least 8 characters"

                                data-bv-different="true"
                                data-bv-different-field="username"
                                data-bv-different-message="The password cannot be the same as username" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Gender</label>
                        <div class="col-lg-5">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="male"
                                        data-bv-notempty="true"
                                        data-bv-notempty-message="The gender is required" /> Male
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="female" /> Female
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="gender" value="other" /> Other
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Date of birth</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" name="birthday" placeholder="YYYY/MM/DD"
                                data-bv-notempty="true"
                                data-bv-notempty-message="The date of birth is required"

                                data-bv-date="true"
                                data-bv-date-format="YYYY/MM/DD"
                                data-bv-date-message="The date of birth is not valid" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <!-- Do NOT use name="submit" or id="submit" for the Submit button -->
                            <button type="submit" class="btn btn-default">Sign up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


<?php
htmlEnd();
?>