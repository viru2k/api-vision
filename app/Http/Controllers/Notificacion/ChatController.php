<?php

namespace App\Http\Controllers\Notificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function encriptarTexto(Request $request)
    {
        $texto =$request->input('texto');  
        $output =$this->encrypt_decrypt('encrypt',$texto);
        return response()->json($output, 201);
    }

    public function desEncriptarTexto(Request $request)
    {
        $texto =$request->input('texto');  
        $output =$this->encrypt_decrypt('decrypt',$texto);
        return response()->json($output, 201);
    }

    /********* ENCRIPTAR - DESENCRIPTAR */
    public function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
       // echo $output;
        return $output;
    }
   /* $plain_txt = "This is my plain text";
    echo "Plain Text =" .$plain_txt. "\n";
    $encrypted_txt = encrypt_decrypt('encrypt', $plain_txt);
    echo "Encrypted Text = " .$encrypted_txt. "\n";
    $decrypted_txt = encrypt_decrypt('decrypt', $encrypted_txt);
    echo "Decrypted Text =" .$decrypted_txt. "\n";
    if ( $plain_txt === $decrypted_txt ) echo "SUCCESS";
    else echo "FAILED";*/
}
