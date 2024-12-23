<?php 
    session_start();
    include("koneksi.php");
    if(isset($_SESSION['user_id'])){
        $id = $_SESSION['user_id'];
        $query = mysqli_query($konek,"select * from users where role='user' and user_id='$id'")or die (mysqli_error($konek));
        while($data=mysqli_fetch_array($query)){
            $_SESSION['email'] = $data['email'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['fullname'] = $data['fullname'];
            $_SESSION['major'] = $data['major'];
            $_SESSION['university'] = $data['university'];
            $_SESSION['profile_picture'] = $data['profile_picture'];
        }
    }else{
        header("Location: user/login.php");
    }

    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $query_documents = mysqli_query($konek, "SELECT * FROM books WHERE status = '$status'") or die(mysqli_error($konek));
    }else {
        $query_documents = mysqli_query($konek, "SELECT * FROM books WHERE status = ''") or die(mysqli_error($konek));
    }

    if (isset($_GET['id']) && isset($_GET['status'])) {
        $id = intval($_GET['id']);
        $status = $_GET['status'];
        $upload_status = "";
        if($_GET['status'] == "Dihapus"){
            $query = mysqli_query($konek, "UPDATE books SET status = '$status',upload_status = '$upload_status' WHERE book_id = $id") or die(mysqli_error($konek));
        }else {
            $query = mysqli_query($konek, "UPDATE books SET status = '$status' WHERE book_id = $id") or die(mysqli_error($konek));
        }

        if ($query) {
            echo "Status updated successfully.";
        } else {
            echo "Failed to update status.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <title>Profile Pengguna</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background-color: #F9F9F9;
        }
        .navbar{
            background-color: #1E5B86;
        }
        .nav-link{
            color:white;
        }
        .profile-nav{
            border-radius: 20%;
            width: 50px;
            height: 38px;
        }
        p{
            color: #ADA7A7;
        }
        .img-top img{
            width: 100%;
            margin-bottom: 10px;
        }
        main{
            width: 90%;
        }
        .profile-img{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .name-email{
            margin-left: 20px;
        }
        .d-flex .profile-pict{
            width: 80px;
            height: 80px;
            border-radius: 100%;
        }
        .name-email{
            margin-top: 14px;
        }
        .form-section {
            display: flex;
            gap: 20px;
        }
        .form-section .left, .form-section .right {
            flex: 1;
        }
        .form-label {
            color: #555;
        }
        .carousel-control-prev, .carousel-control-next {
            opacity: 0;
            transition: opacity 0.3s;
        }
        #carouselExampleControls:hover .carousel-control-prev,
        #carouselExampleControls:hover .carousel-control-next {
            opacity: 1;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-primary px-3">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="contentfiltering.php">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="confirm_admin.php">Payment</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" aria-current="page" href="monetisasi_admin.php">Monetization</a>
            </li>
        </ul>
        <form class="d-flex" role="search" action="search.php" method="get">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
            <?php if($_SESSION['profile_picture'] != "") { ?>
                <div class="dropdown" style="width : 38px;">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
                        <img class="profile-nav" src="../uploads/<?=$_SESSION['profile_picture']?>" alt="Profile Picture" style="width: 38px;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="user/logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php } else { ?>
                <div class="dropdown" style="width : 38px;">
                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0;">
                        <img class="profile-nav" src="default.png" alt="Profile Picture" style="width: 38px;">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="user/logout.php">Logout</a></li>
                    </ul>
                </div>
                <?php } ?>
            <a href="notifikasi.php" style="margin-left: 8px;"><img src="notif.png" alt="Notifikasi"></a>
            <a href="cart.php" style="margin-left: 8px; padding:1px; background-color:white; border-radius:8px;"><img src="cart (2).png" alt="Cart" style="height:36px"></a>
        </form>
        </div>
    </div>
    </nav>

    <main class="m-auto">
        <h2 class="mt-4 mb-3">Kelola Konten</h2>
        <div style="background-color: black; width:100%; height:5px;"></div>
        <div class="btn mt-3 mb-3 d-flex m-auto" aria-label="Filter" style="justify-content:space-evenly; background-color:white; width:50%;">
            <form action="" method="get">
                <button type="submit" class="btn btn-secondary m-10" id="filter-waiting">Menunggu
                    <input type="hidden" name="status" value="">
                </button>
                <button type="submit" class="btn btn-secondary" id="filter-accepted">Diterima 
                    <input type="hidden" name="status" value="Diterima">
                </button>
                <button type="submit" class="btn btn-secondary" id="filter-rejected">Ditolak
                    <input type="hidden" name="status" value="Ditolak">  
                </button>
                <button type="submit" class="btn btn-secondary" id="filter-deleted">Dihapus
                    <input type="hidden" name="status" value="Dihapus">
                </button>
            </form>
        </div>

        <div id="document-container" class="d-flex flex-wrap gap-3">
            <?php while ($doc = mysqli_fetch_assoc($query_documents)) { ?>
                <div class="card m-auto" style="width: 18rem;">
                    <div class="card-body">
                        <img src="uploads/file.png" alt="file">
                        <h5 class="card-title"><?= htmlspecialchars($doc['title']) ?></h5>
                        <a href="uploads/<?= htmlspecialchars($doc['file']) ?>" target="_blank" class="btn btn-link text-white" style="background-color: #4F98CA; text-decoration:none;">Lihat</a>
                        <button class="btn btn btn-sm action-accept text-white" data-id="<?= $doc['book_id'] ?>" style="background-color: #4F98CA;">Terima</button>
                        <button class="btn btn-secondary btn-sm action-reject" data-id="<?= $doc['book_id'] ?>">Tolak</button>
                        <button class="btn btn-sm action-delete" data-id="<?= $doc['book_id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg></button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <script>
        // JavaScript untuk grup tombol filter
        document.querySelectorAll('button[id^="filter-"]').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault(); // Mencegah form submit default
                const status = button.querySelector('input[name="status"]').value;
                const currentUrl = window.location.href.split('?')[0]; // Mengambil URL tanpa query parameter
                window.location.href = `${currentUrl}?status=${status}`; // Redirect dengan parameter status
            });
        });

        document.querySelectorAll('.action-accept').forEach(button => {
            button.addEventListener('click', () => {
                const docId = button.dataset.id;
                fetch(`contentfiltering.php?id=${docId}&status=Diterima`)
                    .then(() => location.reload());
            });
        });

        document.querySelectorAll('.action-reject').forEach(button => {
            button.addEventListener('click', () => {
                const docId = button.dataset.id;
                fetch(`contentfiltering.php?id=${docId}&status=Ditolak`)
                    .then(() => location.reload());
            });
        });

        document.querySelectorAll('.action-delete').forEach(button => {
            button.addEventListener('click', () => {
                const docId = button.dataset.id;
                fetch(`contentfiltering.php?id=${docId}&status=Dihapus`)
                    .then(() => location.reload());
            });
        });
    </script>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>