<?php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PWD', 'babysuse');
define('DB_NAME', 'vacc_db');
class Scheduler {
    private $db_conn;
    private $ppt_query;
    private $pat_query;
    private $ppt_result;
    private $pat_result;
    private $appointment;
    function __construct() {
        $this->db_conn = new mysqli(DB_HOST,DB_USER,DB_PWD,DB_NAME);
        $this->pat_query = "
            SELECT pat_id, provider_id, w_id, t_id, provider_longitude, provider_latitude
            FROM provider_available_time JOIN provider USING(provider_id);";
        $this->ppt_query = "
            SELECT ppt_id, patient_id, w_id, t_id, patient_longitude, patient_latitude, max_distance
            FROM patient_preferred_time JOIN patient USING(patient_id)
            WHERE patient_id NOT IN (
                SELECT DISTINCT patient_id
                FROM appointment
                WHERE status = 'vaccinated' OR
                    status = 'accepted' OR
                    status = 'pending');";
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
        fclose($inputfile);
    }

    private function insert_appointment() {
        echo("\n");
        foreach ($this->appointment as $user => $app) {
            $query = "
                INSERT INTO `appointment` (patient_id, pat_id, status)
                VALUES (".$user.", ".$app["pat_id"].", \"pending\");";
            print("1 query executed:" . $query . "\n");
            /*if (!$this->db_conn->query($query)) {
                print("Failed inserting: " . $db_conn.error);
            }*/
        }
    }

    private function update_data() {
        $this->prepare_data($this->pat_query, "pat_rows.json");
        $this->prepare_data($this->ppt_query, "ppt_rows.json");
    }

    // output/update pat and ppt data for assigner.py
    private function prepare_data($query, $filename) {
        $result = $this->db_conn->query($query, MYSQLI_USE_RESULT);
        if ($result === false) {
            echo $this->db_conn->error;
            exit();
        }
        $rows = array();
        while ($r = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($rows, $r);
        }
        $outfile = fopen($filename, "w");
        fwrite($outfile, json_encode($rows, JSON_PRETTY_PRINT));
        fclose($outfile);
    }
}
?>
