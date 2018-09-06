<?php

require 'QUTGo/setup.php';

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>QUT Go</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>


    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>QUT Go <br>Dashboard</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Welcome Cameron Cross</p>
                <li class="active">
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dashboard</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="/dashboard/challenges">Your Challenges</a>
                        </li>
                        <li>
                            <a href="/dashboard/statistics">Statistics</a>
                        </li>
                        <li>
                            <a href="/dashboard/trends">Weekly Trends</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Contact Us</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="#" class="download">Download QUT Go</a>
                </li>
                <li>
                    <a href="#" class="article">Report an Issue</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Conatct Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Log In</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <?php

            $sql = "SELECT * FROM challenge";
            $result = $connect->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "id: " . $row["challenge_id"]. " Challengee: " . $row["challengee"]. " Challenger:  " . $row["challenger"]. " Block: " . $row["block"] . "<br>";
                }
            } else {
                echo "0 results";
            }
            $connect->close();

            ?>

            <h2>What is QUT Go</h2>
            <p>QUT Go is an app developed by Venom Technologies as a final year IT capstone project. It allows students and staff studying at the QUT gardens point campus to sign up for an account using their Google accounts. They can then add friends, issue walking challenges or be challenged to walk more throughout the day.</p>
            <p>The main goal of qut go is to help people live a healthier lifestyle. The app achieves this for staff and students through the use of challenges. Whenever a user leaves a building within the QUT campus the app will issue a challenge to the user to walk to X building. This could be where they were already going, but more likely than not it will be a different building. By encouraging participants to walk that little extra they can accumliate more steps throughout their day.</p>
            <p>The app also allows others to add their friends and challenge them or organise to meet with them. Making the app more engaging for users.</p>

            <div class="line"></div>

            <h2>This Site</h2>
            <p>This website allows Venom Tech to communicate with the current users of QUT Go, it also allows for feedback to be given regarding the app. On top of this, this website serves as a centeral hub for all QUT Go users to:</p>
            <ol>
                <li>Download the app</li>
                <li>View their progress</li>
                <li>Provide feedback for the study conducted</li>
            </ol>

            <div class="line"></div>

            <h2>Using QUT Go</h2>
            <p>If you want to start using QUT Go and help out Venom Tech by being part of their research group. Feel free to download the app from the sidebar, and fill out a details survey</p>
            <a href="#" class="details">Survey Here</a>
        </div>
    </div>


    <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <script src="/assets/js/jQuery-3.3.1.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>

</html>