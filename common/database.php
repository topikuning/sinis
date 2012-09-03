<?php
ini_set("date.timezone", "Asia/Jakarta");

require_once(dirname(__FILE__)."/koneksi.php");

class database {
    private $mysql_host;
    private $mysql_user;
    private $mysql_passwd;
    private $mysql_db;
    private $mysql_link;
    private $connected;
    private $where;

    public function  __construct() {
        global $mysql_host;
        global $mysql_user;
        global $mysql_passwd;
        global $mysql_db;
        $this->mysql_host = $mysql_host;
        $this->mysql_user = $mysql_user;
        $this->mysql_passwd = $mysql_passwd;
        $this->mysql_db = $mysql_db;
        $this->connected=false;
    }

    public function connect() {
        if($this->connected==false) {
            try {
                $this->mysql_link = mysql_connect($this->mysql_host, $this->mysql_user, $this->mysql_passwd);
                if (false === mysql_select_db ($this->mysql_db)) {
                   die("Cannot select database '$dbname'. MySQL error is: " . mysql_error());
                }
                $this->connected = true;
            }catch(Exception $e) {
                die($e->GetMessage());
            }
        }
    }

    public function disconnect() {
        if($this->connected==true) {
            mysql_close($this->mysql_link);
            $this->connected = false;
        }
    }

    public function runQuery($query) {
        $this->connect();
        $rs = mysql_query($query) or die('Query failed: ' . mysql_error() . $query);
        $this->disconnect();
        return $rs;
    }

    public function runQueryLowLevel($query) {
        $rs = mysql_query($query) or die('Query failed: ' . mysql_error() . $query);
        return $rs;
    }

    public function getEscapeString($text) {
        $hasil = trim($text);
        //$hasil = mysql_real_escape_string($hasil);
        $hasil = str_replace("'", "''", $hasil);
        return $hasil;
    }
    
    public function getNamaRuang($id_ruang){
        $query = "select ruang from rm_ruang where id_ruang='".$id_ruang."'";
        $result = $this->runQuery($query);
        return @mysql_result($result, 0, 'ruang');
    }

    public function clearWhere(){
        $this->where = "where 1=1";
    }
    public function addWhere($field,$value){
        $where = $field;
        if(strpos($value,"*")){
            $where = $where. " like '".$this->getEscapeString(str_replace("*", "%", $value))."'";
        }else{
            $where = $where. " = '".$this->getEscapeString( $value )."'";
        }
        $this->where = $this->where." and ".$where;
    }

    public function setWhere($where){
        $this->clearWhere();
        $this->where = $this->where." and ".$where;
    }
}


?>
