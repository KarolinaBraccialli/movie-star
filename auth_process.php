<?php

require_once("globals.php");
require_once("db.php");
require_once("dao/UserDAO.php");
require_once("models/User.php");
require_once("models/Message.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);

//Resgata o tipo de formulário
$type = filter_input(INPUT_POST, "type");

//Verifica o tipo de fomulario
if($type === "register"){

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    //Verifica se os dados mínimos forão preenchidos
    if($name && $lastname && $email && $password){
        //verifica se as senhas batem
        if($password === $confirmpassword){
            //verifica se o email já existe
            if($userDao->findByEmail($email) == false){
              $user = new User();

              //criar senha e token
              $userToken = $user->generateToken();
              $finalPassword = $user->generatePassword($password);

                //setar dados do usuário
                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;

                $auth = true;

                $userDao->create($user, $auth);

            }else{
                $message->setMessage("E-mail já cadastrado", "error", "back");
            }
        } else{
        //Envia msg de erro, senhas diferentes
        $message->setMessage("As senhas devem ser iguais", "error", "back");
        }

    } else{
        //Envia msg de erro, dados faltantes
        $message->setMessage("Por favor, preencha todos os dados!", "error", "back");
    }

}