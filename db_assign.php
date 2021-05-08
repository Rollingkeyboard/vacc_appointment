<?php
class Schedule {
    private $db_conn;
    private $ppt_result;
    private $pat_result;
    private $appointment;
    function __construct() {
        $this->db_conn = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME);
        $pat_query = "
            SELECT pat_id, provider_id, wid, tid
            FROM provider_available_time;";
        $ppt_query = "
            SELECT ppt_id, patient_id, wid, tid
            FROM patient_preferred_time;";
    }

    function __destruct() {
        $this->db_conn->close();
    }

    // utility schedule function
    public function execute() {
        $this->update_data();
        $this->create_appointment();
        $this->insert_appointment();
    }

    // execute assigner.py
    private function create_appointment() {
        exec("python3 assigner.py");
        $result_file = "appointment.json";
        $inputfile = fopen($result_file, "r");
        $this->appointment = json_decode(fread($inputfile, filesize($result_file)), true);
        $inputfile->close();
    }

    private function insert_appointment() {
        foreach ($this->appointment as $user => $app) {
            $query = "
                INSERT INTO APPOINTMENT (patient_id, pat_id, offered_timestamp, status)
                VALUES (".$user.", ".$app["pat_id"].", "."NOW(), \"pending\");";
            $this->db_conn->query($query);
        }
    }

    private function update_data() {
        $this.prepare_data($pat_query, "pat_rows.json");
        $this.prepare_data($ppt_query, "ppt_rows.json");
    }

    // output/update pat and ppt data for assigner.py
    private function prepare_data($query, $filename) {
        $result = $this->db_conn->query($query, MYSQLI_USE_RESULT);
        $rows = array();
        while ($r = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($rows, $r);
        }
        $outfile = fopen($filename, "w");
        fwrite($outfile, json_encode($rows, JSON_PRETTY_PRINT));
    }
}
?>
