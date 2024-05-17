<?php
/**
 * Created by PhpStorm.
 * User: pavanw3b
 * Date: 22-03-2019
 * Time: 00:18
 */

function isAuthenticated() {
   return isset($_SESSION['authenticated']);
    
}

