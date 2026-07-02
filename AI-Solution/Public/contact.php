<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../config/database.php";

$message = "";
$error_message = "";

if(isset($_POST['submit'])) {
    // Get form data with proper escaping
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $job_details = mysqli_real_escape_string($conn, $_POST['job_details']);
    
    // Validate data
    if(empty($fullname) || empty($email) || empty($phone) || empty($company) || 
       empty($country) || empty($job_title) || empty($job_details)) {
        $error_message = "All fields are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address!";
    } else {
        // Correct INSERT query matching your table structure
        $sql = "INSERT INTO customer_inquiries 
                (fullname, email, phone, company, country, job_title, job_details, created_at) 
                VALUES 
                ('$fullname', '$email', '$phone', '$company', '$country', '$job_title', '$job_details', NOW())";
        
        if(mysqli_query($conn, $sql)) {
            $message = "Your inquiry has been submitted successfully! We will contact you soon.";
            
            // Optional: Clear form fields after success using JavaScript
            echo "<script>setTimeout(function(){ window.location.href = 'contact.php?success=1'; }, 2000);</script>";
        } else {
            $error_message = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Check for success parameter in URL
if(isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Your inquiry has been submitted successfully! We will contact you soon.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - AI Solutions</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    font-family: Arial, sans-serif;
}
.navbar{
    padding:15px 0;
}
.page-title{
    text-align:center;
    margin:60px 0;
}
.contact-form{
    background:#f8f9fa;
    padding:40px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
.footer{
    background:#0d6efd;
    color:white;
    padding:40px 0;
    margin-top:80px;
}
.footer ul{
    list-style:none;
    padding:0;
}
.footer a{
    color:white;
    text-decoration:none;
}
.logo{
    font-size:24px;
    font-weight:bold;
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

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand logo" href="index.html">AI-Solutions</a>
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
                <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact Us</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="page-title">
        <h1>Contact Us</h1>
        <p>Submit your project requirements and our team will get in touch with you.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="contact-form">
                
                <?php if($message != ""){ ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <?php if($error_message != ""){ ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="fullname" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="Enter phone number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name *</label>
                            <input type="text" name="company" class="form-control" placeholder="Enter company name" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country *</label>
                            <input type="text" name="country" class="form-control" placeholder="Enter country" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Job Title *</label>
                            <input type="text" name="job_title" class="form-control" placeholder="Enter job title" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Job Details *</label>
                        <textarea name="job_details" class="form-control" rows="6" placeholder="Describe your project requirements" required></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg px-5">
                            Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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