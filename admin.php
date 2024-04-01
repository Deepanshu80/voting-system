<?php

session_start();
include ('../api/connect.php');

function getUsers($connect)
{
    $query = 'SELECT * FROM users WHERE role in (1, 2, 3)';
    $result = mysqli_query($connect, $query);
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    return $users;
}

function removeUser($connect, $userId)
{
    $query = 'DELETE FROM users WHERE id = ?';
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
}

function renderUserRole($role)
{
    switch ($role) {
        case 1:
            return 'Voter';
        case 2:
            return 'Group';
        default:
            return '';
    }
}

function renderStatusIcon($status)
{
    return $status == 1 ? '✅' : '❌';
}

function renderVotes($role, $votes) {
  return $role == 1 ? '-' : $votes;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove'])) {
        $userIdToRemove = $_POST['remove'];
        removeUser($connect, $userIdToRemove);
    }
    if (isset($_POST['toggle-voting'])) {
      $allowed = voteAllowed();
      $newVal =  $allowed ? false : true;
      setVoteAllowed($newVal);
    }
}

$users = getUsers($connect);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
    />
</head>

<body class="container">
    <div style="    display: flex;
    justify-content: space-between;
    align-items: center;
padding: 12px 0;
">
<h2 style="padding: 0; margin: 0;">Admin Panel</h2>
<form style="padding: 0; margin: 0;" method="post" action="" >
<button name="toggle-voting" style="margin: 0;" type="submit"><?php echo voteAllowed() ? "Pause Voting" : "Resume Voting" ?></button>
</form>
</div>

    <?php if (count($users) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Role</th>
                <th>Voted</th>
                <th>Votes</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr data-id="<?php echo $user['id']; ?>" >
                    <td><?php echo $user['id']; ?></td>
                    <td>
                      <img
                        src="../uploads/<?php echo $user['photo'] ?>"
                        style="object-fit: contain;"
                        height="100"
                        width="100"
                        alt="User Photo"
                      >
                    </td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['mobile']; ?></td>
                    <td><?php echo $user['address']; ?></td>
                    <td><?php echo renderUserRole($user['role']) ?></td>
                    <td><?php echo renderStatusIcon($user['status']) ?></td>
                    <td><?php echo renderVotes($user['role'], $user['votes']) ?></td>
                    <td>
                       <div role="group">
                          <button class="secondary" onclick="removeUser(this)">🚮</button>
                       </div>
                    </td>
                    <form method="post" action="" >
                        <input name="remove" value="<?php echo $user['id']; ?>"  type="hidden"></input>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <script src="../js/modal.js"></script>
<script>
function findParentWithUserId(element) {
    var currentElement = element.parentElement;
    while (currentElement !== null) {
        if (currentElement.hasAttribute('data-id')) {
            return currentElement;
        }
        currentElement = currentElement.parentElement;
    }
    return null;
}

function removeUser(button) {
  var ele = findParentWithUserId(button);
  ele.querySelector("form").submit();
}
</script>

</body>
</html>
