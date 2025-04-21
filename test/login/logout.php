<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php"); // Change this if your login page has a different name
exit();
