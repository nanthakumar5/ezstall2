<?php

namespace App\Controllers\Api\Register;

use App\Controllers\BaseController;

use App\Models\Users;

class Index extends BaseController
{
    public function __construct()
    {
        $this->users = new Users();
    }

    public function action()
    {
        $post       = $this->request->getPost();
        $validation = \Config\Services::validation();
        $encrypter = \Config\Services::encrypter();

        $validation->setRules(
            [
                'name'          => 'required',
                'email'         => 'required|valid_email',
                'password'      => 'required',
                'type'          => 'required',
            ],

            [
                'name' => [
                    'required' => 'User name is required.',
                ],
                'email'        => [
                    'required' => 'Email is required.',
                ],
                'password'     => [
                    'required' => 'Password is required.',
                ],
                'type'     => [
                    'required' => 'Type is required.',
                ],


            ]
        );

        if ($validation->withRequest($this->request)->run()) {

            $check_email = $this->users->getUsers('count', ['users'], ['email' => $post['email']]); 

            if ($check_email) {
                $json = ['0', 'Email id already Exists.', []];
            } else {
                $post['status'] = '1';
                $post['email_status'] = '0';

                $action = $this->users->action($post);

                if ($action) {

                    $encryptid = $encrypter->encrypt($action);

                    $verificationurl = base_url() . "/api/verification/" . $encryptid;
                    $email_subject = "Ezstall Registration";
                    $email_message = "Hi " . $post['name'] . "," . " \n\n Thank you for Registering in Ezstall.
                        \n To activate your account please click below link." . ' ' . $verificationurl . "";

                    $this->send_mail($post['email'], $email_subject, $email_message);
                    $json = ['1', 'User Submitted Successfully.', []];
                } else {
                    $json = ['0', 'Try Later.', []];
                }
            }
        } else {
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
            'result'         => $json[2],
        ]);

        die;
    }



    public function verification($id)
    {
        $encrypter = \Config\Services::encrypter();
        $decryptid = $encrypter->decrypt($id);

        if ($id != '') {
            $post['actionid'] = $decryptid;
            $post['email_status'] = 1;

            $updateaction = $this->users->action($post);
            if ($updateaction) {
                return redirect()->to('https://ezstall.itfhrm.com/Thank');
            }
        }
    }

    public function send_mail($to, $subject, $message)
    {
        $email = \Config\Services::email();

        $config['protocol'] = 'sendmail';
        $config['mailPath'] = '/usr/sbin/sendmail';
        $config['charset']  = 'iso-8859-1';
        $config['wordWrap'] = true;

        //$email->initialize($config);

        //$email->setFrom('muthulakshmi@itflexsolutions.com', 'Ezstall');
		$email->setFrom('no-reply@ezstall.com', 'Ezstall');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);

        /*if ($email->send()) {
            //echo "sent";
			return true;
        } else {
            //echo "not sent";
			return false;
        }*/
		
		if($email->send()){
        return "sent";
    }else{
       	print_r($email->printDebugger());exit;
        return "not sent";
    }

        //die;
    }
}
