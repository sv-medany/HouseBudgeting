<nav class="mobile-nav navbar navbar-expand-lg  d-none sticky-top m-0">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-house-circle-check text-success"></i>
            House Wallet
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon "></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">


            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= $place == 'index' ? 'link-success' : 'link-dark' ?>" aria-current="page">
                        <i class="fa-regular fa-eye me-2"></i>
                        Overview
                    </a>
                </li>

                <li class="nav-item">
                    <a href="purchases.php" class="nav-link <?= $place == 'purchases' ? 'link-success' : 'link-dark' ?>" aria-current="page">
                        <i class="fa-solid fa-dollar-sign me-2"></i>&nbsp;&nbsp;Purchases
                    </a>

                </li>

                <li class="nav-item">
                    <a href="past.php" class="nav-link <?= $place == 'past data' ? 'link-success' : 'link-dark' ?>" aria-current="page">
                        <i class="fa-regular fa-calendar-days me-2"></i>&nbsp;Past Month Data
                    </a>
                </li>

                <li class="nav-item">
                    <a href="settings.php" class="nav-link <?= $place == 'settings' ? 'link-success' : 'link-dark' ?>" aria-current="page">
                        <i class="fa-solid fa-gear me-2"></i>&nbsp;Settings
                    </a>

                </li>

            </ul>

            <div class="mt-5">
                <hr>
                <div class="d-flex justify-content-between ps-2 pe-2 align-items-center">
                    <?= get_username() ?>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Log Out</button>
                </div>
            </div>
        </div>
    </div>
</nav>

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
                <a href="processing.php?mode=logout" class="btn btn-success text-decoration-none">Log Out </a>
            </div>
        </div>
    </div>
</div>