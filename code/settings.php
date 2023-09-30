<?php

require_once('processing.php');
credentialsCheck();

// in case of no budget record for the current month, create a one
Budget::create_budget_record(get_id());

// Handling settings changes
if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['consumingalert']) && !empty($_POST['goal']) && !empty($_POST['initbudget'])) {
    User::update_user(get_id(), $_POST['username'], $_POST['consumingalert']);
    Budget::update_budget(get_id(), $_POST['initbudget'], $_POST['goal']);
}

// retrieve current user
$user = User::find_user($_SESSION['id']);

// retrieve current month budget
$month = date('m');
$year = date('Y');
$budget = Budget::budget_details($_SESSION['id'], $month,$year);


// retrieving some values to be used in html elements
$alert_value = $user->getAlert();
$username = get_username();
$email = $user->getEmail();
$goal = $budget->getGoal();
$initial = $budget->getInitial();


require_once('head.php');
$place = 'settings';

?>


<body>
    <div class="row m-0">

        <?php
        require_once('sidenav.php'); //  desktop view
        require_once('mobilenav.php') // mobile view
        ?>
        <div class="settings" style="padding-left:280px;">

            <h1 class="text-success mt-4 ps-4">
                Hello!
            </h1>
            <hr>
            <div class="d-flex justify-content-evenly">

                <form class="row g-3 mt-4 ps-4 settings-form" method="post" action="settings.php">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?= $username ?>" name="username">
                        </div>
                    </diV>
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" value="<?= $email ?>" name="email" readonly="readonly">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Initial Budget for Current Month:</label>
                            <input type="text" class="form-control" id="initbudget" value="<?= $initial ?>" name="initbudget">
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Alert at Consuming:</label>
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control" id="consumingalert" value="<?= $alert_value ?>" name="consumingalert">
                                <div>&nbsp;%</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Goal to Save:</label>
                            <input type="text" class="form-control" id="goal" value="<?= $goal ?>" name="goal">
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-success" type="submit">Apply Changes</button>
                    </div>
                </form>

                <div class="settings-img">
                    <img src="https://media.istockphoto.com/id/1188398455/vector/happy-family-in-front-of-their-house.jpg?s=170667a&w=0&k=20&c=1Kkkya8OWjhMJ0ZtrPU-gaWqvjM-XpHJzV8h8eWvR50=">
                </div>

            </div>

        </div>

    </div>
</body>

</html>