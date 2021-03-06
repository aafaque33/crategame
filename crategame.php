<!DOCTYPE html>

<?php
require('dbconfig.php');
    session_start();
    if(isset($_SESSION['currentseed']))
        unset($_SESSION['currentseed']);
    if(isset($_SESSION['puzzlename']))
        unset($_SESSION['puzzlename']);
    if(isset($_SESSION['finalscore']))
        unset($_SESSION['finalscore']);
    ?>
<html>

	<head>

		<title> Play Game </title>
		<link rel="stylesheet" type="text/css" href="stylesheets/crate.css">
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

	</head>

	<body class="game">

		<div id="main">

            <div id="header">
                <header class="wrapper clearfix">
                    <div class="center">
                        <h1 class="title">CRA</h1>
                        <img src="bobcat1.gif" alt="" />
                        <img src="boxtarget3inverse.png" alt="" />
                        <h1 class="title">&nbsp;</h1>
                        <h1 class="title">GAM</h1>
                        <img src="boxtarget3inverse.png" alt="" />
                    </div>
                </header>
            </div>

            <nav class="navigation">
                    <ul>
                        <li><a href="index.php" class="list">Home</a></li>
                        <li><a href="puzzlelist.php" class="list">Puzzle List</a></li>
                        <?php
                        if(isset($_SESSION['name']))
                        {
                            echo '<li><a href="logout.php" class="login">' . "Logout" . '</a></li>' ;
                            echo '<li class="username">' .'Logged in as user: '.  $_SESSION['name'] . '</li>' ;
                        }
                        else
                        {
                            echo  '<li><a href="registration.php" class="registration">Registration</a></li>' ;
                            echo '<li><a href="login.php" class="login">Login</a></li>';
                        }
                        ?>
                    </ul>
            </nav>

            <audio id="stepaudio">
                <p>Your browser does not support the audio element.</p>
            </audio>

            <div id="result" class="errorbox success" style="display:none"></div>

            <div style="text-align: center;">
                <form action="score.php" method="POST" <?php if(!isset($_SESSION['name'])) echo 'onsubmit="return confirm(\'You are not currently Logged In, Please Log In to save your record\');"' ?> >
                <input type="hidden" id="finalscore" name="finalscore">
                <input type="hidden" id="currentseed" name="currentseed">
                <input type="hidden" id="puzzlename" name="puzzlename">
                <input type="submit" id="addscore"  name="addscore" class="gamebutton" value="Add Score" style="display:none">
                </form>
            </div>

            <?php
            if(isset($_SESSION['scoreupdate']))
            {
                echo '<div class="errorbox success" id="afterscoreadd"><span>Success: </span>Congratulations:  Your Record has been added for this Puzzle</div>';
                unset($_SESSION['scoreupdate']);
            }
            ?>

            <div id="container3">
                <div id="container2">
                    <div id="container1">
                        <div id="col1">
                            <h1> ScoreBoard </h1> <br/>
                            <div id="puzzlelist">
                                <?php

                                    $connect = mysqli_connect($server, $user, $pass, $db);

                                    if(!$connect)
                                    {
                                        printf("Connect failed: %s\n", $mysqli->connect_error);
                                        exit();
                                    }

                                    $result = $connect->query("select min(score) total,seedlev,puzname,name,recname from puzzles group by puzname");

                                    if(!$result)
                                    {
                                       die("results have not been fetched");
                                       exit();
                                    }
                                ?>

                                <table class="cratetable" >
                                    <th>Puzzle Name</th>
                                    <th>Game Creator</th>
                                    <th>Record Holder</th>
                                    <th>Score</th>

                                <?php
                                    //echo nl2br("PuzzleName ==> Name , Recname ===> Score \n\n") ;

                                    while($row = $result->fetch_array(MYSQLI_ASSOC))
                                    {
                                        echo "<tr>" ;
                                        echo '<td><a href="crategame.php?seed=' .$row["seedlev"]. '&recscore=' . $row["total"] . '&puzname=' . $row["puzname"] . '" class="button">Play&nbsp;'  . $row["puzname"] . "</a></td>";
                                        echo '<td>'  . $row["name"] . "</a></td>";
                                        echo '<td>'  . $row["recname"]. "</a></td>";
                                        echo '<td>' . $row["total"]. "</a></td>";
                                        echo "</tr>" ;
                                    }

                                ?>
                                </table>
                            </div>
                        </div>
                        <div id="col2">
                            <h1 id="puzheading">Simple Crate Game</h1>
                            <canvas id="canvas"></canvas>
                        </div>
                        <div id="col3">
                            <div class="scorebox current" id="score">Current score: 0 </div>
                            <div class="scorebox total" id="recscore">Record score: 0 </div> <br/>

                            <div id="sidebuttoncontainer">
                               <?php
                                echo '<ul id="buttonlist">';
                                echo '<li><a href="newpuzzle.php" id="newpuzlink"> New Puzzle </a> </li>';
                                echo '<li><a href="crategame.php"  id="resetlink"> Reset </a> </li>' ;
                                echo '<li><a href="puzzlelist.php" > Puzzle List </a></li>' ;
                                echo '</ul>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

				<script src="js/utils.js"> </script>
				<script src="js/crateboard.js"> </script>

		</div>

	</body>

</html>
