<?php
require_once '../classloader.php';

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

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

if (isset($_POST['insertAdminArticleBtn'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $author_id = $_SESSION['user_id'];
    $imagePath = null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = basename($_FILES['article_image']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $_FILES['article_image']['size'];
        $maxSize = 5 * 1024 * 1024;

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

    if ($articleObj->createArticle($title, $description, $author_id, $imagePath)) {
        header("Location: ../index.php");
    } else {
        $_SESSION['message'] = "Failed to create the article.";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
    }
}


if (isset($_POST['editArticleBtn'])) {
	$title = htmlspecialchars(trim($_POST['title']));
	$description = htmlspecialchars(trim($_POST['description']));
	$article_id = $_POST['article_id'];
	if ($articleObj->updateArticle($article_id, $title, $description)) {
		header("Location: ../articles_submitted.php");
	}
}

if (isset($_POST['deleteArticleBtn'])) {
    $article_id = $_POST['article_id'];

    $article_to_delete = $articleObj->getArticles($article_id);
    if ($article_to_delete && isset($notificationObj)) {
        $author_id = $article_to_delete['author_id'];
        $article_title = $article_to_delete['title'];
        $message = "An administrator has removed your article titled: \"" . htmlspecialchars($article_title) . "\".";
        $notificationObj->createNotification($author_id, $message);
    }
    
    echo $articleObj->deleteArticle($article_id);
}


if (isset($_POST['updateArticleVisibility'])) {
	$article_id = $_POST['article_id'];
	$status = $_POST['status'];
	echo $articleObj->updateArticleVisibility($article_id,$status);
}

// ==============================================
// ===== NEW BLOCKS FOR MANAGING EDIT REQUESTS =====
// ==============================================

if (isset($_POST['approveEditBtn'])) {
    $request_id = $_POST['request_id'];
    $article_id = $_POST['article_id'];

    // Step 1: Mark the request as 'approved' in the edit_requests table
    if (isset($editRequestObj)) {
        $editRequestObj->updateRequestStatus($request_id, 'approved');
    }
    
    // Step 2: Unlock the article by setting is_editable = 1 in the articles table
    if (isset($articleObj)) {
        $articleObj->setEditableStatus($article_id, 1); 
    }

    header("Location: ../edit_requests.php");
    exit;
}

if (isset($_POST['denyEditBtn'])) {
    $request_id = $_POST['request_id'];

    // Just mark the request as 'denied'
    if (isset($editRequestObj)) {
        $editRequestObj->updateRequestStatus($request_id, 'denied');
    }

    header("Location: ../edit_requests.php");
    exit;
}