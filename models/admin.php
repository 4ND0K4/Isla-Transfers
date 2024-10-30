<?php
class admin {
    private $id = "94556257"; //ID de usuario a introducir
    private $key = "iJNpF5RU"; // clave a introducir

    public function verifyLoginAdmin($inputID, $inputKey) {
        return $inputID === $this->id && $inputKey === $this->key;
    }
}