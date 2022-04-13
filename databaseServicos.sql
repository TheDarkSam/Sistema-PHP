/* FUNCIONANDO */

CREATE DATABASE theServico CHARACTER SET utf8mb4 COLLATE utf8_unicode_ci;

/* Cliente */

CREATE TABLE cliente (
  idCliente INT AUTO_INCREMENT,
  nomeEmpresa VARCHAR(100) NOT NULL,
  nomeResponsavel VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  PRIMARY KEY (idCliente)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE contato (
  idCliente INT NOT NULL,
  telefone VARCHAR(11) NOT NULL,
  tipo enum('Fixo', 'Celular', 'Whatsapp') NOT NULL,
  FOREIGN KEY (idCliente) REFERENCES cliente(idCliente)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

/* ./ Cliente */

/* Orçamento */
CREATE TABLE orcamento (
  idOrcamento INT AUTO_INCREMENT,
  idCliente INT NOT NULL,
  departamentos INT NOT NULL,
  compartilhamentos INT NOT NULL,
  funcInternos INT NOT NULL,
  funcExternos INT NOT NULL,
  dispositivos INT NOT NULL,
  sistemas INT NOT NULL,
  tipoDados enum('pessoais', 'sensiveis') NOT NULL,
  ti enum('sim', 'nao') NOT NULL,
  juridico enum('sim', 'nao') NOT NULL,
  politica enum('sim', 'nao') NOT NULL,
  ferramentas enum('alto', 'medio', 'baixo') NOT NULL,
  observacoes enum('sim', 'nao') NOT NULL,
  valorHora DECIMAL(9, 2) NOT NULL,
  situacao enum("Aprovado", "Pendente", "Reprovado") NOT NULL,
  dataCriado DATE NOT NULL,
  dataValidado DATE,
  FOREIGN KEY (idCliente) REFERENCES cliente(idCliente),
  PRIMARY KEY (idOrcamento)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE lgpd (
  idOrcamento INT NOT NULL,
  coleta INT NOT NULL,
  gap INT NOT NULL,
  planoAcao INT NOT NULL,
  implantacao INT NOT NULL,
  relatorio INT NOT NULL,
  treinamento INT NOT NULL,
  dpo INT NOT NULL,
  validacao INT NOT NULL,
  melhorias INT NOT NULL,
  total INT NOT NULL,
  FOREIGN KEY (idOrcamento) REFERENCES orcamento(idOrcamento)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE pagamento (
  idPagamento INT AUTO_INCREMENT,
  idOrcamento INT NOT NULL,
  totalOrcamento DECIMAL(9, 2) NOT NULL,
  valorEntrada DECIMAL(9, 2) NOT NULL,
  totParcelas INT NOT NULL,
  PRIMARY KEY(idPagamento),
  FOREIGN KEY (idOrcamento) REFERENCES orcamento(idOrcamento)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

/* ./ Orçamento */

/* Documentação */

CREATE TABLE areaDoc (
  idArea INT AUTO_INCREMENT,
  descArea VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (idArea)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE documentacao (
  idDocumentacao INT AUTO_INCREMENT,
  titulo VARCHAR(40) NOT NULL,
  descricaoDoc VARCHAR(400) NOT NULL,
  idArea INT NOT NULL,
  nomePasta VARCHAR(250),
  PRIMARY KEY (idDocumentacao),
  FOREIGN KEY (idArea) REFERENCES areaDoc (idArea)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE arquivosDocumentacao (
  idArquivo INT AUTO_INCREMENT,
  idDocumentacao INT NOT NULL,
  nomeArquivo  VARCHAR(216) NOT NULL,
  PRIMARY KEY(idArquivo),
  FOREIGN KEY (idDocumentacao) REFERENCES documentacao (idDocumentacao)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

/* ./ Documentação */

/* Prazo de retenção */

CREATE TABLE areaRetencao (
  idArea INT AUTO_INCREMENT,
  descArea VARCHAR(20) NOT NULL UNIQUE,
  PRIMARY KEY (idArea)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE categoriaRetencao (
  idCategoria INT AUTO_INCREMENT,
  descCategoria VARCHAR(40) NOT NULL,
  idArea INT NOT NULL,
  PRIMARY KEY (idCategoria),
  UNIQUE KEY (idArea, descCategoria),
  FOREIGN KEY (idArea) REFERENCES areaRetencao (idArea)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE prazoRetencao (
  idRetencao INT AUTO_INCREMENT,
  idCategoria INT NOT NULL,
  titulo VARCHAR(40) NOT NULL,
  descricao VARCHAR(200) NOT NULL,
  destino ENUM('Indefinido', 'Temporário', 'Permanente') NOT NULL,
  finalidade VARCHAR(600) NOT NULL,
  dataPrazo VARCHAR(20) NOT NULL,
  link VARCHAR(2048),
  nomePasta VARCHAR(250),
  PRIMARY KEY (idRetencao),
  FOREIGN KEY (idCategoria) REFERENCES categoriaRetencao (idCategoria)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

CREATE TABLE arquivosRetencao (
  idArquivo INT AUTO_INCREMENT,
  idRetencao INT NOT NULL,
  nomeArquivo  VARCHAR(216) NOT NULL,
  PRIMARY KEY(idArquivo),
  FOREIGN KEY (idRetencao) REFERENCES prazoRetencao (idRetencao)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

/* Para adicionar mais itens no ENUM */
ALTER TABLE prazoRetencao CHANGE destino destino ENUM('Indefinido', 'Temporário', 'Permanente') NOT NULL;

/* ./ Prazo de retenção */

/* ------------------------------- */