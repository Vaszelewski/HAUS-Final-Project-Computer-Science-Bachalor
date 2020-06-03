<?php

require_once('funcoes/bancoDeDados.php');
require_once('funcoes/usuario.php');

$sql = "select nome from categoria where nome = 'teste'";
print_r(bd_consulta($sql));

