<?php
require_once '../classloader.php';

// --- This block is for the registration form ---
if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

// --- This block is for the login form ---
if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

// --- This block is for the logout button ---
if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}


// --- THIS IS THE CORRECTED BLOCK FOR THE WRITER'S ARTICLE SUBMISSION ---
if (isset($_POST['insertArticleBtn'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $author_id = $_SESSION['user_id'];
    $imagePath = null; // Default to null if no image is uploaded

    // --- Start Image Upload Handling ---
    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = basename($_FILES['article_image']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $_FILES['article_image']['size'];
        $maxSize = 5 * 1024 * 1024; // 5 MB limit
        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
            $uniqueFileName = uniqid('', true) . '.' . $fileType;
            $targetPath = $uploadDir . $uniqueFileName;
            if (move_uploaded_file($_FILES['article_image']['tmp_name'], $targetPath)) {
                $imagePath = 'uploads/' . $uniqueFileName;
            } else {
                die("Error: There was a problem uploading your file.");
            }
        } else {
            die("Error: Invalid file type or size.");
        }
    }
    // --- End of Image Upload Handling ---

    if ($articleObj->createArticle($title, $description, $author_id, $imagePath)) {
        header("Location: ../index.php");
    } else {
		$_SESSION['message'] = "Failed to create the article.";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
	}
}


// --- This block is for when a writer saves their edited article ---
if (isset($_POST['editArticleBtn'])) {
	$title = htmlspecialchars(trim($_POST['title']));
	$description = htmlspecialchars(trim($_POST['description']));
	$article_id = $_POST['article_id'];
	if ($articleObj->updateArticle($article_id, $title, $description)) {
        // After updating, re-lock the article
        $articleObj->setEditableStatus($article_id, 0);
		header("Location: ../index.php");
	}
}

if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	echo $articleObj->deleteArticle($article_id);
}


// --- This block handles marking notifications as read ---
if (isset($_POST['markNotificationAsRead'])) {
    $notification_id = $_POST['notification_id'];
    if ($notificationObj->markAsRead($notification_id)) {
        echo 'success';
    } else {
        http_response_code(500);
        echo 'failed';
    }
}

// ===============================================
// ===== NEW BLOCK FOR CREATING EDIT REQUEST =====
// ===============================================
if (isset($_POST['requestEditBtn'])) {
    $article_id = $_POST['article_id'];
    $user_id = $_SESSION['user_id'];

    // Assumes $editRequestObj is available from classloader
    if (isset($editRequestObj)) {
        if ($editRequestObj->createRequest($article_id, $user_id)) {
            echo 'success';
        } else {
            http_response_code(400); // Bad Request (likely a duplicate)
            echo 'failed';
        }
    } else {
        http_response_code(500); // Server error
        echo 'server_error';
    }
}