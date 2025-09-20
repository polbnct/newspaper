<?php
require_once '../classloader.php';

// --- User Registration, Login, and Logout blocks ---

// NOTE: This block is for user registration, which may not be needed in an admin-only area.
// It is kept here as it was in your original file.
if (isset($_POST['insertNewUserBtn'])) {
	// ... (your existing registration code would go here)
}

// FIXED: Added the complete, corrected login handling block.
if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			// On successful login, redirect to the admin dashboard
			header("Location: ../index.php");
			exit; // CRITICAL: Always stop the script after a redirect.
		}
		else {
			// On failed login, set a message and redirect back to the login page
			$_SESSION['message'] = "Invalid email or password. Please try again.";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
			exit; // CRITICAL: Always stop the script after a redirect.
		}
	}
	else {
		// If fields are empty, set a message and redirect back
		$_SESSION['message'] = "Please make sure there are no empty input fields.";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
		exit; // CRITICAL: Always stop the script after a redirect.
	}
}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
	exit; // FIXED: Added exit to ensure the redirect happens correctly.
}


if (isset($_POST['editArticleBtn'])) {
	$title = htmlspecialchars(trim($_POST['title']));
	$description = htmlspecialchars(trim($_POST['description']));
	$article_id = $_POST['article_id'];
    $category_id = $_POST['category_id'];

	if ($articleObj->updateArticle($article_id, $title, $description, $category_id)) {
		header("Location: ../articles_submitted.php");
        exit; // Added exit for consistency and safety.
	} else {
        $_SESSION['message'] = "Failed to update the article.";
        $_SESSION['status'] = '400';
        header("Location: ../edit_article.php?id=" . $article_id);
        exit; // Added exit for consistency and safety.
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

    if ($status == 1 && isset($notificationObj)) {
        $article = $articleObj->getArticles($article_id);
        if ($article) {
            $message = "Congratulations! Your article \"" . htmlspecialchars($article['title']) . "\" has been approved and is now published.";
            $notificationObj->createNotification($article['author_id'], $message);
        }
    }

	echo $articleObj->updateArticleVisibility($article_id, $status);
}


if (isset($_POST['addCategoryBtn'])) {
    $category_name = htmlspecialchars(trim($_POST['category_name']));

    if (!empty($category_name)) {
        if ($articleObj->addCategory($category_name)) {
            header("Location: ../categories.php");
        } else {
            $_SESSION['message'] = "Could not add category. It may already exist.";
            $_SESSION['status'] = '400';
            header("Location: ../categories.php");
        }
    } else {
        $_SESSION['message'] = "Category name cannot be empty.";
        $_SESSION['status'] = '400';
        header("Location: ../categories.php");
    }
    exit;
}


if (isset($_POST['approveEditBtn'])) {
    $request_id = $_POST['request_id'];
    $article_id = $_POST['article_id'];
    $user_id = $_POST['user_id'];

    if (isset($editRequestObj)) {
        $editRequestObj->updateRequestStatus($request_id, 'approved');
    }
    
    if (isset($articleObj)) {
        $articleObj->setEditableStatus($article_id, 1); 
    }

    if (isset($notificationObj)) {
        $article = $articleObj->getArticles($article_id);
        if ($article) {
            $message = "Your request to edit the article \"" . htmlspecialchars($article['title']) . "\" has been approved. You can now make your changes.";
            $notificationObj->createNotification($user_id, $message);
        }
    }

    header("Location: ../edit_requests.php");
    exit;
}

if (isset($_POST['denyEditBtn'])) {
    $request_id = $_POST['request_id'];
    $user_id = $_POST['user_id'];
    $article_id = $_POST['article_id'];

    if (isset($editRequestObj)) {
        $editRequestObj->updateRequestStatus($request_id, 'denied');
    }
    
    if (isset($notificationObj)) {
        $article = $articleObj->getArticles($article_id);
        if ($article) {
            $message = "Your request to edit the article \"" . htmlspecialchars($article['title']) . "\" has been denied by an administrator.";
            $notificationObj->createNotification($user_id, $message);
        }
    }

    header("Location: ../edit_requests.php");
    exit;
}

?>