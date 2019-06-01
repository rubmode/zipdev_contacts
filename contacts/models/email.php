<?php

class Email
{
    public $body = array();

    public function __construct($body)
    {
        $this->body = $body;
    }

    public function insert()
    {
        global $db;
        $contact_id = $this->body['contact_id'];
        $email      = $this->body['email'];
        $insert     = $db->query('INSERT INTO emails (contact_id, email) VALUES ("'.$contact_id.'","'.$email.'")');
        return $insert->insertedId();
    }

    public function read()
    {
        global $db;
        if(isset($this->body['contact_id']))
        {
            $contact_id = $this->body['contact_id'];
            $emails = $db->query('SELECT * FROM emails WHERE contact_id = '.intval($contact_id).'')->fetchAll();
        }
        else if(isset($this->body['email']))
        {
            $email = $this->body['email'];
            $emails = $db->query('SELECT * FROM emails WHERE email = "'.$email.'"')->fetchAll();
        }
        return $emails;
    }

    public function update()
    {
        global $db;
        if(isset($this->body['email_id']))
        {
            $email = $this->body['email'];
            $email_id = $this->body['email_id'];
            $updated_emails = $db->query('UPDATE emails SET emails.email = "'.$email.'" WHERE emails.email_id = "'.$email_id.'"');
        }
        return $updated_emails->affectedRows();
    }

    public function delete()
    {
        global $db;
        $email_id = $this->body['email_id'];
        $deleted_emails = $db->query('DELETE FROM emails WHERE email_id = '.intval($email_id).'');
        return $deleted_emails->affectedRows();
    }
}