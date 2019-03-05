
<?php



class Course
    {
        
        private $_crn;
        private $_termCode;
        private $_semester;
        private $_year;
        private $_name;
        private $_url;
        private $_url2;
        private $_subject;
        private $_number;
        private $_professor;
        private $_time;
        private $_day;
        private $_location;
        private $_date;
        private $_type;

        public function getCRN() {
            return $this->_crn;
        }
        public function getTermCode() {
            return $this->_termCode;
        }
        public function getSemester() {
            return $this->_semester;
        }
        public function getYear() {
            return $this->_year;
        }
        public function getName() {
            return $this->_name;
        }
        public function getSubject() {
            return $this->_subject;
        }
        public function getNumber() {
            return $this->_number;
        }
        public function getURL() {
            return $this->_url;
        }
        public function getProfessor() {
            return $this->_professor;
        }
        public function getTime() {
            return $this->_time;
        }
        public function getDay() {
            return $this->_day;
        }
        public function getLocation() {
            return $this->_location;
        }
        public function getDate() {
            return $this->_date;
        }
        public function getType() {
            return $this->_type;
        }

        public function __construct($crn, $semester, $year) {
            $this->_crn = $crn;
            
            $this->_semester = $semester;
            $this->_year = $year;
            
            if ($semester==='Fall') {
                $this->_termCode = ($year+1).'10';
            } elseif ($semester==='Winter') {
                $this->_termCode = $year.'20';
            } elseif ($semester==='Summer') {
                $this->_termCode = $year.'30';
            }
            
            $this->_url = sprintf('https://mybanner.gvsu.edu/PROD/bwckschd.p_disp_detail_sched?term_in=%s&crn_in=%s', $this->_termCode, $crn);
            $info = $this->getInfo();
            $info_array = explode(' - ', $info);
            foreach ($info_array as &$item) {
                $item = trim($item);
            }
            $this->_name = $info_array[0];
            $code_array = explode(' ', $info_array[2]);
            $this->_subject = $code_array[0];
            $this->_number = $code_array[1];
            $this->_url2 = sprintf('https://mybanner.gvsu.edu/PROD/bwckctlg.p_disp_listcrse?term_in=%s&subj_in=%s&crse_in=%s&schd_in=LD', $this->_termCode, $this->_subject, $this->_number);
            $info2 = $this->getInfo2($info);
            foreach ($info2 as &$item) {
                $item = trim($item);
                
            }  
            $this->_time = $info2[1];
            $this->_location = $info2[3];
            $this->_date = $info2[4];
            $this->_day = $info2[2];
            $this->_type = $info2[5];
            $this->_professor = $info2[6];
        }
            
        private function getInfo() {
            $html = file_get_contents($this->_url);
            $start_index=strpos($html, '"row" >');
            $end_index=strpos($html, '<br /><br />');
            $datum=substr($html, $start_index+7, ($end_index-$start_index-12));
            return $datum;
        }

        private function getInfo2($info) {
            
            $html = file_get_contents($this->_url2);
            $start_index=strpos($html, $info)+strlen($info);
            $html = substr($html, $start_index);
            $end_index = strpos($html, 'href="mailto:');
            $html = substr($html, 0, $end_index);
            $start_index = strpos($html, 'Instructors')+strlen('Instructors</th> </tr> <tr>');
            $end_index = strpos($html, '<a')-strlen(' (<ABBR title= "Primary">P</ABBR>)<a ');
            $html = substr($html, $start_index, $end_index);
            $data = explode('<td CLASS="dddefault">', $html);
            $data = implode('', $data);
            $data = substr($data, 0, strlen($data)-37);
            $data = explode('</td>', $data);
            return $data;
        }
    }

class DataHandler
    {
        
        public function isValidCourse(Course $course) {
            $crn_length = strlen((string)$course->getCRN());
            if ($crn_length != 5) {
                return FALSE;
            }
            $html = file_get_contents($course->getURL());
            if (!strpos($html, "No detailed class information found")) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function writeCourse(Course $course) {
            include("includes/mysqllogin.php");

            
            #First check if the course is already in the database
            $sql = sprintf("SELECT * FROM Courses WHERE crn = %s AND termCode = %s", $course->getCRN(), $course->getTermCode());
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result)==0) {
                $sql = sprintf("INSERT INTO Courses (crn, termCode, semester, year, name, subject, number, professor, time, day, location, date, type) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $course->getCRN(), $course->getTermCode(), $course->getSemester(), $course->getYear(), $course->getName(), $course->getSubject(), $course->getNumber(), $course->getProfessor(), $course->getTime(), $course->getDay(), $course->getLocation(), $course->getDate(), $course->getType());
               
                $conn->query($sql);
            } else {
                echo "dupe";
                return 1;
            }
        }
        
    }

$db = new DataHandler();
/*
$counter = 20000;
while ($counter < 29999) {
    $course = new Course($counter, 'Winter', '2018');
    if ($db->isValidCourse($course)) {
        $db->writeCourse($course);
    }
    $counter = $counter + 1;
}*/
?>