<?php
require_once 'Database.php';

class EditRequest extends Database
{
    /**
     * Creates a new edit request for an article.
     */
    public function createRequest($article_id, $user_id)
    {
        // Prevent creating duplicate pending requests for the same article
        $sql_check = "SELECT request_id FROM edit_requests WHERE article_id = ? AND status = 'pending'";
        $existing = $this->executeQuerySingle($sql_check, [$article_id]);
        if ($existing) {
            return false; // A pending request already exists
        }
        $sql = "INSERT INTO edit_requests (article_id, user_id) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$article_id, $user_id]);
    }

    /**
     * Fetches all pending edit requests for the admin view.
     */
    public function getPendingRequests()
    {
        $sql = "SELECT er.*, a.title, u.username 
                FROM edit_requests er
                JOIN articles a ON er.article_id = a.article_id
                JOIN school_publication_users u ON er.user_id = u.user_id
                WHERE er.status = 'pending'
                ORDER BY er.created_at DESC";
        return $this->executeQuery($sql);
    }

    /**
     * Updates the status of an edit request ('approved' or 'denied').
     */
    public function updateRequestStatus($request_id, $status)
    {
        $sql = "UPDATE edit_requests SET status = ? WHERE request_id = ?";
        return $this->executeNonQuery($sql, [$status, $request_id]);
    }
}