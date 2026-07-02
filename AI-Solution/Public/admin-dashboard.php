```php
<?php
session_start();

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

include "../config/database.php";

/* Dashboard Counts */

$totalInquiries = mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM customer_inquiries")
);

$totalFeedback = 0;
if(mysqli_query($conn,"SHOW TABLES LIKE 'feedback'")->num_rows > 0){
    $totalFeedback = mysqli_num_rows(
        mysqli_query($conn,"SELECT * FROM feedback")
    );
}

$unreadInquiries = $totalInquiries;

$upcomingEvents = 4;
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f7fb;
}

.sidebar{
    width:250px;
    height:100vh;
    background:#0d6efd;
    position:fixed;
    left:0;
    top:0;
    padding-top:20px;
}

.sidebar h3{
    color:white;
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:15px 25px;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.2);
}

.main-content{
    margin-left:250px;
    padding:30px;
}

.card-box{
    border:none;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.card-box h2{
    font-weight:bold;
}

.table-container{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

</style>

</head>

<body>

<!-- Sidebar -->

<div class="sidebar">

<h3>Admin Panel</h3>

<a href="admin-dashboard.php">Dashboard</a>
<a href="inquiries.php">Inquiries</a>
<a href="manage-feedback.php">Feedback</a>
<a href="events.php">Events</a>
<a href="settings.php">Settings</a>
<a href="logout.php">Logout</a>

</div>

<!-- Main Content -->

<div class="main-content">

<h2 class="mb-4">Dashboard</h2>

<div class="row">

<div class="col-md-3 mb-4">
<div class="card card-box p-3">
<h5>Total Inquiries</h5>
<h2><?php echo $totalInquiries; ?></h2>
</div>
</div>

<div class="col-md-3 mb-4">
<div class="card card-box p-3">
<h5>Unread Inquiries</h5>
<h2><?php echo $unreadInquiries; ?></h2>
</div>
</div>

<div class="col-md-3 mb-4">
<div class="card card-box p-3">
<h5>Total Feedback</h5>
<h2><?php echo $totalFeedback; ?></h2>
</div>
</div>

<div class="col-md-3 mb-4">
<div class="card card-box p-3">
<h5>Upcoming Events</h5>
<h2><?php echo $upcomingEvents; ?></h2>
</div>
</div>

</div>

<!-- Recent Inquiries -->

<div class="table-container">

<h4 class="mb-3">Recent Inquiries</h4>

<table class="table table-bordered table-hover">

<thead class="table-primary">

<tr>
<th>Name</th>
<th>Company</th>
<th>Job Title</th>
<th>Email</th>
<th>Date</th>
</tr>

</thead>

<tbody>

<?php

$query = mysqli_query(
$conn,
"SELECT * FROM customer_inquiries ORDER BY id DESC LIMIT 10"
);

while($row = mysqli_fetch_assoc($query))
{
?>

<tr>

<td><?php echo $row['fullname']; ?></td>
<td><?php echo $row['company']; ?></td>
<td><?php echo $row['job_title']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['created_at']; ?></td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</body>
</html>
```
