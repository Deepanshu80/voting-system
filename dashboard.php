<?php

session_start();
include ('../api/connect.php');

if (!isset($_SESSION['userdata']))
    header('location: ../');

$userdata = $_SESSION['userdata'];
$groups = mysqli_query($connect, 'SELECT * FROM users WHERE role=2');
$groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

if ($userdata['status'] == 0)
    $status = '<b style="color:red">Not voted</b>';
else
    $status = '<b style="color:green">voted</b>';
?>

<html>
  <head>
    <title>online voting system - dashboard</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <style>
      #backbtn{
         padding: 5px;
         border-radius: 5px;
         background-color: blue;
         color: white;
         float: left;
         margin: 10px;
      }
      #logoutbtn{
         padding: 5px;
         border-radius: 5px;
         background-color: blue;
         color: white;
         float: right;
         margin: 10px;
      }
      #profile{
         background-color: white;
         width: 30%;
         padding: 20px;
         float: left;
      }
      #Group{
         background-color: white;
         width: 60%;
         padding: 20px;
         float: right;
      }
      #votebtn{
         padding: 5px;
         border-radius: 5px;
         background-color: blue;
         color: white;
      }
      #mainpannel{
         padding: 10px;
      }
      #voted{
         padding: 5px;
         border-radius: 5px;
         background-color: green;
         color: white;
      }
    </style>
  </head>

  <body>
    <div id="mainsection">
      <div id="headersection">
        <a href="../"><button id="backbtn">Back</button></a>
        <a href="logout.php"><button id="logoutbtn">Logout</button></a>
        <h1>Online Voting System</h1>
      </div>
      <hr>
      <div id="mainpanel">
        <div id="profile">
          <center>
            <img
              src="../uploads/<?php echo $userdata['photo'] ?>"
              style="object-fit: contain;"
              height="100"
              width="100"
              alt="User Photo"
            >
          </center>
          <br><br>
          <b>Name:</b> <?php echo $userdata['name'] ?> <br><br>
          <b>Mobile:</b> <?php echo $userdata['mobile'] ?><br><br>
          <b>Address:</b> <?php echo $userdata['address'] ?><br><br>
          <b>Status:</b> <?php echo $status ?><br><br>
        </div>

        <div id="Group">
          <?php if ($groupsdata) {
    foreach ($groupsdata as $group) { ?>
            <div>
              <img
                src="../uploads/<?php echo $group['photo'] ?>"
                height="100"
                width="100"
                style="object-fit: contain;"                            
                alt="Group Photo"
              >
              <b>Group Name: </b><?php echo $group['name'] ?>
              <br><br>
              <b>Votes: </b> <?php echo $group['votes'] ?>
              <br><br>
              <form action="../api/vote.php" method="POST">
                <input
                  type="hidden"
                  name="gvotes"
                  value="<?php echo $group['votes'] ?>"
                >
                <input
                  type="hidden"
                  name="gid"
                  value="<?php echo $group['id'] ?>"
                >
                <?php if ($_SESSION['userdata']['status'] == 0 && voteAllowed()) { ?>
                  <input
                    type="Submit"
                    name="votebtn"
                    value="Vote" id="votebtn"
                  >
                <?php } else { ?>
                  <button 
                    disabled
                    type="button"
                    name="votebtn"
                    values="vote"
                    id="voted"
                    > <?php echo voteAllowed() ? "Voted" : "Voting paused" ?>
                  </button>
                <?php } ?>
              </form>
            </div>
            <hr>
           <?php }
} ?>
        </div>
      </div>
    </div>
  </body>
</html>
