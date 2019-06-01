<?php

class Controller
{
    public $body;

    public function __construct($body)
    {
        $this->body = $body;
    }

    public function create($object, $value)
    {
        if($object=='contact')
        {
            $result = array('result'=>'success', 'message'=>'Correctly created');
            $contact = new Contact($this->body);
            $last_inserted_contact = $contact->insert();
            if(is_int($last_inserted_contact))
            {
                $email_body = array(
                    'contact_id'=>$last_inserted_contact,
                    'email'=>$this->body['email']
                );
                $email = new Email($email_body);
                $email_inserted = $email->insert();

                $phone_body = array(
                    'contact_id'=>$last_inserted_contact,
                    'phone'=>$this->body['phone']
                );
                $phone = new Phone($phone_body);
                $phone_inserted = $phone->insert();

                if(!is_int($email_inserted) || !is_int($phone_inserted))
                {
                    $result = array('result'=>'error', 'message'=>'There was a problem inserting phone or email.');
                }
                return $result;
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Contact was not created.');
            }
        }
        else if ($object=='phone')
        {
            $result = array('result'=>'success', 'message'=>'Correctly created');
            if(isset($this->body['contact_id']))
            {
                $contact_exist = new Contact($this->body);
                $select = $contact_exist->read();
                if(isset($select[0]['contact_id']))
                {
                    $phone_body = array(
                        'contact_id'=>$this->body['contact_id'],
                        'phone'=>$this->body['phone']
                    );
                    $phone = new Phone($phone_body);
                    $inserted_phone = $phone->insert();
                    if(is_int($inserted_phone))
                    {
                        $result = array('result'=>'success', 'message'=>'Correctly created', 'phone'=>$inserted_phone);
                    }
                    else
                    {
                        $result = array('result'=>'error', 'message'=>'Phone was not created.');
                    }
                }
                else
                {
                    $result = array('result'=>'error', 'message'=>'Can not found contact. See if you have the correct contact_id');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'You have to give us the contact id that will be related with this phone number.');
            }
        }
        else if($object=='email')
        {
            $result = array('result'=>'success', 'message'=>'Correctly created');
            if(isset($this->body['contact_id']))
            {
                $contact_exist = new Contact($this->body);
                $select = $contact_exist->read();
                if(isset($select[0]['contact_id']))
                {
                    $email_body = array(
                        'contact_id'=>$this->body['contact_id'],
                        'email'=>$this->body['email']
                    );
                    $email = new Email($email_body);
                    $inserted_email = $email->insert();
                    if(is_int($inserted_email))
                    {
                        $result = array('result'=>'success', 'message'=>'Correctly created', 'email_id'=>$inserted_email);
                    }
                    else
                    {
                        $result = array('result'=>'error', 'message'=>'Phone was not created.');
                    }
                }
                else
                {
                    $result = array('result'=>'error', 'message'=>'Can not found contact. See if you have the correct contact_id');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'You have to give us the contact id that will be related with this phone number.');
            }
        }
        else
        {
            $result = array('result'=>'error', 'message'=> $object.' object does not exists');
        }
        return $result;
    }

