<?php
/**
 * Created by PhpStorm.
 * User: pavan.mohan
 * Date: 2019-03-22
 * Time: 16:10
 */
session_start();
session_unset();
session_destroy();
header('Location: index.php');