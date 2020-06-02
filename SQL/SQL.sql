CREATE DATABASE IF NOT EXISTS haus;

CREATE TABLE IF NOT EXISTS
	haus.usuario(
		cod_usuario INT PRIMARY KEY AUTO_INCREMENT,
		nome VARCHAR(50),
		sobrenome VARCHAR(100),
		display_name VARCHAR(50) NULL,
		email VARCHAR(50),
		descricao VARCHAR(255) NULL,
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

GRANT SELECT, INSERT, UPDATE
ON 
	haus.usuario
TO
	public_haus;

CREATE TABLE IF NOT EXISTS
	haus.altera_senha(
  		email varchar(250) NOT NULL,
  		chave varchar(250) NOT NULL,
  		data_expiracao datetime NOT NULL
)