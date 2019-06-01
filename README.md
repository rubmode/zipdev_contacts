# zipdev_contacts
Test for PHP developer position

# Description
Web API Rest to manage a contact book that save people contact information (email, phone, name, surname and photo).

# File tree
- Database
install.sql (Script that install mysql tables)
dump.sql (Script that install sample data)
- contacts (Web App)

# Web API Endpoints

# /CREATE

{path}/?do=create&object=contact

Method: POST

Params 

- object: contact, phone, email
- do: create

- Body: form-data: {"name":"John", "surname":"Smith", "email":"name@domain.com", "phone":"3312141618"}.
- Body: form-data: {"contact_id":"1", "phone":"3312141618"}.
- Body: form-data: {"contact_id":"1", "email":"name@domain.com"}.
  
# /READ

Method: GET

{path}/?do=read&object=phone&value=3312141618

Params

- do: read
- object: contact, phone, email, surname, name
- value: contact_id, phone_id, email_id, surname, name
  
# /UPDATE

Method: PUT

{path}/?do=update&object=contact

Params
- do: update
- object: contact, phone, email, surname, name

- Body: x-www-form-urlencoded: {"contact_id":"1", "surname":"Scott"}
- Body: x-www-form-urlencoded: {"contact_id":"1", "phone":"3316181952"}
- Body: x-www-form-urlencoded: {"contact_id":"1", "name":"Steve"}
- Body: x-www-form-urlencoded: {"contact_id":"1", "email":"new@domain.com"}
  
# /DELETE

Method: DELETE

{path}/?do=delete&object=email

Params

- do: delete
- object: contact, phone, email
  
Body

- Body: x-www-form-urlencoded: {"contact_id":"1"}
- Body: x-www-form-urlencoded: {"email_id":"1"}
- Body: x-www-form-urlencoded: {"phone_id":"1"}

# /POST IMAGE

{path}/?do=upload&object=image

Params 

- object: image
- do: upload

- Body: form-data: {"image":"Image sent via file form field", "contact_id":"1"}

# NOTES
The Web API that i've created try to accomplish the tasks that was sent to me. Please, consider that i had so little time to get this done because of my current activities.
