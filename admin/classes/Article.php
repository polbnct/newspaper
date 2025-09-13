<?php

require_once 'Database.php';
require_once 'User.php';

class Article extends Database
{

    public function createArticle($title, $content, $author_id, $imagePath)
    {
        // Updated SQL query to include the image_path column
        $sql = "INSERT INTO articles (title, content, author_id, image_path, is_active) VALUES (?, ?, ?, ?, 1)";
        // Updated parameters array to include the image path
        return $this->executeNonQuery($sql, [$title, $content, $author_id, $imagePath]);
    }


    public function getArticles($id = null)
    {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                ORDER BY articles.created_at DESC";

        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null)
    {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                WHERE is_active = 1 ORDER BY articles.created_at DESC";

        return $this->executeQuery($sql);
    }


    public function getArticlesByUserID($user_id)
    {
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id
                WHERE author_id = ? ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }


    public function updateArticle($id, $title, $content)
    {
        $sql = "UPDATE articles SET title = ?, content = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $id]);
    }


    public function updateArticleVisibility($id, $is_active)
    {
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$is_active, $id]);
    }

    public function deleteArticle($id)
    {
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
    public function setEditableStatus($article_id, $status) {
    $sql = "UPDATE articles SET is_editable = ? WHERE article_id = ?";
    return $this->executeNonQuery($sql, [$status, $article_id]);
}
}

?>
