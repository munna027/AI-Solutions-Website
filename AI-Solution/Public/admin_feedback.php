<?php
include "config/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; padding: 20px; }
        .container { max-width: 1400px; }
        .stats-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        .stats-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #0d6efd;
        }
        .stats-card .label {
            color: #6c757d;
        }
        .table th { background: #0d6efd; color: white; }
        .btn-action { margin: 0 5px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">
        <i class="fas fa-comments"></i> Feedback Management
    </h2>

    <?php
    // Handle status update
    if(isset($_GET['action']) && isset($_GET['id'])) {
        $id = $_GET['id'];
        $action = $_GET['action'];
        
        if($action == 'approve') {
            $sql = "UPDATE customer_feedback SET status = 'approved' WHERE id = $id";
            if(mysqli_query($conn, $sql)) {
                echo '<div class="alert alert-success">Feedback approved successfully!</div>';
            }
        } elseif($action == 'reject') {
            $sql = "UPDATE customer_feedback SET status = 'rejected' WHERE id = $id";
            if(mysqli_query($conn, $sql)) {
                echo '<div class="alert alert-warning">Feedback rejected.</div>';
            }
        } elseif($action == 'delete') {
            $sql = "DELETE FROM customer_feedback WHERE id = $id";
            if(mysqli_query($conn, $sql)) {
                echo '<div class="alert alert-danger">Feedback deleted.</div>';
            }
        }
    }

    // Statistics
    $stats = [
        'total' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customer_feedback"))['count'],
        'pending' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customer_feedback WHERE status = 'pending'"))['count'],
        'approved' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customer_feedback WHERE status = 'approved'"))['count'],
        'rejected' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM customer_feedback WHERE status = 'rejected'"))['count'],
    ];
    ?>

    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="number"><?php echo $stats['total']; ?></div>
                <div class="label">Total Feedback</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="number text-warning"><?php echo $stats['pending']; ?></div>
                <div class="label">Pending</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="number text-success"><?php echo $stats['approved']; ?></div>
                <div class="label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="number text-danger"><?php echo $stats['rejected']; ?></div>
                <div class="label">Rejected</div>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM customer_feedback ORDER BY id DESC");
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $stars = str_repeat('⭐', $row['rating']) . str_repeat('☆', 5 - $row['rating']);
                    $status_color = $row['status'] == 'approved' ? 'success' : 
                                  ($row['status'] == 'pending' ? 'warning' : 'danger');
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                        <td><?php echo $stars; ?></td>
                        <td><?php echo htmlspecialchars(substr($row['comments'], 0, 50)) . (strlen($row['comments']) > 50 ? '...' : ''); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $status_color; ?>">
                                <?php echo strtoupper($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'pending') { ?>
                                <a href="?action=approve&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-success btn-action"
                                   onclick="return confirm('Approve this feedback?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="?action=reject&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-warning btn-action"
                                   onclick="return confirm('Reject this feedback?')">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php } ?>
                            <a href="?action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-danger btn-action"
                               onclick="return confirm('Delete this feedback?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No feedback found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>