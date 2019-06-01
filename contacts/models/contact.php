<?php

class Contact
{
    public $name;
    public $surName;

    public function __construct($body)
    {
        $this->body = $body;
    }

    public function insert()
    {
        global $db;
        $name       = $this->body['name'];
        $surname    = $this->body['surname'];
        $image      = '';
        $insert     = $db->query('INSERT INTO contacts (name, surname, image) VALUES ("'.$name.'","'.$surname.'","'.$image.'")');
        return $insert->insertedId();
    }

    public function read()
    {
        global $db;
        if(isset($this->body['contact_id']))
        {
            if($this->body['contact_id']!=0)
            {
                $contact_id = $this->body['contact_id'];
                $contacts = $db->query('SELECT * FROM contacts WHERE contact_id = '.intval($contact_id).'')->fetchAll();
            }
            else
            {
                $contacts = $db->query('SELECT * FROM contacts')->fetchAll();
            }
            
        }
        else if(isset($this->body['surname']))
        {
            $surname = $this->body['surname'];
            $contacts = $db->query('SELECT * FROM contacts WHERE surname = "'.$surname.'"')->fetchAll();
        }
        else if(isset($this->body['name']))
        {
            $name = $this->body['name'];
            $contacts = $db->query('SELECT * FROM contacts WHERE name = "'.$name.'"')->fetchAll();
        }
        return $contacts;
    }

    public function update()
    {
        global $db;
        if(isset($this->body['name']))
        {
            $name = $this->body['name'];
            $contact_id = $this->body['contact_id'];
            $update = $db->query('UPDATE contacts SET contacts.name = "'.$name.'" WHERE contacts.contact_id = "'.$contact_id.'"');
        }

        if(isset($this->body['surname']))
        {
            $surname = $this->body['surname'];
            $contact_id = $this->body['contact_id'];
            $update = $db->query('UPDATE contacts SET contacts.surname = "'.$surname.'" WHERE contacts.contact_id = "'.$contact_id.'"');
        }
        
        if(isset($this->body['image']))
        {
            $image = $this->body['image'];
            $contact_id = $this->body['contact_id'];
            $update = $db->query('UPDATE contacts SET contacts.image = "'.$image.'" WHERE contacts.contact_id = "'.$contact_id.'"');
        }

        return $update->affectedRows();
    }

    public function delete()
    {
        global $db;
        $contact_id = $this->body['contact_id'];
        $contacts = $db->query('DELETE FROM contacts WHERE contact_id = '.intval($contact_id).'');
        return $contacts->affectedRows();
    }
}
