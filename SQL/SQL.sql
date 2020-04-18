CREATE DATABASE IF NOT EXISTS haus;

CREATE TABLE IF NOT EXISTS
	haus.usuario(
		id INT PRIMARY KEY,
		nome VARCHAR(50),
		sobrenome VARCHAR(100),
		email VARCHAR(50),
		senha TEXT
	);

GRANT SELECT
ON
	haus.*
TO
	public_haus IDENTIFIED BY 'Teste1234567890';

GRANT INSERT
ON
	haus.usuario
TO
	public_haus;