<?php

use Classes\Program;

require_once './Classes/Program.php';

(new Program(fopen("php://stdin", "r")))->init();

