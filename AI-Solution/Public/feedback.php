<?php


include "../config/database.php";

$message = "";

if(isset($_POST['submit']))
{
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if($fullname != "" && $rating != "" && $comment != "")
    {
        $sql = "INSERT INTO feedback
                (fullname, rating, comment, created_at)
                VALUES
                ('$fullname','$rating','$comment',NOW())";

        if(mysqli_query($conn,$sql))
        {
            $message = "Feedback submitted successfully!";
        }
        else
        {
            $message = "Database Error : " . mysqli_error($conn);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Feedback - AI Solutions</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
font-family:Arial,sans-serif;
background:#f8f9fa;
}

.navbar{
padding:15px 0;
}

.logo{
font-size:24px;
font-weight:bold;
}

.page-title{
text-align:center;
margin:60px 0;
}

.feedback-form{

background:white;
padding:35px;
border-radius:10px;
box-shadow:0 2px 10px rgba(0,0,0,.1);

}

.feedback-card{

background:white;
padding:20px;
border-radius:10px;
margin-bottom:20px;
box-shadow:0 2px 10px rgba(0,0,0,.08);

}

.stars{

font-size:22px;
color:#ffc107;

}

.footer{

background:#0d6efd;
color:white;
padding:40px;
margin-top:70px;

}

.footer a{

color:white;
text-decoration:none;

}

.star-rating i{

font-size:34px;
cursor:pointer;
color:#ddd;

}
/* Chatbot */

#chatbotIcon{
    width:120px;
    cursor:pointer;
    transition:0.3s;
}

#chatbotIcon:hover{
    transform:scale(1.05);
}

#chatPopup{
    display:none;
    position:absolute;
    right:0;
    bottom:130px;
    width:320px;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 0 15px rgba(0,0,0,0.2);
    z-index:999;
}

.chat-header{
    background:#2f49d1;
    color:white;
    padding:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:bold;
}

.chat-header span{
    cursor:pointer;
}

.chat-body{
    height:250px;
    overflow-y:auto;
    padding:15px;
    background:#f8f9fa;
}

.bot-msg{
    background:#e9ecef;
    color:black;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
    width:85%;
}

.user-msg{
    background:#2f49d1;
    color:white;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
    width:85%;
    margin-left:auto;
}

.chat-input{
    display:flex;
}

.chat-input input{
    flex:1;
    padding:10px;
    border:none;
    outline:none;
}

.chat-input button{
    border:none;
    background:#2f49d1;
    color:white;
    padding:10px 15px;
}

</style>

</head>

<body>
    <!-- Navigation -->

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">

<div class="container">

<a class="navbar-brand logo" href="index.html">
AI-Solutions
</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
<li class="nav-item"><a class="nav-link" href="services.html">Services</a></li>
<li class="nav-item"><a class="nav-link" href="projects.html">Projects</a></li>
<li class="nav-item"><a class="nav-link" href="events.html">Events</a></li>
<li class="nav-item"><a class="nav-link" href="gallery.html">Gallery</a></li>
<li class="nav-item"><a class="nav-link active" href="feedback.php">Feedback</a></li>
<li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>

</ul>

</div>

</div>

</nav>

<!-- Title -->

<div class="container">

<div class="page-title">

<h1>Customer Feedback</h1>

<p>Read customer reviews and submit your own feedback.</p>

</div>

</div>

<div class="container">

<?php

if($message!="")
{
?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php
}
?>

<h3 class="mb-4">

Recent Feedback

<?php

$count=mysqli_query($conn,"SELECT COUNT(*) AS total FROM feedback");

$row=mysqli_fetch_assoc($count);

?>

<span class="badge bg-primary">

<?php echo $row['total']; ?>

Reviews

</span>

</h3>

<?php

$result=mysqli_query($conn,"SELECT * FROM feedback ORDER BY id DESC");

if(mysqli_num_rows($result)>0)
{

while($data=mysqli_fetch_assoc($result))
{

?>

<div class="feedback-card">

<h5>

<?php echo htmlspecialchars($data['fullname']); ?>

</h5>

<div class="stars">

<?php

for($i=1;$i<=5;$i++)
{

if($i<=$data['rating'])
echo "★";
else
echo "☆";

}

?>

</div>

<p>

<?php echo htmlspecialchars($data['comment']); ?>

</p>

<small class="text-muted">

<?php echo date("d M Y",strtotime($data['created_at'])); ?>

</small>

</div>

<?php

}

}
else
{

echo "<div class='alert alert-info'>No feedback available.</div>";

}

