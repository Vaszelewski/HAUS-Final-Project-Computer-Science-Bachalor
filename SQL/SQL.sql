CREATE DATABASE IF NOT EXISTS haus;

CREATE TABLE IF NOT EXISTS
	haus.usuario(
		cod_usuario INT PRIMARY KEY,
		nome VARCHAR(50),
		sobrenome VARCHAR(100),
		email VARCHAR(50),
		senha TEXT,
		tipo_mime VARCHAR(50) NULL DEFAULT NULL,
		imagem MEDIUMBLOB NULL DEFAULT NULL
	);

DROP USER public_haus;

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