    public function read($object, $value)
    {
        $result = array('result'=>'success', 'contact'=>array());
        if($object=='contact')
        {
            if($this->is_id($value))
            {
                $this->body['contact_id'] = $value;
                $contact = new Contact($this->body);
                $selected = $contact->read();
                if(isset($selected[0]['contact_id']))
                {
                    foreach($selected as $value)
                    {
                        $contact = array(
                            'contact_id'=>$value['contact_id'],
                            'name'=>$value['name'],
                            'surname'=>$value['surname'],
                            'image'=>$value['image'],
                            'phones'=>array(),
                            'emails'=>array(),
                        );

                        $phones = new Phone($contact);
                        $selected_phones = $phones->read();
                        foreach($selected_phones as $value)
                        {
                            array_push($contact['phones'], array('phone_id'=>$value['phone_id'], 'phone'=>$value['phone']));
                        }

                        $emails = new Email($contact);
                        $selected_emails = $emails->read();
                        foreach($selected_emails as $value)
                        {
                            array_push($contact['emails'], array('email_id'=>$value['email_id'], 'email'=>$value['email']));
                        }

                        array_push($result['contact'], $contact);
                    }
                    
                }
                else
                {
                    $result = array('result'=>'error', 'message'=> 'Contact with id '.$this->body['contact_id'].' was not found.');
                }   
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Value is not an id');
            }
        }
        else if($object=='phone')
        {
            if($this->is_phone($value))
            {
                $this->body['phone'] = $value;
                $phone = new Phone($this->body);
                $selected_phones = $phone->read();
                if(isset($selected_phones[0]['contact_id']))
                {
                    foreach($selected_phones as $value)
                    {
                        $contact = new Contact($value);
                        $selected_contact = $contact->read();
                        if(isset($selected_contact[0]['contact_id']))
                        {
                            $result_contact = array(
                                'contact_id'=>$selected_contact[0]['contact_id'],
                                'name'=>$selected_contact[0]['name'],
                                'surname'=>$selected_contact[0]['surname'],
                                'image'=>$selected_contact[0]['image'],
                                'phones'=>array(),
                                'emails'=>array(),
                            );
                            
                            $phones = new Phone($result_contact);
                            $selected_phones = $phones->read();
                            foreach($selected_phones as $phone)
                            {
                                array_push($result_contact['phones'], array('phone_id'=>$phone['phone_id'], 'phone'=>$phone['phone']));
                            }

                            $emails = new Email($result_contact);
                            $selected_emails = $emails->read();
                            foreach($selected_emails as $email)
                            {
                                array_push($result_contact['emails'], array('email_id'=>$email['email_id'], 'email'=>$email['email']));
                            }

                            array_push($result['contact'], $result_contact);
                        }
                    }
                }
                else
                {
                    $result = array('result'=>'error', 'message'=> 'Contacts with phone '.$this->body['phone'].' was not found.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Value is not a ten digits phone');
            }
        }
        else if($object=='email')
        {
            if($this->is_email($value))
            {
                $this->body['email'] = $value;
                $email = new Email($this->body);
                $selected_emails = $email->read();
                if(isset($selected_emails[0]['contact_id']))
                {
                    foreach($selected_emails as $value)
                    {
                        $contact = new Contact($value);
                        $selected_contact = $contact->read();
                        if(isset($selected_contact[0]['contact_id']))
                        {
                            $result_contact = array(
                                'contact_id'=>$selected_contact[0]['contact_id'],
                                'name'=>$selected_contact[0]['name'],
                                'surname'=>$selected_contact[0]['surname'],
                                'image'=>$selected_contact[0]['image'],
                                'phones'=>array(),
                                'emails'=>array(),
                            );
                            
                            $phones = new Phone($result_contact);
                            $selected_phones = $phones->read();
                            foreach($selected_phones as $phone)
                            {
                                array_push($result_contact['phones'], array('phone_id'=>$phone['phone_id'], 'phone'=>$phone['phone']));
                            }

                            $emails = new Email($result_contact);
                            $selected_emails = $emails->read();
                            foreach($selected_emails as $email)
                            {
                                array_push($result_contact['emails'], array('email_id'=>$email['email_id'], 'email'=>$email['email']));
                            }

                            array_push($result['contact'], $result_contact);
                        }
                    }
                }
                else
                {
                    $result = array('result'=>'error', 'message'=> 'Contacts with email '.$this->body['email'].' was not found.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Value is not an email');
            }
        }
        else if($object=='name')
        {
            $this->body['name'] = $value;
            $contact = new Contact($this->body);
            $selected = $contact->read();
            if(isset($selected[0]['contact_id']))
            {
                foreach($selected as $value)
                {
                    $contact = array(
                        'contact_id'=>$value['contact_id'],
                        'name'=>$value['name'],
                        'surname'=>$value['surname'],
                        'image'=>$value['image'],
                        'phones'=>array(),
                        'emails'=>array(),
                    );

                    $phones = new Phone($contact);
                    $selected_phones = $phones->read();
                    foreach($selected_phones as $value)
                    {
                        array_push($contact['phones'], array('phone_id'=>$value['phone_id'], 'phone'=>$value['phone']));
                    }

                    $emails = new Email($contact);
                    $selected_emails = $emails->read();
                    foreach($selected_emails as $value)
                    {
                        array_push($contact['emails'], array('email_id'=>$value['email_id'], 'email'=>$value['email']));
                    }

                    array_push($result['contact'], $contact);
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=> 'Contact with surname '.$this->body['contact_id'].' was not found.');
            }
        }
        else if($object=='surname')
        {
            $this->body['surname'] = $value;
            $contact = new Contact($this->body);
            $selected = $contact->read();
            if(isset($selected[0]['contact_id']))
            {
                foreach($selected as $value)
                {
                    $contact = array(
                        'contact_id'=>$value['contact_id'],
                        'name'=>$value['name'],
                        'surname'=>$value['surname'],
                        'image'=>$value['image'],
                        'phones'=>array(),
                        'emails'=>array(),
                    );

                    $phones = new Phone($contact);
                    $selected_phones = $phones->read();
                    foreach($selected_phones as $value)
                    {
                        array_push($contact['phones'], array('phone_id'=>$value['phone_id'], 'phone'=>$value['phone']));
                    }

                    $emails = new Email($contact);
                    $selected_emails = $emails->read();
                    foreach($selected_emails as $value)
                    {
                        array_push($contact['emails'], array('email_id'=>$value['email_id'], 'email'=>$value['email']));
                    }

                    array_push($result['contact'], $contact);
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=> 'Contact with surname '.$this->body['contact_id'].' was not found.');
            }   
        }
        else
        {
            $result = array('result'=>'error', 'message'=> $object.' object does not exists');
        }
        return $result;
    }

    public function update($object, $value)
    {
        $result = array('result'=>'success', 'message'=>'correctly updated');
        if($object=='contact')
        {
            $contact = new Contact($this->body);
            $updated_id = $contact->update();
            if(is_int($updated_id))
            {
                if($updated_id==0)
                {
                    $result = array('result'=>'error', 'message'=>'can not update because it does not exist.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Contact was not updated.');
            }
            return $result;
        }
        else if($object=='phone')
        {
            $phone = new Phone($this->body);
            $updated_id = $phone->update();
            if(is_int($updated_id))
            {
                if($updated_id==0)
                {
                    $result = array('result'=>'error', 'message'=>'can not update.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Phone was not updated.');
            }
            return $result;
        }
        else if($object=='email')
        {
            $email = new Email($this->body);
            $updated_id = $email->update();
            if(is_int($updated_id))
            {
                if($deleted_phone==0)
                {
                    $result = array('result'=>'error', 'message'=>'can not update.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Phone was not updated.');
            }
            return $result;
        }
    }

    public function delete($object, $value)
    {
        $result = array('result'=>'success', 'message'=>'Correctly deleted');
        if($object=='contact')
        {
            $contact = new Contact($this->body);
            $deleted_id = $contact->delete();
            if(is_int($deleted_id))
            {
                if($deleted_phone==0)
                {
                    $result = array('result'=>'error', 'message'=>'can not delete because it does not exist.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'Contact not deleted.');
            }
            return $result;
        }
        else if($object=='phone')
        {
            $phone = new Phone($this->body);
            $deleted_phone = $phone->delete();
            
            if(is_int($deleted_phone))
            {
                if($deleted_phone==0)
                {
                    $result = array('result'=>'error', 'message'=>'can not delete because it does not exist.');
                }
            }
            else
            {
                $result = array('result'=>'error', 'message'=>'phone not deleted.');
            }
            return $result;
        }
        else if($object=='email')
        {
            $email = new Email($this->body);
            $deleted_email = $email->delete();
            if(!is_int($deleted_email))
            {
                if($deleted_email==0)
                {
                    $result = array('result'=>'error', 'message'=>'email not deleted.');
                }
            }
            return $result;
        }
        else
        {
            $result = array('result'=>'error', 'message'=> $object.' object does not exists');
        }
        
        return $result;
    }

    public function upload()
    {
        $contact = new Contact($this->body);
        $contact_exist = $contact->read();
        if(isset($contact_exist[0]['contact_id']))
        {
            $images_path = './uploads/';
            $files = $this->body;
            if(is_uploaded_file($files['file']['image']['tmp_name']))
            {
                $sent_file = $files['file']['image']['tmp_name'];
                $sent_file_name = $files['file']['image']['name'];
                $sent_file_path = $images_path.$sent_file_name;
                if(move_uploaded_file($sent_file, $sent_file_path))
                {
                    $result = array('result'=>'success', 'message'=>'image correctly uploaded');
                    $body = array('contact_id'=>$this->body['contact_id'], 'image'=>$sent_file_name);
                    $contact = new Contact($body);
                    $uploaded_image = $contact->update();
                }
                else
                {
                    $result = array('result'=>'error', 'message'=>'can not upload the file.');
                }
            }
        }
        else
        {
            $result = array('result'=>'error', 'message'=> 'Contact with contact_id '.$this->body['contact_id'].' was not found.');
        }
        
        return $result;
    }
    
    private function is_email($email) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
    }

    private function is_phone($phone) {
        return (!preg_match( "/^\+?[0-9]+$/", "+12312312312", $phone)) ? FALSE : TRUE;
    }

    private function is_id($id)
    {
        return ctype_digit($id);
    }
}