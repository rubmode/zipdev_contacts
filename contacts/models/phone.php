<?php

class Phone
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
        $phone      = $this->body['phone'];
        $insert     = $db->query('INSERT INTO phones (contact_id, phone) VALUES ("'.$contact_id.'","'.$phone.'")');
        return $insert->insertedId();
    }

    public function read()
    {
        global $db;
        if(isset($this->body['contact_id']))
        {
            $contact_id = $this->body['contact_id'];
            $phones = $db->query('SELECT * FROM phones WHERE contact_id = '.intval($contact_id).'')->fetchAll();
        }
        else if(isset($this->body['phone']))
        {
            $phone = $this->body['phone'];
            $phones = $db->query('SELECT * FROM phones WHERE phone = '.$phone.'')->fetchAll();
        }
        return $phones;
    }

    public function update()
    {
        global $db;
        if(isset($this->body['phone_id']))
        {
            $phone = $this->body['phone'];
            $phone_id = $this->body['phone_id'];
            $updated_phones = $db->query('UPDATE phones SET phones.phone = "'.$phone.'" WHERE phones.phone_id = "'.intval($phone_id).'"');
        }
        return $updated_phones->affectedRows();
    }

    public function delete()
    {
        global $db;
        $phone_id = $this->body['phone_id'];
        $deleted_phones = $db->query('DELETE FROM phones WHERE phone_id = '.intval($phone_id).'');
        return $deleted_phones->affectedRows();
    }
}