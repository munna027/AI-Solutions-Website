<?php

include "../config/database.php";

// Delete Feedback
if(isset($_GET['delete']))
{
    $id = intval($_GET['delete']);

    mysqli_query($conn,"DELETE FROM feedback WHERE id='$id'");

    header("Location: manage_feedback.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Manage Feedback</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2 class="mb-4">

Manage Feedback

<?php

$count=mysqli_query($conn,"SELECT COUNT(*) AS total FROM feedback");

$row=mysqli_fetch_assoc($count);

?>

<span class="badge bg-primary">

<?php echo $row['total']; ?>

Feedback

</span>

</h2>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Full Name</th>

<th>Rating</th>

<th>Comment</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$result=mysqli_query($conn,"SELECT * FROM feedback ORDER BY id DESC");

if(mysqli_num_rows($result)>0)
{

while($data=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $data['id']; ?></td>

<td><?php echo htmlspecialchars($data['fullname']); ?></td>

<td>

<?php

for($i=1;$i<=5;$i++)
{
    if($i<=$data['rating'])
        echo "★";
    else
        echo "☆";
}

?>

</td>

<td><?php echo htmlspecialchars($data['comment']); ?></td>

<td><?php echo $data['created_at']; ?></td>

<td>

<a
href="?delete=<?php echo $data['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this feedback?')">

Delete

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="6" class="text-center">

No Feedback Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<a href="admin-dashboard.php" class="btn btn-secondary">

Back to Dashboard

</a>

</div>

</body>
</html>