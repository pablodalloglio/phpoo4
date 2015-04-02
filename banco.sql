DROP TABLE cidade;
CREATE TABLE cidade (
    id integer,
    nome character varying(40),
    estado character(2)
);

DROP TABLE cliente;
CREATE TABLE cliente (
    id integer,
    nome character varying(40),
    endereco character varying(40),
    telefone character varying(20),
    id_cidade integer
);

DROP TABLE fabricante;
CREATE TABLE fabricante (
    id integer,
    nome character varying(40),
    site character varying(40)
);

DROP TABLE item;
CREATE TABLE item (
    id_venda integer,
    id_produto integer,
    quantidade double precision,
    id integer
);

DROP TABLE produto;
CREATE TABLE produto (
    id integer,
    descricao character varying(40),
    estoque double precision,
    preco_custo double precision,
    preco_venda double precision,
    id_fabricante integer
);

DROP TABLE venda;
CREATE TABLE venda (
    id integer,
    id_cliente integer,
    data_venda date,
    valor_total double precision,
    valor_pago double precision,
    desconto double precision
);


INSERT INTO cidade VALUES (4, 'Rio de Janeiro', 'RJ');
INSERT INTO cidade VALUES (3, 'Belo Horizonte', 'MG');
INSERT INTO cidade VALUES (2, 'São Paulo', 'SP');
INSERT INTO cidade VALUES (1, 'Porto Alegre', 'RS');

INSERT INTO cliente VALUES (3, 'Diego Feitosa', 'Rua do Diego', '(11) 1234-5678', 2);
INSERT INTO cliente VALUES (5, 'Luciano Zangeronimo', 'Rua do Luciano', '(11) 1234-5678', 2);
INSERT INTO cliente VALUES (6, 'Rodrigo Bisterço', 'Rua do Rodrigo', '(11) 1234-5678', 2);
INSERT INTO cliente VALUES (7, 'Tobias Taurian Viana', 'Rua do Tobias', '(21) 1234-5678', 4);
INSERT INTO cliente VALUES (4, 'Fernando H. Correa', 'Rua do Fernando', '(11) 1234-5678', 2);
INSERT INTO cliente VALUES (2, 'Bruno Canongia', 'Rua do Bruno', '(31) 1234-5678', 3);
INSERT INTO cliente VALUES (8, 'Luís Zendrael', 'Rua do Zendrael', '(11) 1234-5678', 2);
INSERT INTO cliente VALUES (1, 'Adler Medrado', 'Rua do Adler', '(81) 1234-5678', 3);


INSERT INTO fabricante VALUES (3, 'Corsair', 'www.corsair.com');
INSERT INTO fabricante VALUES (4, 'Olimpus', 'www.olimpus.com');
INSERT INTO fabricante VALUES (5, 'Samsung', 'www.samsung.com');
INSERT INTO fabricante VALUES (6, 'Sony', 'www.sony.com');
INSERT INTO fabricante VALUES (7, 'Creative', 'www.creative.com');
INSERT INTO fabricante VALUES (8, 'Intel', 'www.intel.com');
INSERT INTO fabricante VALUES (9, 'HP', 'www.hp.com');
INSERT INTO fabricante VALUES (10, 'Satellite', 'www.satellite.com');
INSERT INTO fabricante VALUES (2, 'Seagate', 'www.seagate.com');
INSERT INTO fabricante VALUES (1, 'Kingston', 'www.kingston.com');


INSERT INTO item VALUES (1, 1, 2, 1);
INSERT INTO item VALUES (1, 2, 3, 2);
INSERT INTO item VALUES (2, 3, 1, 3);
INSERT INTO item VALUES (3, 5, 1, 5);
INSERT INTO item VALUES (3, 7, 2, 6);


INSERT INTO produto VALUES (1, 'Pendrive 512Mb', 10, 20, 40, 1);
INSERT INTO produto VALUES (3, 'SD CARD  512MB', 4, 20, 35, 3);
INSERT INTO produto VALUES (4, 'SD CARD 1GB MINI', 3, 28, 40, 1);
INSERT INTO produto VALUES (5, 'CAM. FOTO I70 PLATA', 5, 600, 900, 5);
INSERT INTO produto VALUES (6, 'CAM. FOTO DSC-W50 PLATA', 4, 400, 700, 6);
INSERT INTO produto VALUES (7, 'WEBCAM INSTANT VF0040SP', 4, 50, 80, 7);
INSERT INTO produto VALUES (8, 'CPU 775 CEL.D 360  3.46 512K 533M', 10, 140, 300, 8);
INSERT INTO produto VALUES (9, 'FILMADORA DCR-DVD108', 2, 900, 1400, 6);
INSERT INTO produto VALUES (10, 'HD IDE  80G 7.200', 8, 90, 160, 5);
INSERT INTO produto VALUES (11, 'IMP LASERJET 1018 USB 2.0', 4, 200, 300, 9);
INSERT INTO produto VALUES (12, 'MEM DDR  512MB 400MHZ PC3200', 10, 60, 100, 5);
INSERT INTO produto VALUES (13, 'MEM DDR2 1024MB 533MHZ PC4200', 5, 100, 170, 3);
INSERT INTO produto VALUES (14, 'MON LCD 19" 920N PRETO', 2, 500, 800, 5);
INSERT INTO produto VALUES (15, 'MOUSE USB OMC90S OPT.C/LUZ', 10, 20, 40, 5);
INSERT INTO produto VALUES (16, 'NB DV6108 CS 1.86/512/80/DVD+RW ', 2, 1400, 2500, 9);
INSERT INTO produto VALUES (17, 'NB N220E/B DC 1.6/1/80/DVD+RW ', 3, 1800, 3400, 6);
INSERT INTO produto VALUES (18, 'CAM. FOTO DSC-W90 PLATA', 5, 600, 1200, 6);
INSERT INTO produto VALUES (19, 'CART. 8767 NEGRO', 20, 30, 50, 9);
INSERT INTO produto VALUES (20, 'CD-R TUBO DE 100 52X 700MB', 20, 30, 60, 5);
INSERT INTO produto VALUES (21, 'MEM DDR 1024MB 400MHZ PC3200', 7, 80, 150, 1);
INSERT INTO produto VALUES (22, 'MOUSE PS2 A7 AZUL/PLATA', 20, 5, 15, 10);
INSERT INTO produto VALUES (23, 'SPEAKER AS-5100 HOME PRATA', 5, 100, 180, 10);
INSERT INTO produto VALUES (24, 'TEC. USB ABNT AK-806', 14, 20, 40, 10);
INSERT INTO produto VALUES (2, 'HD 120 GB', 20, 100, 180, 2);


INSERT INTO venda VALUES (3, 6, '2007-07-22', 1060, 1000, 60);
INSERT INTO venda VALUES (1, 4, '2007-07-10', 620, 600, 20);
INSERT INTO venda VALUES (2, 8, '2007-07-15', 115, 100, 15);
