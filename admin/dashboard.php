<!DOCTYPE html>
<html lang="en">

<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != '0') {
    header("Location: ../index.php");
    exit();
}
include "../components/header.php";
?>

<body>

    <div class="wrapper">
        <div class="main-panel">
            <?php include "../components/topnav.php" ?>
            <div class="container">
                <div class="page-inner">
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Users</p>
                                                <h4 class="card-title" id="totalUsers">...</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Active Users</p>
                                                <h4 class="card-title" id="totalActiveUsers">...</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                                <i class="fas fa-dog"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Announcements</p>
                                                <h4 class="card-title" id="totalBreeds">...</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="basic-datatables"
                                                class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID No.</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Last Login</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>ID No.</th>
                                                        <th>Name</th>

                                                        <th>Email</th>
                                                        <th>Last Login</th>

                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>

                                                </tbody>


                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include "./../components/scripts.php" ?>
    <script>
    $("#basic-datatables").DataTable({

        ajax: {
            url: './../backend/get_users.php',
            dataSrc: 'data'
        },
        columns: [{
                data: 'id'
            },
            {
                data: 'name',

            },

            {
                data: 'email'
            },
            {
                data: 'last_login'

            },
            {
                data: 'status'
            },
            {
                data: null,
                render: function(data, type, row) {
                    if (row.role != 0) {
                        return `<button type="button" class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#EditUserModal"
                            data-username="${row.username}"
                            data-email="${row.email}"
                            data-fname="${row.f_name}"
                            data-lname="${row.l_name}"
  
                            data-userid="${row.id}">
                            Edit
                        </button> 
                        
                        <button type="button" class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#DeleteUserModal"
                            data-username="${row.username}"
                            data-userid="${row.id}">
                            Delete
                        </button>`
                    }
                    return `
                        <button type="button" class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#EditUserModal"
                            data-username="${row.username}"
                            data-email="${row.email}"
                            data-fname="${row.f_name}"
                            data-lname="${row.l_name}"
  
                            data-userid="${row.id}">
                            Edit
                        </button>

                        

                        
                    `;
                }
            }
        ]
    })

    $(document).ready(() => {
        fetch('./../backend/dashboard_stats.php')
            .then(res => res.json())
            .then(data => {
                document.getElementById("totalUsers").textContent = data.users;
                document.getElementById("totalActiveUsers").textContent = data.ActiveUsers;
                document.getElementById("totalBreeds").textContent = data.breeds;

                myLineChart.data.datasets[0].data = data.monthly;
                myLineChart.update();

                const male = data.gender.Male || 0;
                const female = data.gender.Female || 0;
                myPieChart.data.datasets[0].data = [male, female];
                myPieChart.update();
            })
            .catch(err => {
                console.error("Failed to load dashboard data:", err);
            });
    })
    </script>
</body>

</html>