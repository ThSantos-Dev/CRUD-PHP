# Relacionamento na prática
use db_contatos;

# Criando tabela de Estados
create table tblEstados(
	idEstado int not null auto_increment primary key,
    sigla varchar(2) not null,
    nome varchar(30) not null
);

# Apagando os dados da tabela de contatos
delete from tbl_contatos;

# Adicionando FK em uma tabela existente
alter table tbl_contatos
	add column idEstado int not null, # Adicionando a coluna que receberá a FK
	add constraint FK_Estados_Contatos # Construindo uma FK que vem de Estados e vai para Contatos
	foreign key (idEstado)				# Apontando para a coluna que recebe a FK
    references tblEstados(idEstado);	# Indicando de onde será retirada a PK para se tornar a FK

desc tbl_contatos;

# Inserindo mais de um Registro por SCRIPT
insert into tblEstados ( sigla, nome )
			values 	   ('SP', 'São Paulo'),
					   ('RJ', 'Rio de Janeiro'),
                       ('MG', 'Minas Gerais');

select * from tbl_contatos;


select * from tblEstados where idEstado = 1;









