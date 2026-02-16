<?php
namespace App\Helpers;

class Helpers
{
    public function UserIdCondominio()
    {
        $userAuth = auth()->user();
        // Obtenemos el id del condominio del usuario para por defecto que se esta agregando un copropietario en ese condominio.
        $userCondominio = $userAuth->id_condominio;
        return $userCondominio;
    }
    
}
