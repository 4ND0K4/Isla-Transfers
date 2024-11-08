<?php
class admin {
    private $id = "94556257"; //ID de usuario a introducir
    private $key = "pass"; // clave a introducir iJNpF5RU

    public function verifyLoginAdmin($inputID, $inputKey) {
        return $inputID === $this->id && $inputKey === $this->key;
    }
}
