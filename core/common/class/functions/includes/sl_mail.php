<?php
/**
* 
* @author Julien Veuillet [http://www.wakdev.com/]
* @version 1.0


* @addtogroup sl_functions
* @{

*/


class sl_mail{

	
	var $_mode; // newsletter send mode 
	var $_content; //newsletter content (html or text)
	var $_from; // "From" Email
	var $_replyto; // "Reply-to" Email
	var $_subject; // Mail subject
	var $_charset; // Encode
	
	var $_recipients_emails = array(); // contact list (just email)
	var $_recipients_errors = array(); // contact list (just email)
	var $_errors;
	var $_current; // current position
	var $_total; // total recepient
	/**
	* Contructor
	* @param $content Newsletter content
	*/
	function __construct($content="", $mode="html", $subject="(pas d'objet)", $from="contact", $recipients=null) {
       $this->_current = 0;
       $this->_errors = 0;
       $this->_total = 1000;
       $this->_content = $content;
       $this->_mode = $mode;
       $this->_subject = $subject;
       $this->_from = $from;
	   $this->_charset = "iso-8859-1";
       
       if ($from != "contact" && $this->check_mail_syntax($from)){
       		$this->_from = $from;
       		$this->_replyto = $from;
       } else { return -4; } 
       
       if ($recipients!=null) { 
       		return set_recipients($recipients);
       }
	}
	
	
	/** ------------------------ **/
	/** --- PUBLIC FUNCTIONS --- **/
	/** ------------------------ **/


	/**
	 * Show error description
	 * @param $code Error code
	 * @return string Error description
	 */
	public function show_error($code){
		
		switch($code){
			case -1: //Mode error
				return "Incorrect send mode";
				break;
			case -2: //Recipients
				return "Recipients is not a array";
				break;
			case -3: //Mail syntaxe
				return "Mail syntax error";
				break;
			case -4: //
				return "Mail send error";
				break;
			case -5: // No Value
				return "Empty value error";
				break;
			default:
				return $code." :: unknow error code";
		}
	}

	/**
	 * Send mode selection
	 * @param $mode Send mode ("html" or "text")
	 */
	public function set_mode($mode) {
		if ($mode == "html" || $mode == "text") {
			$this->_mode = $mode;
			return 1;
		}else { return -1; }
	}

	/**
	 * Send charset
	 * @param $mode Send mode ("html" or "text")
	 */
	public function set_charset($enc) {
		$this->_charset = $enc;
	}	
	
	
	/**
	 * Increment Send
	 * @param $nb Increment n
	 */
	public function send_inc($nb) {	
		
		$i = 0;
		
		while($i < $nb && $this->_current < $this->_total) {
			
			$email = $this->_recipients_emails[$this->_current];
			
			if ($this->check_mail_syntax($email)){
				$value = 1;
				$value = $this->send_email($email);
				if ($value != 1) {
					$this->_recipients_errors[$this->_current] = -4;
					$this->_errors++;
				}else { $this->_recipients_errors[$this->_current] = 1; }			
			}else{ $this->_recipients_errors[$this->_current] = -3; $this->_errors++; }
	
			$i++;
			$this->_current++;
		}	
	}
	
	
	/**
	 * Send all
	 */
	public function send_all() {	
		
		while($this->_current < $this->_total) {
			
			$email = $this->_recipients_emails[$this->_current];
			
			if ($this->check_mail_syntax($email)){
				$value = $this->send_email($email);
				if ($value != 1) {
					$this->_recipients_errors[$this->_current] = -4;
					$this->_errors++;
				}else {$this->_recipients_errors[$this->_current] = 1; }			
			}else{ $this->_recipients_errors[$this->_current] = -3; $this->_errors++;}
			$this->_current++;
		}	
	}
	
	
	
	/**
	 * Set subject of newsletter
	 * @param $subject Text
	 */
	public function set_subject($subject) {
		if ($subject) {
			$this->_subject = $subject;
		}else { return -5; }
	}
	
	/**
	 * Set "From" and "Reply-to" mail adress
	 * @param $from Mail for "from"
	 * @param $reply Mail for "reply-to"
	 */
	public function set_sender($from,$reply) {
		
		if ($this->check_mail_syntax($from)){ 
			if ($reply != ""){
				if ($this->check_mail_syntax($reply)){ 
					$this->_replyto = $reply;
				}else { return -3; }
			}else{
				$this->_replyto = $from;	
			} 
			$this->_from = $from;	
		} else { return -3; }
				
	}
	
	/**
	 * Set Newsletter content
	 * @param $content
	 */
	public function set_content($content) {
		if ($content) {
			$this->_content = $content;
		}else { return -5; }
		 
	}

	
	/**
	 * Recipients configuration 
	 * @param $recipients Recipients Mails Array
	 * @return boolean Success or error
	 */
	public function set_recipients($recipients) {
		if (is_array($recipients)){
			$this->_recipients_emails = $recipients;
			$this->_total = count($recipients);
			return 1;
		} else { return -2;	}	
	}
	
	
	/**
	 * Newsletter send test
	 * @param $email Recipient email for the test
	 */
	public function send_test($email) {
					
		if ($this->check_mail_syntax($email)){
			$value = $this->send_email($email);
			if ($value != 1) {
				return -4;
			}else { return 1; }			
		}else{ return -3; }
		
	}
	
	/**
	 * Send
	 * @param $email Recipient email
	 */
	public function send($email) {
					
		if ($this->check_mail_syntax($email)){
			$value = $this->send_email($email);
			if ($value != 1) {
				return -4;
			}else { return 1; }			
		}else{ return -3; }
		
	}
	
	/** ------------------------- **/
	/** --- PRIVATE FUNCTIONS --- **/
	/** ------------------------- **/
	
	/**
	 * Mail function
	 */
	private function send_email($email){
				
		$headers  = "MIME-Version: 1.0"."\r\n".
     				"Content-type: text/html; charset=".$this->_charset."\r\n".
		 			"From: ".$this->_from."\r\n".
     				"Reply-To: ".$this->_replyto."\r\n".
     				"X-Mailer: PHP/".phpversion();
	
		return mail($email,$this->_subject,$this->_content,$headers);
	}
	
	
	
	/** 
	 * Email syntax verification
	 * @param @email Email
	 * @return boolean True or false
	 */
	private function check_mail_syntax($email){
		if (!strpos($email,"@")){ return false;}
		if (!strpos($email,".")){ return false;}
		return true;
	}
	 
	 
}

/** 
* @} 
*/

?>