<?php

require_once 'Database.php';
require_once 'User.php';

/**
 * The single, unified class for handling all Article-related operations.
 * This class is used by the admin panel, writer panel, and public-facing pages.
 */
class Article extends Database
{

    public function createArticle($title, $content, $author_id, $category_id, $imagePath, $is_active = true)
    {
        $sql = "INSERT INTO articles (title, content, author_id, category_id, image_path, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$title, $content, $author_id, $category_id, $imagePath, $is_active]);
    }


    public function getArticles($id = null)
    {
        $sqlBase = "FROM articles a 
                    JOIN school_publication_users u ON a.author_id = u.user_id 
                    LEFT JOIN categories c ON a.category_id = c.category_id";

        if ($id) {
            $sql = "SELECT a.*, u.username, u.is_admin, c.category_name 
                    $sqlBase
                    WHERE a.article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        
        $sql = "SELECT a.*, u.username, u.is_admin, c.category_name 
                $sqlBase
                ORDER BY a.created_at DESC";

        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null)
    {
        $sqlBase = "FROM articles a 
                    JOIN school_publication_users u ON a.author_id = u.user_id 
                    LEFT JOIN categories c ON a.category_id = c.category_id";
        
        if ($id) {
            $sql = "SELECT a.*, u.username, u.is_admin, c.category_name
                    $sqlBase
                    WHERE a.article_id = ? AND a.is_active = 1";
            return $this->executeQuerySingle($sql, [$id]);
        }
        
        // Added 'u.is_admin' to the SELECT statement to prevent errors on index.php
        $sql = "SELECT a.*, u.username, u.is_admin, c.category_name 
                $sqlBase
                WHERE a.is_active = 1 ORDER BY a.created_at DESC";

        return $this->executeQuery($sql);
    }

    public function getArticlesByUserID($user_id)
    {
        $sql = "SELECT a.*, c.category_name 
                FROM articles a
                LEFT JOIN categories c ON a.category_id = c.category_id
                WHERE a.author_id = ? 
                ORDER BY a.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function updateArticle($id, $title, $content, $category_id)
    {
        $sql = "UPDATE articles SET title = ?, content = ?, category_id = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $category_id, $id]);
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

    public function addCategory($category_name)
    {
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
        return $this->executeNonQuery($sql, [$category_name]);
    }


    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY category_name ASC";
        return $this->executeQuery($sql);
    }
}
?>