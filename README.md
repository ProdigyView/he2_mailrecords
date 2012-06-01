HE2 MailRecords
==============

MailRecords is a model/collected design for Helium. The purpose of MailRecords is to record all outgoing emails that are sent through the web application.

##How It Works
MailRecords by creating observers through anonymous function for the methods PVMail::sendEmailPHP and PVMail::sendEmailSMPT. Depending on your configuration, PVMail:sendEmail method with use on of these methods. 

Each time one of these methods finishes executing, the observer will be fired and the data will be dumped into the model MailRecords which will store in the database.

##Installation
1. Copy or clone the files in h2_mailrecords into your designated librares folder.
2. In bootstrap, add the library to your system with the load set to explicit.

###Example
<pre><code>
PVLibraries::addLibrary('he2_mailrecords', array('path' => 'explicit_load' => true));
</code></pre>

##Postmarkapp Hook
This installation comes with a Postmarkapp hook that will record all emails sent using the Postmarkapp application. https://github.com/ProdigyView/he2_mailrecords

Furthers hooks can be added as needed.

