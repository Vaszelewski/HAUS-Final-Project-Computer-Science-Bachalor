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

CREATE TABLE IF NOT EXISTS
	haus.categoria(
		cod_categoria INT PRIMARY KEY AUTO_INCREMENT,
		nome VARCHAR(100)
	)

CREATE TABLE IF NOT EXISTS
	haus.colecao(
		cod_colecao INT PRIMARY KEY AUTO_INCREMENT,
		nome VARCHAR(100),
		descricao VARCHAR(255) NULL,
		cod_categoria INT,
		privacidade TEXT,
		tipo_mime VARCHAR(50) NULL DEFAULT NULL,
		imagem LONGBLOB NULL DEFAULT NULL,

		FOREIGN KEY (cod_categoria) REFERENCES categoria(cod_categoria)
	);

CREATE TABLE IF NOT EXISTS
	haus.rel_usuarioxcolecao(
		cod_usuario INT,
		cod_colecao INT,
		dono INT,
		moderador INT,

		FOREIGN KEY (cod_usuario) REFERENCES usuario(cod_usuario),
		FOREIGN KEY (cod_colecao) REFERENCES colecao(cod_colecao)
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
	public_haus@%;