CREATE DATABASE theUsuario CHARACTER SET utf8 COLLATE utf8_general_ci;

/* INSERIR NOVO SERVIÇO A TODOS OS USUÁRIOS */
insert into controleAcesso (idUsuario, pagina, visualizar, incluir, editar, excluir)
(select idUsuario, /*nome pagina*/, 0, 0, 0, 0 from usuario group by idUsuario);

CREATE TABLE usuario (
  idUsuario INT AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL,
  login VARCHAR(30) NOT NULL UNIQUE,
  senha VARCHAR(32) NOT NULL,
  PRIMARY KEY (idUsuario)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

CREATE TABLE controleAcesso (
  idUsuario INT,
  pagina VARCHAR(30) NOT NULL,
  visualizar BOOLEAN NOT NULL,
  incluir BOOLEAN NOT NULL,
  editar BOOLEAN NOT NULL,
  excluir BOOLEAN NOT NULL,
  FOREIGN KEY (idUsuario) REFERENCES usuario (idUsuario)
);