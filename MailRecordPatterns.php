<?php

/**
 * A filter designed specifcally for MongoDB. It sets the date_sent to the current date object
 * generated by the MongoDate class.
 */
MailRecords::addFilter('MailRecords', 'create','filter', function($data, $options) {
	
	if(PVDatabase::getDatabaseType() == 'mongo') {
		$data['data']['date_sent'] = new MongoDate();
	}
	
	return $data;
}, array('type' => 'closure', 'event' => 'args'));

/**
 * Adds an oberser to emails sent through sendEmailPHP method. The observer utilizes the model MailRecords
 * and adds emails to the collection owned by the model.
 */
PVMail::addObserver('PVMail::sendEmailPHP', 'read_closure', function($args) {
	
	$mail = new MailRecords();
	$mail -> create($args);
	
}, array('type' => 'closure'));

/**
 * Adds an oberser to emails sent through sendEmailSMTP method. The observer utilizes the model MailRecords
 * and adds emails to the collection owned by the model.
 */

PVMail::addObserver('PVMail::sendEMailSMTP', 'read_closure', function($args) {
	$mail = new MailRecords();
	$mail -> create($args);
	
}, array('type' => 'closure'));


/**
 * If Postmarkapp is installed, it will utilize the observer at the end of that application. Every email sent using Postmarkapp will be
 * added to the MailRecords method with the feilds return from PostMarkApp
 */
if(class_exists('Postmarkapp')) {

	Postmarkapp::addObserver('Postmarkapp::send', 'read_closure', function($args, $feedback) {
		
		$data = array();
		
		if(isset($args['To']))
			$data['receiver'] = $args['To'];
		
		if(isset($args['From']))
			$data['sender'] = $args['From'];
		
		if(isset($args['HtmlBody']))
			$data['html_message'] = $args['HtmlBody'];
		
		if(isset($args['TextBody']))
			$data['text_message'] = $args['TextBody'];
		
		if(isset($args['Subject']))
			$data['subject'] = $args['Subject'];
		
		if(isset($args['Cc']))
			$data['carboncopy'] = $args['Cc'];
		
		if(isset($args['Bcc']))
			$data['blindcopy'] = $args['Bcc'];
			
		if(isset($args['ReplyTo']))
			$data['reply_to'] = $args['ReplyTo'];
		
		$callback = json_decode($feedback);
		
		$data['message_id'] = $callback -> MessageID;
		$data['error_code'] = $callback -> ErrorCode;
		$data['response'] = $callback -> Message;
		
		$mail = new MailRecords();
		$mail -> create($data);
		
	}, array('type' => 'closure'));

}