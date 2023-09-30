<?php

require_once('processing.php');
credentialsCheck();

// in case of no budget record for the current month, create a one
Budget::create_budget_record(get_id());


$month = date('m');
$year = date('Y');
$pairs = month_year_pair(get_id()); // get month-year pairs available in database
$categories = Purchase::get_categories(); // get categories stored in database



// get filter parameters if found
$category = !empty($_GET['category']) ? $_GET['category'] : null;
$above = !empty($_GET['above']) ? $_GET['above'] : null;
$below = !empty($_GET['below']) ? $_GET['below'] : null;



// retrieve purchases based on month - year filter
if (empty($_GET['monthyear'])) {

    $purchases = Purchase::get_purchases($month, $year, get_id(), $category, $above, $below);
} else {
    $temp = parseMonthYearPair($_GET['monthyear']);
    $month = $temp[0];
    $year = $temp[1];
    $purchases = Purchase::get_purchases($month, $year, get_id(), $category, $above, $below);
}



require_once('head.php');
$place = 'purchases';
?>


<body>
    <div class="row m-0">

        <?php
        require_once('sidenav.php'); //  desktop view
        require_once('mobilenav.php') // mobile view
        ?>
        <div class="purchases" style="padding-left:280px; padding-right:0;">

            <!-- filtering -->
            <div class="bg-success text-light ps-2 pt-2 pb-2 fs-4 text-center">
                Wanna display specific purchases?&nbsp; &nbsp;
                <button type="button" class="btn btn-light text-success" data-bs-toggle="modal" data-bs-target="#exampleModal2">Filter Your List</button>
            </div>



            <!-- filtering modal -->
            <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-success" id="exampleModalLabel2">Filter Purchases List .. </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

                            <form class="row g-3 mt-4 ps-4 filter-form" action="purchases.php" method="get">

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Category</label>
                                        <select name="category" class="form-control">
                                            <option value=""></option>
                                            <?php
                                            foreach ($categories as $c) {
                                            ?>
                                                <option value="<?= $c ?>"><?= $c ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </diV>

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Cost Above</label>
                                        <input type="text" class="form-control" id="above" name="above">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div>
                                        <label class="form-label">Cost Below</label>
                                        <input type="text" class="form-control" id="below" name="below">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="costbelow" name="monthyear" value=<?= getMonthName($month) . $year ?>>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Apply Filter</button>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <!-- display deletion success bar -->
            <?php
            if (!empty($_GET['successdel'])) { ?>
                <div class="text-center bg-warning">Purchase is successfully deleted! &nbsp;Note: any set filters have been cleared!</div>
            <?php } ?>

            <!-- header -->
            <div class="header-purchases p-4 pb-0 mt-1 ">

                <!-- displayed month filter -->
                <div style="font-size:2.3rem;" class="pt-4">
                    <form method="get" action="purchases.php" onchange="submit()">
                        Displaying Purchases for Month:
                        <select name="monthyear" class="text-success p-2 border border-white">
                            <?php
                            if (!empty($pairs)) {
                                foreach ($pairs as $pair) {
                                    $pair = explode("-", $pair);
                                    $m = $pair[0];
                                    $y = $pair[1];
                            ?>
                                    <option value="<?= $m . "" . $y ?>" <?= !empty($_GET['monthyear']) && $_GET['monthyear'] == $m . $y ? " selected " : " " ?>><?= $m . " " . $y ?></option>
                            <?php }
                            }
                            ?>

                        </select>
                    </form>
                </div>

            </div>

            <!-- purchases table -->
            <div class="p-2 table-responsive ">
                <table class="table table-striped table-hover text-center ">
                    <thead class="table-success">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Purchase</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">Payment Method</th>
                            <?= $month == date('m') && $year == date('Y') ? '<th scope="col">Remove</th>' : '' ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 1;

                        if (!empty($purchases)) {
                            foreach ($purchases as $p) {
                        ?>
                                <tr>
                                    <th scope="row"><?= $i++ ?></th>
                                    <td><?= $p->getDate(); ?></td>
                                    <td><?= $p->getName(); ?></td>
                                    <td><?= $p->getCategory(); ?></td>
                                    <td><?= $p->getPrice(); ?></td>
                                    <td><?= $p->getPayment(); ?></td>
                                    <?= ($month == date('m') && $year == date('Y')) ? '<td><i data-bs-toggle="modal" data-bs-target="#exampleModal3' . $p->getId() . '" class="fa-solid fa-trash-can text-danger fs-5" ></i></td>' : '' ?>
                                </tr>

                                <!-- delete modal -->
                                <div class="modal fade" id="exampleModal3<?= $p->getId(); ?>" tabindex="-1" aria-labelledby="exampleModalLabel3<?= $p->getId() ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-success" id="exampleModalLabel3<?= $p->getId() ?>">Deleting Purchase ... </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">Are you sure you want to delete this purchase?
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="processing.php?delete=<?= $p->getId() ?>&monthyear=<?= getMonthName($month) . $year ?>" class="btn btn-danger text-decoration-none">Delete</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <?php
                            }
                        } ?>


                    </tbody>

                </table>
            </div>

        </div>

    </div>
</body>

</html>