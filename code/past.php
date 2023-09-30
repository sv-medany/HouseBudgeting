<?php
require_once('processing.php');
credentialsCheck();

// in case of no budget record for the current month, create a one
Budget::create_budget_record(get_id());

$month = date('m');
$year = date('Y');
$years = Budget::get_budget_years(get_id()); // returns budget years for his user

// retrieve budgets based on year
if (empty($_GET['year'])) {
    $budgets = Budget::budgetsOfThisYear(get_id(), $year);
} else {
    $year = $_GET['year'];
    $budgets = Budget::budgetsOfThisYear(get_id(), $year);
}

require_once('head.php');
$place = 'past data';
?>


<body>
    <div class="row m-0">

        <?php
        require_once('sidenav.php'); //  desktop view
        require_once('mobilenav.php') //  mobile view
        ?>
        <div class="past" style="padding-left:280px; padding-right:0;">
            <!-- header -->
            <div class="header-purchases">

                <!-- displayed month filter -->
                <div style="font-size:2.5rem;" class="pt-3 text-center bg-success text-light pb-1">
                    <form method="get" action="past.php" onchange="submit()">
                        Displaying Budgets for the year:
                        <select name="year" class="text-success p-2 border border-success">
                            <?php
                            if (!empty($years)) {
                                foreach ($years as $y) {
                            ?>
                                    <option value="<?= $y ?>" <?= !empty($_GET['year']) && $_GET['year'] == $y ? " selected " : " " ?>><?= $y ?></option>
                            <?php }
                            }
                            ?>

                        </select>
                    </form>

                </div>


            </div>
            <?php
            if (!empty($budgets)) {
                foreach ($budgets as $budget) { ?>
                    <div class="mb-3">
                        <div style="font-size:2.3rem;" class="pt-4 ps-4">
                            Budgeting Data for Month <span class="text-success"> <?= getMonthName($budget->getMonth()) . " " . $budget->getYear() ?></span>:
                        </div>


                        <!-- goal achieved handling -->
                        <?php
                        $consumed = $budget->getConsumed();
                        $initial = $budget->getInitial();
                        $goal = $budget->getGoal();
                        $m = $budget->getMonth();
                        $y = $budget->getYear();
                        if ($initial - $consumed >= $goal && $m != date('m'))
                            $achieved = 1;
                        elseif ($m == date('m') && $y != date('Y')) {
                            $achieved = 1;
                        } else {
                            $achieved = 0;
                        }
                        ?>

                        <hr>
                        <div class="row overview-stats m-0 mt-4 p-2 justify-content-evenly">

                            <div class="col-md-3 me-1 align-items-center pt-4 pb-4 mb-3">
                                <div class="row align-items-center">
                                    <div class="col mr-1">
                                        <div class="fs-7 font-weight-bold text-success  mb-1">
                                            Initial Budget
                                        </div>
                                        <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary">
                                            <?= $budget->getInitial() ?>
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
                                            <?= $budget->getConsumed() ?>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa-solid fa-angles-down text-success fs-2 p-0"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 align-items-center pt-4 pb-4  mb-3<?= $achieved ? " border-start border-warning border-5" : "" ?>">
                                <div class="row align-items-center">
                                    <div class="col mr-1">
                                        <div class="fs-7 font-weight-bold <?= $achieved ? " text-warning " : " text-success " ?>  mb-1">
                                            Goal to Save <?= $achieved ? '<i class="fa-solid fa-check text-warning fs-3"></i> <span class="text-secondary" style="font-size:0.8rem;">Achieved</span>' : "" ?>
                                        </div>
                                        <div class="h3 pb-4 pt-4 mb-0 font-weight-bold <?= $achieved ? " text-warning " : " text-secondary " ?>">
                                            <?= $budget->getGoal() ?>

                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa-solid fa-trophy  <?= $achieved ? " text-warning " : " text-success " ?> fs-2 p-0"></i>
                                    </div>
                                </div>
                            </div>
                            <?php
                            // data to be displayed in elements
                            $mostPurchasedCategory = Purchase::most_purchased_category($budget->getMonth(), get_id(), $budget->getYear());
                            $payCount = Purchase::visa_cash_count($budget->getMonth(), get_id(), $budget->getYear());
                            $visa = $payCount["visa"];
                            $cash = $payCount["cash"];
                            ?>

                            <div class="col-md-3 me-1 align-items-center pt-4 pb-4 mb-3">
                                <div class="row align-items-center">
                                    <div class="col mr-1">
                                        <div class="fs-7 font-weight-bold text-success mb-1">
                                            Most Purchased Category
                                        </div>
                                        <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary"><?= $mostPurchasedCategory ?></div>
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
                                        <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary"><?= $visa ?></div>
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
                                        <div class="h3 pb-4 pt-4 mb-0 font-weight-bold text-secondary"><?= $cash ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fa-solid fa-money-bill text-success fs-2 p-0"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                <?php }
            } else { ?>
                <div style="font-size:2.3rem;" class="pt-4 ps-4">
                    No Budgeting Data for the year<span class="text-success"> <?= $_GET['year'] ?></span>:
                </div>
            <?php } ?>

        </div>
</body>

</html>