Create Table respondente (
    id serial not null,
    login varchar(30) not null unique,
    senha varchar(255) not null,
    nome varchar(255) not null,
    email varchar(255) not null,
    cpf varchar(11) not null unique,
    telefone varchar(13) not null
);

    alter table respondente 
    add constraint pk_respondente
    primary key(id);

Create Table elaborador (
    id serial not null,
    login varchar(30) not null unique,
    senha varchar(255) not null,
    nome varchar(255) not null,
    email varchar(255) not null,
    cpf varchar(11) not null unique,
    instituicao varchar(255) not null,
    is_admin boolean default false
);

    alter table elaborador 
    add constraint pk_elaborador
    primary key(id);


Create Table questionario (
    id serial not null,
    nome varchar(255) not null,
    descricao varchar(255) not null,
    data_criacao timestamp default current_timestamp,
    nota_aprovacao int not null,
    elaborador_id int not null
);

    alter table questionario 
    add constraint pk_questionario
    primary key(id);

    alter table questionario
    add constraint fk_questionario_elaborador
    foreign key (elaborador_id) references elaborador(id);


Create Table questao (
    id serial not null,
    descricao varchar(255) not null,
    is_discursiva boolean default false,
    is_objetiva boolean default false,
    is_multipla_escolha boolean default false,
    imagem varchar(255)
   
);

    alter table questao 
    add constraint pk_questao
    primary key(id);


Create Table questionario_questao (
    id serial not null,
    pontos int not null,
    ordem int not null,
    questionario_id int,
    questao_id int not null
);

    alter table questionario_questao 
    add constraint pk_questionario_questao
    primary key(id);

    alter table questionario_questao
    add constraint fk_questionario_questao_questionario
    foreign key (questionario_id) references questionario(id);

    alter table questionario_questao
    add constraint fk_questionario_questao_questao
    foreign key (questao_id) references questao(id);


Create Table alternativa (
    id serial not null,
    descricao varchar(255) not null,
    is_correta boolean default false,
    questao_id int not null
);

    alter table alternativa 
    add constraint pk_alternativa
    primary key(id);

    alter table alternativa
    add constraint fk_alternativa_questao
    foreign key (questao_id) references questao(id);
	

Create Table oferta (
    id serial not null,
    data timestamp default current_timestamp,    
    respondente_id int,
    questionario_id int
);

    alter table oferta 
    add constraint pk_oferta
    primary key(id);

    alter table oferta
    add constraint fk_oferta_respondente
    foreign key (respondente_id) references respondente(id);

    alter table oferta
    add constraint fk_oferta_questionario
    foreign key (questionario_id) references questionario(id);


Create Table submissao (
    id serial not null,
    nome_ocasiao varchar(255),
    descricao varchar(255),
    data timestamp default current_timestamp,    
    respondente_id int, 
    oferta_id int
);

    alter table submissao 
    add constraint pk_submissao
    primary key(id);

    alter table submissao
    add constraint fk_submissao_oferta
    foreign key (oferta_id) references oferta(id);

    alter table submissao
    add constraint fk_submissao_respondente
    foreign key (respondente_id) references respondente(id);


Create Table resposta (
    id serial not null,
    texto varchar(255),
    observacao varchar(255),
    nota int,
    alternativa_id int,
    questao_id int not null,
    submissao_id int not null
);

    alter table resposta 
    add constraint pk_resposta
    primary key(id);

    alter table resposta
    add constraint fk_resposta_alternativa
    foreign key (alternativa_id) references alternativa(id);

    alter table resposta
    add constraint fk_resposta_questao
    foreign key (questao_id) references questao(id);

    alter table resposta
    add constraint fk_resposta_submissao
    foreign key (submissao_id) references submissao(id);


-- INSERT USER ADMIN
insert into elaborador(login, senha, nome, email, cpf, instituicao, is_admin) 
    values ('admin', 'admin#123', 'Admin', 'admin@admin.com', '98745625871', 'Quatões ABC', true);
	
	
