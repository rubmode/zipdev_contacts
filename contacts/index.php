<?php
/**
 *  API Rest: Contacts
 *  @author: ruben.loguz@gmail.com
 */

global $db;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('database/database.php');
require_once('models/contact.php');
require_once('models/phone.php');
require_once('models/email.php');
require_once('controllers/controller.php');

try 
{
    $body   = null;
    $object = null;
    $value  = null;
    $db     = new Database('127.0.0.1','root','Passw0rd','zipdev_contacts');

    if (isset($_SERVER['REQUEST_METHOD']))
    {
        if(isset($_GET['do']) && isset($_GET['object']))
        {
            $action = $_GET['do'];
            $object = $_GET['object'];

            switch ($_SERVER['REQUEST_METHOD'])
            {
                case 'POST':
                    $body = $_POST;
                    if(isset($_FILES['image']))
                    {
                        $body['file'] = $_FILES;
                    }
                    break;
                case 'GET':
                    $value = $_GET['value'];
                    break;
                case 'PUT':
                    parse_str(file_get_contents("php://input"),$body);
                    break;
                case 'DELETE':
                    parse_str(file_get_contents("php://input"),$body);
                    break;
            }

            $ctrl = new Controller($body);
            $result = $ctrl->$action($object, $value);

            print json_encode($result);
        }
        else
        {
            print json_encode(array('ZipDev Contact API Test'=>'Running'));
        }
    }
} 
catch(Exception $e) 
{
    print json_encode(array('error'=>$e->getMessage()));
}