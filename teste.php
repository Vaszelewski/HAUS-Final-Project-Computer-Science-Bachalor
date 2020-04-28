<?php

require_once('funcoes/bancoDeDados.php');

print_r((bd_consulta("SELECT imagem FROM usuario WHERE email LIKE 'ariel.fc.silva@gmail.com'")));