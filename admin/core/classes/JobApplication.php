<?php

/**
 * JobApplication.php — Model for the job_applications table.
 */
class JobApplication {

    private $_db,
            $_data,
            $_count = 0,
            $_errors = [];

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    // ── INSERT ──────────────────────────────────────────────────────
    public function insert($fields = []) {
        $fields['created_date'] = Dates::get("Y-m-d");
        $fields['created_time'] = Dates::get("H:i:s");
        if (!$this->_db->insert('job_applications', $fields)) {
            throw new Exception("Erreur lors de l'enregistrement de la candidature.");
        }
        $result = $this->_db->query("SELECT LAST_INSERT_ID() as lid");
        if ($result && $result->count()) {
            return (int)$result->first()->lid;
        }
        return 0;
    }

    // ── UPDATE ──────────────────────────────────────────────────────
    public function update($fields = [], $id = null) {
        $this->_db->update('job_applications', $id, $fields);
    }

    // ── FIND BY ID ──────────────────────────────────────────────────
    public function find($id = null) {
        if ($id && is_numeric($id)) {
            // Use a direct query to avoid DB::get() argument issues
            $data = $this->_db->query(
                "SELECT * FROM `job_applications` WHERE `ID` = ? LIMIT 1",
                [(int)$id]
            );
            if ($data->count()) {
                $this->_data  = $data->results();
                $this->_count = 1;
                return true;
            }
        }
        return false;
    }

    // ── SELECT (raw WHERE clause) ───────────────────────────────────
    public function select($sql = null) {
        $data = $this->_db->query("SELECT * FROM `job_applications` {$sql}");
        if ($data->count()) {
            $this->_count = $data->count();
            $this->_data  = $data->results();
        }
    }

    // ── SELECT QUERY (full SQL + params) ───────────────────────────
    public function selectQuery($sql, $params = []) {
        $data = $this->_db->query($sql, $params);
        if ($data->count()) {
            $this->_count = $data->count();
            $this->_data  = $data->results();
        }
    }

    // ── HELPERS ────────────────────────────────────────────────────
    public function exists()  { return !empty($this->_data); }
    public function data()    { return $this->_data; }
    public function count()   { return $this->_count; }
    public function errors()  { return $this->_errors; }

    public function first() {
        $data = $this->data();
        if (is_array($data) && isset($data[0])) return $data[0];
        if (is_object($data)) return $data;
        return null;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }
}