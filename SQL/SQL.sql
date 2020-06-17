CREATE DATABASE IF NOT EXISTS haus;

CREATE TABLE IF NOT EXISTS
	haus.usuario(
		cod_usuario INT PRIMARY KEY AUTO_INCREMENT,
		nome VARCHAR(50) NOT NULL,
		sobrenome VARCHAR(100) NOT NULL,
		display_name VARCHAR(50) NULL,
		email VARCHAR(50) NOT NULL,
		descricao VARCHAR(255) NULL,
		senha TEXT NOT NULL,
		tipo_mime VARCHAR(50) NULL DEFAULT NULL,
		imagem MEDIUMBLOB NULL DEFAULT NULL
	);

CREATE TABLE IF NOT EXISTS
	haus.opcoes_suporte(
		cod_opcao INT PRIMARY KEY,
		texto VARCHAR(100) NOT NULL
	);

CREATE TABLE IF NOT EXISTS
	haus.suporte(
		cod_suporte INT PRIMARY KEY AUTO_INCREMENT,
		cod_opcao INT NOT NULL,
		cod_usuario INT NOT NULL,
		email VARCHAR(100) NOT NULL,
		assunto VARCHAR(100) NOT NULL,
		mensagem VARCHAR(255) NOT NULL,

		FOREIGN KEY (cod_opcao) REFERENCES opcoes_suporte(cod_opcao),
		FOREIGN KEY (cod_usuario) REFERENCES usuario(cod_usuario)
	);

REPLACE INTO
	haus.opcoes_suporte(cod_opcao, texto)
VALUES
	("1", "Spam ou Conteúdo Impróprio"),
	("2", "Algo não está funcionando corretamente"),
	("3", "Feedback Geral"),
	("4", "Problema com Qualidade de Imagem"),
	("5", "Não encontrou uma opção? Descreva abaixo detalhadamente");

CREATE TABLE IF NOT EXISTS
	haus.categoria(
		cod_categoria INT PRIMARY KEY AUTO_INCREMENT,
		nome VARCHAR(100) NOT NULL,
		qtd_visualizacao int(11) NOT NULL DEFAULT 0
	);
CREATE TABLE IF NOT EXISTS
	haus.altera_senha(
  		email varchar(50) NOT NULL,
  		chave varchar(250) NOT NULL,
  		data_expiracao datetime NOT NULL
);

CREATE TABLE IF NOT EXISTS
	haus.colecao(
		cod_colecao INT PRIMARY KEY AUTO_INCREMENT,
		titulo VARCHAR(100) NOT NULL,
		descricao VARCHAR(255) NULL,
		cod_categoria INT NOT NULL,
		qtd_visualizacao INT(11) NOT NULL DEFAULT 0,
		tipo_mime VARCHAR(50) NULL DEFAULT NULL,
		imagem LONGBLOB NULL DEFAULT NULL,

		FOREIGN KEY (cod_categoria) REFERENCES categoria(cod_categoria) ON UPDATE NO ACTION ON DELETE NO ACTION
	);

CREATE TABLE IF NOT EXISTS
	haus.rel_usuarioxcolecao(
		cod_usuario INT NOT NULL,
		cod_colecao INT NOT NULL,
		dono INT NOT NULL,
		moderador INT DEFAULT NULL,

		FOREIGN KEY (cod_usuario) REFERENCES usuario(cod_usuario) ON UPDATE NO ACTION ON DELETE CASCADE,
		FOREIGN KEY (cod_colecao) REFERENCES colecao(cod_colecao) ON UPDATE NO ACTION ON DELETE CASCADE
	);

CREATE TABLE IF NOT EXISTS
	haus.item(
		cod_item INT PRIMARY KEY AUTO_INCREMENT,
		titulo VARCHAR(100) NOT NULL,
		descricao VARCHAR(255) NULL,
		cod_colecao INT NOT NULL,
		tipo_mime VARCHAR(50) NULL DEFAULT NULL,
		imagem LONGBLOB NULL DEFAULT NULL,

		FOREIGN KEY (cod_colecao) REFERENCES colecao(cod_colecao) ON UPDATE NO ACTION ON DELETE CASCADE
	);

DROP USER public_haus;
CREATE USER public_haus;

GRANT SELECT, INSERT, UPDATE, DELETE ON haus.* TO 'public_haus'@'%'
IDENTIFIED BY 'Teste1234567890';