-- INSERT RESPONDENTES
insert into respondente(login, senha, nome, email, cpf, telefone) 
    values ('sidartafifi', '1234', 'Sidarta Fifi', 'sidartaogato@gmail.com', '12312300002', '54991716182');

insert into respondente(login, senha, nome, email, cpf, telefone) 
    values ('respondente', '1234', 'Maria Respondente', 'mariarespondente@gmail.com', '12312300006', '54991716185');

--INSERT ELABORADOR
insert into elaborador(login, senha, nome, email, cpf, instituicao, is_admin) 
    values ('loganogato', '123', 'Logan', 'loganogato@gmail.com', '12312300001', 'Gatos Gordos', true);


-- INSERT QUESTAO
insert into questao(descricao, is_discursiva, is_objetiva, is_multipla_escolha) 
    values ('Qual é a capitarl da Nova Zelandia', false, false, true);
insert into questao(descricao, is_objetiva) 
    values ('Selecione a opcao correta', true);
insert into questao(descricao, is_discursiva) 
    values ('Faca uma redacao sobre o dia de hoje', true);
insert into questao(descricao, is_objetiva) 
    values ('Qual a cor do pe de predro', true);
insert into questao(descricao, is_objetiva) 
    values ('Qual a cor do cavalo branco de Napoleao', true);
	
	
--INSERT ALTERNATIVA
insert into alternativa (descricao, is_correta, questao_id)
	values ('Wellington',true, 1);
insert into alternativa (descricao, is_correta, questao_id)
	values ('Auckland',false, 1);
insert into alternativa (descricao, is_correta, questao_id)
	values ('Brisbane',false, 1);
	
insert into alternativa (descricao, is_correta, questao_id)
	values ('Seria essa a certa ?',false, 2);
insert into alternativa (descricao, is_correta, questao_id)
	values ('Talvez essa opcao', true, 2);
insert into alternativa (descricao, is_correta, questao_id)
	values ('Quem sabe essa ultima', false, 2);


-- INSERT QUESTIONARIO
insert into questionario(nome, descricao, nota_aprovacao, elaborador_id)
    values ('capitais', 'Avalie seu conhecimento sobre as capitais brasileiras', 10, 2);
insert into questionario(nome, descricao, nota_aprovacao, elaborador_id)
    values ('cores', 'Avalie seu conhecimento sobre as cores', 10, 2);
	

-- INSERT QUESTIONARIO_QUESTAO
insert into questionario_questao(pontos, ordem, questionario_id, questao_id) 
	values(2, 1, 1, 1);
insert into questionario_questao(pontos, ordem, questionario_id, questao_id) 
	values(4, 2, 1, 3);
insert into questionario_questao(pontos, ordem, questionario_id, questao_id) 
	values(2, 3, 1, 2);
insert into questionario_questao(pontos, ordem, questionario_id, questao_id) 
	values(2, 1, 2, 4);
insert into questionario_questao(pontos, ordem, questionario_id, questao_id) 
	values(2, 2, 2, 5);


--INSERT OFERTA
insert into oferta(respondente_id, questionario_id)
    values (1, 1);
insert into oferta(respondente_id, questionario_id)
    values (1, 1);

insert into oferta(respondente_id, questionario_id)
    values (2, 2);
insert into oferta(respondente_id, questionario_id)
    values (2, 2);


--INSERT SUBMISSAO
insert into submissao(nome_ocasiao, descricao, respondente_id, oferta_id)
    values ('Prova terceiro ano 2023', 'Teste aplicado aos alunos do terceiro ano', 1, 1);
	

--INSERT RESPOSTA
insert into resposta(texto, questao_id, submissao_id)
	values ('[Imagine aqui uma redacao]', 3, 1);
	
insert into resposta(alternativa_id, questao_id, submissao_id)
	values (2, 1, 1);


