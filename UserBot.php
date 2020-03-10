<?php
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
function createAccount(){
    $conn = new mysqli("localhost", "root", "","agencewebquebec");
    $sqlSelect="Select * from informations";
    $res=$conn->query($sqlSelect) or die($conn->error);

    while($rec=$res->fetch_array()){

        $email=$rec['email'];
        $email=$rec['email'];
        $information_id=$rec['Id'];
        $name=$rec['Titre'];
        $pass=randomPassword();

        $password=password_hash($pass,PASSWORD_DEFAULT);
        $date=date("Y-m-d");

        $sqlInsert="Insert into users(name,email,password,Created_at,InformationId) values('$email','$email','$password','$date','$information_id')";
        $conn->query($sqlInsert)or die($conn->error);

    }

}

createAccount();
