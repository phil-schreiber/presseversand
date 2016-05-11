<?php
require_once('bounce_driver.class.php');
class Mailreader {

	// imap server connection
	public $conn;

	// inbox storage and inbox message count
	private $inbox;
	private $msg_cnt;

	// email login credentials
	private $server = '';
	private $user   = '';
	private $pass   = '';
	private $port   = 0; // adjust according to server settings

	// connect to the server and get the inbox emails
	function __construct($server,$user,$pass,$port) {
		$this->server=$server;
                $this->user=$user;
                $this->pass=$pass;
                $this->port=$port;
                $this->connect();
                $this->msg_cnt=imap_num_msg($this->conn);
                
	}

        public function processMails(){
            $bouncehandler = new \Bouncehandler();

            # get the failures
            $email_addresses = array();
            $delete_addresses = array();
            for ($n=1;$n<=$this->msg_cnt;$n++) {
            $bounce = imap_fetchheader($this->conn, $n).imap_body($this->conn, $n); //entire message
            $multiArray = $bouncehandler->get_the_facts($bounce);
              if (!empty($multiArray[0]['action']) && !empty($multiArray[0]['status']) && !empty($multiArray[0]['recipient']) ) {
                if ($multiArray[0]['action']=='failed') {
                $email_addresses[$multiArray[0]['recipient']]++; //increment number of failures
                $delete_addresses[$multiArray[0]['recipient']][] = $n; //add message to delete array
                } //if delivery failed
              } //if passed parsing as bounce
            } //for loop

            # process the failures
              foreach ($email_addresses as $key => $value) { //trim($key) is email address, $value is number of failures
                if ($value>=$delete) {
                /*
                do whatever you need to do here, e.g. unsubscribe email address
                */
                # mark for deletion
                  foreach ($delete_addresses[$key] as $delnum) imap_delete($conn, $delnum);
                } //if failed more than $delete times
              } //foreach

            # delete messages
            imap_expunge($conn);

            # close
            imap_close($conn);
        }
        
	// close the server connection
	private function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;

		imap_close($this->conn);
	}
        
        

	// open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	// these are laid out to connect to a Dreamhost IMAP server
	private function connect() {
		$this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
	}

	// move the message to a new folder
	private function move($msg_index, $folder='INBOX.Processed') {
		// move on server
		imap_mail_move($this->conn, $msg_index, $folder);
		imap_expunge($this->conn);

		// re-read the inbox
		$this->inbox();
	}

	// get a specific message (1 = first email, 2 = second email, etc.)
	private function get($msg_index=NULL) {
		if (count($this->inbox) <= 0) {
			return array();
		}
		elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
			return $this->inbox[$msg_index];
		}

		return $this->inbox[0];
	}

	// read the inbox
	private function inbox() {
		$this->msg_cnt = imap_num_msg($this->conn);

		$in = array();
		for($i = 1; $i <= $this->msg_cnt; $i++) {
			$in[] = array(
				'index'     => $i,
				'header'    => imap_headerinfo($this->conn, $i)
				//'body'      => imap_body($this->conn, $i),
				//'structure' => imap_fetchstructure($this->conn, $i)
			);
		}

		$this->inbox = $in;
	}
        
        public function getInbox(){
            return $this->inbox;
        }

}

?>