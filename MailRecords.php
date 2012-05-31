<?php
/**
 * MailRecords is a Model and Collection designed to work helium MVC. The purpose of the MailRecords is to record all emails
 * sent through the system. MailRecords is designed to be loosely copy and unintrusive by only acting as an observer after an
 * email has been sent.
 */
class MailRecords extends Model {
	
	protected $_schema = array(
		'mail_id' => array('type' => 'int', 'auto_increment' => true, 'primary_key' => true),
		'sender' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'receiver' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'subject' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'carboncopy' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'blindcopy' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'reply_to' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'return_path' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'errors_to' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'message_id' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'attachment' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'html_message' => array('type' => 'text', 'default' => ''),
		'text_message' => array('type' => 'text', 'default' => ''),
		'date_sent' => array('type' => 'datetime', 'default' => 'now()'),
		'bounce_hook' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'error_code' => array('type' => 'string', 'precision' => 255, 'default' => ''),
		'response' => array('type' => 'string', 'precision' => 255, 'default' => ''),
	);
	
	/**
	 * The constructor is used to change certain fields to make the Model database agnostic as possible.
	 * 
	 * @param array $data Same function and data as the parent model
	 * @param array $data Same function and data as the parent model
	 * 
	 * @return void
	 * @access public
	 */
	public function __construct($data = null, array $options = array()) {
		
		$database_type = PVDatabase::getDatabaseType();
		
		if($database_type == 'postgresql') {
			$this->_schema['mail_id']['type'] = 'serial';
			$this->_schema['date_sent']['default'] = 'now()';
		} else if($database_type == 'mysql') {
			$this->_schema['mail_id']['type'] = 'int';
			$this->_schema['date_sent']['default'] = 'CURRENT_TIMESTAMP';
		} else if($database_type == 'mongo') {
			$this->_schema['mail_id']['type'] = 'id';
			$this->_schema ['_id'] = $this->_schema['mail_id'];
			unset($this->_schema['mail_id']);
		}
		
		parent::__construct($data, $options);	
	}
	
	/**
	 * Resends an exact email after is has been sent.
	 * 
	 * @return void
	 * @access public
	 */
	public function resend() {
		PVMail::sendEmail($this -> getIterator() -> getData());
	}
	
}
