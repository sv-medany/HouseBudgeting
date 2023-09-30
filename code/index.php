<?php
require_once('processing.php');

// authentication
if (!empty($_POST['signup'])) {
    signupProcessing();
} elseif (!empty($_POST['login'])) {
    loginProcessing();
} else {
    credentialsCheck();
}

// in case of no budget record for the current month, create a one
Budget::create_budget_record(get_id());


$month = date('m'); // gets current month
$year = date('Y'); // gets current month


// Adding purchase handling
if (!empty($_POST['price']) && !empty($_POST['item'])) {
    $flag = Purchase::add_purchase($month,get_id(),$year);
    if ($flag == false){
        echo "<script>alert('Purchase Recording Has Failed For Some Reason')</script>";
    }
}



$user = User::find_user(get_id()); // retrieve current user
$budget = Budget::budget_details(get_id(), $month,$year); // retrieve current month budget

// some values to be used in html elements
$alert_value = $user->getAlert();
$username = get_username();
$initial = $budget->getInitial();
$consumed = $budget->getConsumed();
$goal = $budget->getGoal();
$current = $initial - $consumed;

$mostPurchasedCategory = Purchase::most_purchased_category($month,get_id(),$year);
$payCount = Purchase::visa_cash_count($month,get_id(),$year);
$visa = $payCount["visa"];
$cash = $payCount["cash"];

// handle alert case
$alert = ($initial) * ($user->getAlert() / 100) <= $consumed ? 1 : 0; // to be retrieved from a function


// related to UI
require_once('head.php');
$place = 'index';
?>


<body>
    <div class="row m-0">

        <?php
        require_once('sidenav.php'); //  desktop view
        require_once('mobilenav.php') //  mobile view
        ?>
        <div class="overview" style="padding-left:280px; padding-right:0;">
            <!-- Landing - retrieve name from database-->
            <div class="text-center mt-5 landing">
                <h1 class="text-success" style="font-size:5.5rem;"> Hello, <?= $username?>!</h1>
                <div class="text-secondary" style="font-size:1.5rem;">Manage Your House Budget Fast and Smoothly ...</div>
            </div>

            <!-- remaining budget box - to be retrieved from database -->
            <div class="curr-budget p-4 mt-5  <?= $alert == 0 ? 'bg-success' : 'bg-danger' ?> text-light">
                <div class="d-flex justify-content-evenly">
                    <div style="font-size:2.3rem;" class="pt-4 pb-4">
                        <div>Remaining Amount of your Budget: </div>
                        <div class="text-warning"><?= $current ?></div>

                    </div>

                    <div class="vertical-bar-grey"></diV>

                    <div class="ps-4 pt-3 pb-3 add-caption text-center" style="font-size:1.5rem;">
                        Made a new purchase? <br> Go ahead!<br>
                        <button type="button" class="btn btn-light text-success mt-3 fw-bold" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add It To Your List</button>
                    </div>
                </div>

                <div class="budget-alert text-center pt-3 <?= $alert == 0 ? 'invisible' : '' ?> ">
                    Be Careful! You've consumed above <?= $alert_value ?>% of your initial budget!
                </div>
            </div>


            <!-- Add Purchase Modal -->
            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-success" id="exampleModalLabel2">Add a New Purchase ..</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <form class="row g-3 mt-4 ps-4 add-form" method="post" action="index.php">

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Item</label>
                                        <input type="text" class="form-control" id="item" name="item">
                                    </div>
                                </diV>

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Price</label>
                                        <input type="text" class="form-control" id="price" name="price">
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category" id="category">
                                            <option selected value="Groceries">Groceries</option>
                                            <option value="Furniture">Furniture</option>
                                            <option value="Clothing">Clothing</option>
                                            <option value="Electronics">Electronics</option>
                                            <option value="Books">Books</option>
                                            <option value="Toys">Toys</option>
                                            <option value="Outing">Outing</option>
                                            <option value="Home Decor">Home Decor</option>
                                            <option value="Jewellery">Jewellery</option>
                                            <option value="Fitness Equipment">Fitness Equipment</option>
                                            <option value="Car Maintenance">Car Maintenance</option>
                                            <option value="Beauty Products">Beauty Products</option>
                                            <option value="Services">Services</option>
                                            <option value="Medicine">Medicine</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Date of Purchasing Process</label>
                                        <input type="date" class="form-control" id="date" name="date">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Payment Method</label>
                                        <select  class="form-select" name="paymethod" id="paymethod">
                                            <option selected value="cash">Cash</option>
                                            <option value="visa">Visa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Add</button>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <!-- budget stats - to be retrieved from database -->
            <div class="row overview-stats m-0 mt-4 p-2 justify-content-evenly">

                <div class="col-md-3 me-1 align-items-center pt-4 pb-4 mb-3">
                    <div class="row align-items-center">
                        <div class="col mr-1">
                            <div class="fs-7 font-weight-bold text-success  mb-1">
                                Initial Budget
                            </div>
                            <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary">
                                <?= $initial ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-dollar-sign text-success fs-2 p-0"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 me-1 align-items-center pt-4 pb-4  mb-3">
                    <div class="row align-items-center">
                        <div class="col mr-1">
                            <div class="fs-7 font-weight-bold text-success  mb-1">
                                Consumed Amount
                            </div>
                            <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary">
                                <?= $consumed ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-angles-down text-success fs-2 p-0"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 align-items-center pt-4 pb-4  mb-3">
                    <div class="row align-items-center">
                        <div class="col mr-1">
                            <div class="fs-7 font-weight-bold text-success  mb-1">
                                Goal to Save
                            </div>
                            <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary">
                                <?= $goal ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-trophy  text-success fs-2 p-0"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 me-1 align-items-center pt-4 pb-4 mb-3">
                    <div class="row align-items-center">
                        <div class="col mr-1">
                            <div class="fs-7 font-weight-bold text-success mb-1">
                                Most Purchased Category
                            </div>
                            <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary"><?=$mostPurchasedCategory?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-basket-shopping text-success fs-2 p-0"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 me-1 align-items-center pt-4 pb-4 mb-3">
                    <div class="row align-items-center">
                        <div class="col mr-1">
                            <div class="fs-7 font-weight-bold text-success mb-1">
                                Visa Purchases
                            </div>
                            <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary"><?=$visa?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-regular fa-credit-card text-success fs-2 p-0"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 align-items-center pt-4 pb-4 mb-3">
                    <div class="row align-items-center">
                        <div class="col mr-1">
                            <div class="fs-7 font-weight-bold text-success  mb-1">
                                Cash Purchases
                            </div>
                            <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary"><?=$cash?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-money-bill text-success fs-2 p-0"></i>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</body>

</html>