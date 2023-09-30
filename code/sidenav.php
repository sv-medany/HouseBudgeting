<div class="side-navbar d-flex flex-column justify-content-between">
    <div>
        <!-- logo -->
        <div class="ms-3 fs-4 fw-bold">
            <i class="fa-solid fa-house-circle-check text-success"></i>
            House Wallet
        </div>
        <hr>

        <!-- pages list -->
        <ul class="nav nav-pills flex-column mt-3 mb-auto">
            <li class="nav-item ms-2 mb-2">
                <a href="index.php" class="nav-link <?= $place == 'index' ? 'active bg-success' : 'link-dark' ?>" aria-current="page">
                    <i class="fa-regular fa-eye me-2"></i>
                    Overview
                </a>
            </li>

            <li class="nav-item ms-2 mb-2">
                <a href="purchases.php" class="nav-link <?= $place == 'purchases' ? 'active bg-success' : 'link-dark' ?>" aria-current="page">
                    <i class="fa-solid fa-dollar-sign me-2"></i>&nbsp;&nbsp;Purchases
                </a>
            </li>

            <li class="nav-item ms-2 mb-2">
                <a href="past.php" class="nav-link <?= $place == 'past data' ? 'active bg-success' : 'link-dark' ?>" aria-current="page">
                    <i class="fa-regular fa-calendar-days me-2"></i>&nbsp;Past Months Data
                </a>
            </li>

            <li class="nav-item ms-2 mb-2">
                <a href="settings.php" class="nav-link <?= $place == 'settings' ? 'active bg-success' : 'link-dark' ?>" aria-current="page">
                    <i class="fa-solid fa-gear me-2"></i>&nbsp;Settings
                </a>
            </li>
        </ul>
    </div>

    <div class="mb-5">
        <hr>
        <div class="d-flex justify-content-between ps-2 pe-2 align-items-center">
            <?= get_username()?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Log Out</button>


        </div>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logging Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are your sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="processing.php?mode=logout" class = "btn btn-success text-decoration-none">Log Out </a>
            </div>
        </div>
    </div>
</div>