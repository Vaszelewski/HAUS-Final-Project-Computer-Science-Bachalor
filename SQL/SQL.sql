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
	haus.suporte(
		cod_suporte INT PRIMARY KEY AUTO_INCREMENT,
		cod_opcao TINYINT(1) NOT NULL,
		email VARCHAR(100) NOT NULL,
		assunto VARCHAR(100) NOT NULL,
		mensagem VARCHAR(255) NOT NULL
	);

CREATE TABLE IF NOT EXISTS
	haus.opcoes_suporte(
		cod_opcao INT PRIMARY KEY,
		texto VARCHAR(100) NOT NULL
	);

INSERT INTO 
	haus.opcoes_suporte(cod_opcao, texto)
VALUES
	("1", "Spam ou Conteúdo Impróprio"),
	("2", "Algo não está funcionando corretamente"),
	("3", "Feedback Geral"),
	("4", "Problema com Qualidade de Imagem"),
	("5", "Não encontrou uma opção? Descreva abaixo detalhadamente");

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