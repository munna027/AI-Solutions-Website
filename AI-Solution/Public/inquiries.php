<h2>Customer Inquiries</h2>

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Company</th>
<th>Email</th>
<th>Phone</th>
<th>Job Title</th>
<th>Date</th>
</tr>
</thead>

<tbody>

<?php

include "../config/database.php";

$result = mysqli_query(
$conn,
"SELECT * FROM customer_inquiries ORDER BY id DESC"
);

while($row = mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['fullname']; ?></td>
<td><?php echo $row['company']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['phone']; ?></td>
<td><?php echo $row['job_title']; ?></td>
<td><?php echo $row['created_at']; ?></td>

</tr>

<?php
}
?>

</tbody>

</table>