?>

<hr class="my-5">

<div class="feedback-form">

<h3 class="mb-4">

Submit Feedback

</h3>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="fullname"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Rating

</label>

<select
name="rating"
class="form-select"
required>

<option value="">Choose Rating</option>
<option value="5">★★★★★ Excellent</option>
<option value="4">★★★★☆ Good</option>
<option value="3">★★★☆☆ Average</option>
<option value="2">★★☆☆☆ Poor</option>
<option value="1">★☆☆☆☆ Bad</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Comment

</label>

<textarea
name="comment"
rows="5"
class="form-control"
required></textarea>

</div>

<button
type="submit"
name="submit"
class="btn btn-primary">

Submit Feedback

</button>

</form>

</div>

</div>
<!-- Footer -->

<footer class="footer">
   <div class="container">
    <div class="row text-white">

        <!-- Quick Links -->
        <div class="col-md-4">
            <h3>Quick Links</h3>
            <ul class="list-unstyled">
                <li><a href="index.php" class="text-white">Home</a></li>
                <li><a href="#services" class="text-white">Services</a></li>
                <li><a href="#projects" class="text-white">Projects</a></li>
                <li><a href="#gallery" class="text-white">Gallery</a></li>
                <li><a href="#events" class="text-white">Events</a></li>
                <li><a href="#contact" class="text-white">Contact Us</a></li>
            </ul>
        </div>

        <!-- Social Media -->
        <div class="col-md-4">
            <h3>Follow Us</h3>
            <ul class="list-unstyled">
                <li><a href="#" class="text-white">Facebook</a></li>
                <li><a href="#" class="text-white">Instagram</a></li>
                <li><a href="#" class="text-white">LinkedIn</a></li>
                <li><a href="#" class="text-white">Twitter</a></li>
            </ul>
        </div>

        <!-- Chatbot -->
        <!-- Chatbot -->

<div class="col-md-4 text-center position-relative">

<h4>Chat With Us</h4>

<img src="chatbox.png"
     alt="Chatbot"
     id="chatbotIcon">

<div id="chatPopup">

<div class="chat-header">

AI Assistant

<span onclick="closeChat()">✖</span>

</div>

<div class="chat-body" id="chatBody">

<div class="bot-msg">
Hello! How can I help you today?
</div>

</div>

<div class="chat-input">

<input
type="text"
id="userInput"
placeholder="Ask a question...">

<button onclick="sendMessage()">
Send
</button>

</div>

</div>

</div>


 </div>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>

document.getElementById("chatbotIcon")
.addEventListener("click", function(){

document.getElementById("chatPopup")
.style.display = "block";

});

function closeChat(){

document.getElementById("chatPopup")
.style.display = "none";

}

function sendMessage(){

let input =
document.getElementById("userInput");

let question =
input.value.trim();

if(question==="")
return;

let chat =
document.getElementById("chatBody");

chat.innerHTML +=
'<div class="user-msg">'+question+'</div>';

let answer="";

let q=
question.toLowerCase();

if(q.includes("service"))
{
answer=
"We provide AI Chatbots, Software Development, Automation Solutions and Technology Consulting.";
}

else if(q.includes("contact"))
{
answer=
"Please visit the Contact Us page and submit your project requirements.";
}

else if(q.includes("project"))
{
answer=
"You can explore our completed software projects on the Projects page.";
}

else if(q.includes("event"))
{
answer=
"Upcoming workshops and technology events are available on the Events page.";
}

else if(q.includes("feedback") || q.includes("review"))
{
answer=
"Customer ratings and reviews are available on the Feedback page.";
}

else
{
answer=
"Sorry, I can only answer questions related to our services, projects, events, feedback and contact information.";
}

setTimeout(function(){

chat.innerHTML +=
'<div class="bot-msg">'+answer+'</div>';

chat.scrollTop =
chat.scrollHeight;

},500);

input.value="";

}

document.getElementById("userInput")
.addEventListener("keypress",function(e){

if(e.key==="Enter")
{
sendMessage();
}

});

</script>
</html>