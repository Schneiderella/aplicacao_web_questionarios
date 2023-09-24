# Quest

![image](https://user-images.githubusercontent.com/64370426/235376551-454466c9-5bf3-41f2-8308-71b003abc564.png)

## Sobre o Projeto

O projeto desenvolvido para a disciplina de Programação de Aplicações Web II, do curso de Análise e Desenvolvimento de Sistemas.


## Funcionalidades desenvolvidas
Cadastro de questionários, questões e alternativas. Disponibilização dos questionários prontos para serem respondidos pelos usuários cadastrados.

## Principais tecnologias utilizadas

PHP, Postgres


## Como instalar o projeto na sua máquina:

### Requisitos:
* Instalar WampServer64
> https://www.
* Instalação do Git
> https://git-scm.com/book/pt-br/v2/Come%C3%A7ando-Instalando-o-Git

### Clonando projeto para máquina local:

* Posicione-se, via terminal, dentro do repositório 'wamp64/wwww'. 
* Rode o comando
```
git clone https://github.com/clhobus013/questionarios.git
```

### Criando configurações para conexão ao banco de dados:
* dentro do diretório 'dao', crie o arquivo 'config.json'
* dentro do arquivo crie duas variáveis para colocar o nome do usuário e senha do banco de dados, da seguinte forma:

```
{
    "USERNAME": "username",
    "PASSWORD": "password"  
}

``` 


### Criando banco de dados:
* através do psql ou PGAdmin, rodar os scripts sql que estão no arquivo "script.sql", na pasta raiz do projeto.
