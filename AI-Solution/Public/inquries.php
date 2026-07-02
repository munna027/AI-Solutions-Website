<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Inquiries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f6f9;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .badge-count {
            background: #0d6efd;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .table th {
            background: #0d6efd;
            color: white;
            text-align: center;
            vertical-align: middle;
        }
        .table td {
            vertical-align: middle;
            text-align: center;
        }
        .no-data {
            padding: 40px;
            text-align: center;
            color: #6c757d;
        }
        .no-data i {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }
        .truncate-text {
            max-width: 150px;
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .table-hover tbody tr:hover {
            background-color: #e7f1ff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>
        📋 Customer Inquiries
        <?php
        include "../config/database.php";
        $count_query = "SELECT COUNT(*) as total FROM customer_inquiries";
        $count_result = mysqli_query($conn, $count_query);
        $count_row = mysqli_fetch_assoc($count_result);
        ?>
        <span class="badge-count">Total: <?php echo $count_row['total']; ?></span>
    </h2>

    <?php
    // Check if there are any records
    $check_query = "SELECT COUNT(*) as count FROM customer_inquiries";
    $check_result = mysqli_query($conn, $check_query);
    $check_row = mysqli_fetch_assoc($check_result);
    
    if($check_row['count'] > 0) {
    ?>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Company</th>
                    <th>Country</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Job Title</th>
                    <th>Job Details</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM customer_inquiries ORDER BY id DESC");
            $counter = 1;
            
            while($row = mysqli_fetch_assoc($result)) {
                // Truncate job details if too long
                $job_details = $row['job_details'];
                if(strlen($job_details) > 50) {
                    $job_details = substr($job_details, 0, 50) . '...';
                }
                
                // Format date
                $created_at = date('Y-m-d H:i', strtotime($row['created_at']));
                ?>
                <tr>
                    <td><strong><?php echo $counter++; ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['company']); ?></td>
                    <td><?php echo htmlspecialchars($row['country']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                    <td>
                        <span class="truncate-text" title="<?php echo htmlspecialchars($row['job_details']); ?>">
                            <?php echo htmlspecialchars($job_details); ?>
                        </span>
                    </td>
                    <td><?php echo $created_at; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    
    <?php
    } else {
        // No records found
        ?>
        <div class="no-data">
            <i>📭</i>
            <h4>No inquiries found</h4>
            <p>No customer inquiries have been submitted yet.</p>
            <a href="contact.php" class="btn btn-primary">Go to Contact Page</a>
        </div>
        <?php
    }
    
    // Close connection
    mysqli_close($conn);
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>