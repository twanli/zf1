<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $message = "This is the Home Page";
        $this->view->message = $message;        
    }

    public function contactAction()
    {
        $request = $this->getRequest();
        $msg     = $request->getParam('msg');
        $form    = new Application_Form_Contact();
 
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                ini_set('max_execution_time', 300);
                $config = new Zend_Config_Ini(APPLICATION_PATH . 
                    '/configs/config.ini', 'mail');
                $username = $config->username;
                $pass = $config->password;
                $name    = $form->getValue('name');
                $email   = $form->getValue('email');
                $message = $form->getValue('message');
                
                $tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com',
                    array('ssl' => 'tls',
                    'auth' => 'login',
                             'username' => $config->username,
                             'password' => $config->password,
                             'port' => 587));
                Zend_Mail::setDefaultTransport($tr);
                                
                try{
                    $mail = new Zend_Mail();
                    $mail->setBodyText($message);
                    $mail->setFrom($email, 'Some Sender');
                    $mail->addTo('tomas.wanli@gmail.com', 'Some Recipient');
                    $mail->setSubject('Contact Message from customer');
                    $mail->send();
                    return $this->_helper->redirector('contact',
                                                      'index',
                                                      'default',
                                                      array('msg' => 1)  
                                                     );                    

                } catch(\Exception $e) {
                    return $this->_helper->redirector('contact',
                                                      'index',
                                                      'default',
                                                      array('msg' => 0)  
                                                     );                    
                    }
                }
        }
 
        $this->view->form = $form;
        $this->view->sent = $msg;
    }

}

