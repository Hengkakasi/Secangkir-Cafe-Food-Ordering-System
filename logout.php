<?php

include('connection/connect.php');

session_start();
session_unset();
session_destroy();

header("Location: animation/coffeeFilling.php?final=page1");  //change to index.php

?>