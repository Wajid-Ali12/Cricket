    <?php
    // Start session to access session variables
    include('../Join/register.php');

    if (!isset($_SESSION['username'])) {
        header("Location: ../Join/join.php");
        exit();
    }
    include('./profileregister.php');
    // Establish database connection
    $conn = mysqli_connect("localhost", "root", "", "cricket");

    // Initialize success and error messages
    $successMessage = $errorMessage = '';

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Check if the form is submitted for updating profile information
        if (isset($_POST['updateProfile'])) {
            // Get form data
            $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $age = $_POST['age'];
            $mobile = $_POST['mobile'];
            $email = $_POST['email'];
            $address = $_POST['address'];

            // Update profile information
            if (updateProfileInfo($conn, $userId, $isAdmin, $firstname, $lastname, $age, $mobile, $email, $address)) {
                // Profile information updated successfully
                $successMessage = "Profile information updated successfully";
            } else {
                // Error updating profile information
                $errorMessage = "Error updating profile information";
            }
        }

        // Check if the form is submitted for changing password
        if (isset($_POST['changePassword'])) {
            // Get form data
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            // Validate password fields
            if ($newPassword !== $confirmPassword) {
                $errorMessage = "New password and confirm password do not match.";
            } else {
                // Check if current password matches
                $passwordCheck = mysqli_query($conn, "SELECT * FROM register WHERE id = '$userId'");
                $row = mysqli_fetch_assoc($passwordCheck);
                if ($currentPassword !== $row['password']) {
                    $errorMessage = "Incorrect current password.";
                } else {
                    // Update password in the database
                    $sql = "UPDATE register SET password = '$newPassword' WHERE id = '$userId'";
                    if (mysqli_query($conn, $sql)) {
                        $successMessage = "Password changed successfully.";
                    } else {
                        $errorMessage = "Error updating password.";
                    }
                }
            }
        }
    }
    // Function to update profile information in the database
    function updateProfileInfo($conn, $userId, $isAdmin, $firstname, $lastname, $age, $mobile, $email, $address)
    {
        // Prepare update query
        $sql = "UPDATE profileinfo SET isAdmin=? ,firstname=?, lastname=?, age=?, mobile=?, email=?, address=? WHERE id=?";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ssisssi", $isAdmin, $firstname, $lastname, $age, $mobile, $email, $address, $userId);

        // Execute statement
        if ($stmt->execute()) {
            // Profile information updated successfully
            return true;
        } else {
            // Error updating profile information
            return false;
        }
    }

    // Function to fetch user's profile information from the database
    function getUserProfileInfo($conn, $id)
    {
        $sql = "SELECT isAdmin,firstname, lastname, age, mobile, email, address FROM profileinfo WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get user's profile information
    $userProfile = getUserProfileInfo($conn, $_SESSION['user_id']);

    ?>

    <!DOCTYPE html>
    <html lang="en" dir="ltr">

    <head>
        <title>Profile</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../Landing Page/homepage.php"><img src="../Landing Page/img/cricketlogo.png" alt=""></a>
                </div>
                <div class="collapse navbar-collapse" id="navbar-collapse-man">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../Team Information Form/teamInfoForm.php">TEAM</a></li>
                        <li><a href="../Contract Form/contractForm.php">CONTRACT</a></li>
                        <li><a href="../Club Registration Form/clubRegistration.php">CLUB</a></li>
                        <li><a href="../Player Registration Form/playerRegistration.php">PLAYER</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="glyphicon glyphicon-cog"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="divider"></li>
                                <li><a href="../Profile/profile.php"><i class="glyphicon glyphicon-user"></i>&nbsp; Profile</a></li>
                                <li class="divider"></li>

                                <li><a href="../Profile/logout.php"><i class="glyphicon glyphicon-log-out"></i>&nbsp; Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <br />
        <br />
        <hr>

        <hr>
        <div id="profile-body" class="container bootstrap snippet">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="welcome-profile">
                        <?php
                        echo "Welcome " . $_SESSION['username'];
                        ?></h1>
                </div>
            </div>

            <div class="container">
                <div class="d-flex justify-content-between">
                    <h2>User Profile</h2>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">

                        <hr>
                        <div class="panel panel-default">
                            <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> Profile Info</div>
                            <div class="panel-body">
                                <?php if ($userProfile) : ?>
                                    <!-- Display user's profile information -->
                                    <p><strong>First Name:</strong> <?php echo $userProfile['firstname']; ?></p>
                                    <p><strong>Last Name:</strong> <?php echo $userProfile['lastname']; ?></p>
                                    <p><strong>Age:</strong> <?php echo $userProfile['age']; ?></p>
                                    <p><strong>Mobile:</strong> <?php echo $userProfile['mobile']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $userProfile['email']; ?></p>
                                    <p><strong>Address:</strong> <?php echo $userProfile['address']; ?></p>
                                <?php else : ?>
                                    <p>No profile information available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Tabs/Forms -->
                    <div class="col-md-9">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#editProfile">Edit Profile</a></li>
                            <li><a data-toggle="tab" href="#changePassword">Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- Edit Profile Tab/Form -->
                            <div id="editProfile" class="tab-pane fade in active">
                                <h3>Edit Profile</h3>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-group">
                                        <label for="firstName">First Name:</label>
                                        <input type="text" class="form-control" id="firstName" name="firstname" placeholder="Enter first name" value="<?php echo $userProfile['firstname']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last Name:</label>
                                        <input type="text" class="form-control" id="lastName" name="lastname" placeholder="Enter last name" value="<?php echo $userProfile['lastname']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Age:</label>
                                        <input type="text" class="form-control" id="age" name="age" placeholder="Enter Age" value="<?php echo $userProfile['age']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo $userProfile['email']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone:</label>
                                        <input type="tel" class="form-control" id="phone" name="mobile" placeholder="Enter phone" value="<?php echo $userProfile['mobile']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address:</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" value="<?php echo $userProfile['address']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="isAdmin">Is Admin:</label>
                                        <input type="checkbox" id="isAdmin" name="isAdmin" <?php if ($userProfile['isAdmin'] == 1) echo "checked"; ?>>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                            <!-- Change Password Tab/Form -->
                            <div id="changePassword" class="tab-pane fade">
                                <h3>Change Password</h3>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="form-group">
                                        <label for="currentPassword">Current Password:</label>
                                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter current password">
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password:</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm Password:</label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="changePassword">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // JavaScript code to display success and error messages
                $(document).ready(function() {
                    var successMessage = "<?php echo $successMessage; ?>";
                    var errorMessage = "<?php echo $errorMessage; ?>";

                    if (successMessage) {
                        alert(successMessage); // You can use any alert library or toast container here
                    }

                    if (errorMessage) {
                        alert(errorMessage); // You can use any alert library or toast container here
                    }
                });
            </script>
    </body>

    </html>