Lion Lift — Site Institucional

1. Sobre o projeto

A Lion Lift é uma empresa que atua no segmento de fabricação e montagem de cestas aéreas, equipamentos utilizados em veículos para permitir a realização de trabalhos em altura com mais praticidade e segurança. Esses equipamentos são aplicados em atividades como manutenção de redes elétricas, iluminação pública, telecomunicações e outros serviços que exigem acesso a locais elevados.

Pensando nesse segmento, o projeto consiste no desenvolvimento de um site institucional para apresentar a empresa, seus equipamentos e os serviços oferecidos, aproximando a Lion Lift de empresas, profissionais e potenciais clientes interessados nesse tipo de solução.

A proposta é oferecer um espaço onde os visitantes possam conhecer melhor a empresa, consultar informações sobre os equipamentos, esclarecer dúvidas e solicitar orçamentos de maneira simples e acessível. Além disso, o site conta com uma área do cliente, permitindo que usuários cadastrados acompanhem suas solicitações de orçamento e gerenciem os dados de suas contas.

Dessa forma, o projeto busca contribuir para a presença digital da Lion Lift, facilitando a comunicação com seus clientes e tornando o acesso às informações e aos serviços da empresa mais organizado.

2. Integrantes

- Adalberto Santos da Silva
- Enzo Silveira
- Gabriel Notalgiacomo

3. Tecnologias utilizadas

- HTML5: estruturação das páginas.
- CSS3: estilização e responsividade.
- JavaScript: interatividade e validação de formulários.
- PHP: processamento das informações, autenticação e comunicação com o banco de dados.
- MySQL: armazenamento e gerenciamento dos dados.
- XAMPP: ambiente local para execução do projeto.
- phpMyAdmin: gerenciamento e importação do banco de dados.

4. Principais funcionalidades

- Página inicial com apresentação da empresa.
- Página de apresentação dos equipamentos e serviços.
- Galeria de imagens.
- Página de perguntas frequentes (FAQ).
- Página de contato e localização com mapa integrado.
- Solicitação de orçamento por formulário.
- Cadastro, login e logout de usuários.
- Área do cliente com resumo da conta.
- Consulta das solicitações de orçamento vinculadas ao usuário.
- Edição de dados da conta e alteração de senha.
- Integração com WhatsApp para contato.

5. Estrutura do projeto

O projeto está organizado em pastas e arquivos responsáveis pelas diferentes funcionalidades:

- site/: páginas públicas, estilos, scripts e imagens.
- cliente/: páginas da área autenticada do cliente.
- auth.php: funções de autenticação e gerenciamento de sessões.
- db.php: conexão com o banco de dados.
- login.php e register.php: autenticação e cadastro.
- logout.php: encerramento da sessão.
- receber_orcamento.php: processamento das solicitações de orçamento.
- banco.sql: criação do banco de dados e suas tabelas.

6. Instalação e configuração

Para executar o projeto localmente, é necessário ter o XAMPP instalado.

1. Instale e abra o XAMPP.
2. Inicie os serviços Apache e MySQL.
3. Copie a pasta `user_system` para o diretório `htdocs` do XAMPP.
4. Acesse o phpMyAdmin pelo endereço `http://localhost/phpmyadmin`.
5. Importe o arquivo `banco.sql`, disponível na pasta principal do projeto.
6. Confira as configurações de conexão no arquivo `db.php`. A configuração padrão utiliza o servidor `localhost`, usuário `root`, senha vazia e banco `user_system`.

7. Como executar

Após realizar a instalação e configuração:

1. Mantenha os serviços Apache e MySQL iniciados no XAMPP.
2. Abra o navegador.
3. Acesse `http://localhost/user_system/site/index.php`.

Caso a pasta tenha sido renomeada, substitua `user_system` pelo nome utilizado no diretório `htdocs`.

Para testar a área do cliente, realize um cadastro e entre com os dados da conta criada.

8. Requisitos

- XAMPP ou ambiente equivalente com Apache, PHP e MySQL/MariaDB.
- PHP com as extensões MySQLi e mbstring habilitadas.
- Navegador atualizado.
- Conexão com a internet para recursos externos, como Google Fonts, Google Maps e WhatsApp.

9. Banco de dados

O projeto utiliza o banco de dados **user_system**, composto pelas tabelas:

- `users`: armazena os dados das contas cadastradas.
- `solicitacoes`: armazena as solicitações de orçamento, permitindo vinculá-las às contas dos usuários.

O arquivo `banco.sql` contém a estrutura necessária para criar o banco e suas tabelas.