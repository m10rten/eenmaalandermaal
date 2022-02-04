<?php
// vernietigd de sessie en stuurt de gebruiker naar de homepagina

session_start();
unset($_SESSION);
session_destroy();

header('Location: ../index.php?pop=logout');
exit();
