<?php require_once __DIR__ . '/../auth.php'; iniciar_sessao(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitar orçamento — Lion Lift | Cestas Aéreas</title>
  <meta name="description" content="Solicite um orçamento de cesta aérea para o seu veículo.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>

 
  <header class="site-header">
    <div class="container">
      <a href="index.php" class="brand" aria-label="Lion Lift — página inicial">
        <img src="imagens/Logo.png" alt="" class="brand-logo">
        <span class="brand-name">Lion <span>Lift</span></span>
      </a>

      <nav class="nav" id="site-nav">
        <ul class="nav-links">
          <li><a href="index.php">Início</a></li>
          <li><a href="cesta-aerea.php">Cesta Aérea</a></li>
          <li><a href="faq.php">FAQ</a></li>
          <li><a href="localizacao.php">Localização</a></li>
          <li><a href="contato.php">Contato</a></li>
        </ul>
        <a href="orcamento.php" class="btn btn-primary nav-cta">Solicitar orçamento</a>
<?php render_conta_nav('../'); ?>
      </nav>

      <button class="nav-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="site-nav">
        <span></span>
      </button>
    </div>
  </header>

  <main>

    
    <section class="page-header">
      <div class="container">
        <span class="eyebrow">Lion Lift</span>
        <h1>Solicitar orçamento</h1>
        <p class="lede">Preencha as informações abaixo para que a equipe avalie a sua solicitação. Para dúvidas e mensagens gerais, use a página de <a href="contato.php" class="text-link">Contato</a>.</p>
      </div>
    </section>

    
    <section class="section" style="padding-top: 16px;">
      <div class="container">

        
        <form class="form-panel form-panel--wide quote-form" id="form-orcamento"
              method="post" data-validar data-endpoint="../receber_orcamento.php" data-confirmacao="confirmacao-orcamento" novalidate>

          <p class="form-required-note">Campos marcados com <span class="req-mark" aria-hidden="true">*</span> são obrigatórios. Não é necessário informar CPF, placa, chassi ou outros documentos.</p>

          <!-- SEÇÃO 1 -->
          <div class="form-section" role="group" aria-labelledby="sec-solicitante">
            <h2 class="form-section-title" id="sec-solicitante"><span class="form-section-num">01</span> Dados do solicitante</h2>
            <div class="form-grid">
            <div class="form-row">
              <label for="nome">Nome completo <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="text" id="nome" name="nome" placeholder="Seu nome completo" autocomplete="name" required data-validate="fullname" data-msg-required="Informe o seu nome completo.">
            </div>
            <div class="form-row">
              <label for="empresa">Nome da empresa</label>
              <input type="text" id="empresa" name="empresa" placeholder="Opcional" autocomplete="organization">
            </div>
            <div class="form-row">
              <label for="telefone">Telefone / WhatsApp <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000" autocomplete="tel" inputmode="tel" required data-validate="phone" data-msg-required="Informe um telefone ou WhatsApp para contato.">
            </div>
            <div class="form-row">
              <label for="email">E-mail <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" autocomplete="email" required data-validate="email" data-msg-required="Informe o seu e-mail.">
            </div>
            </div>
          </div>

          
          <div class="form-section" role="group" aria-labelledby="sec-veiculo">
            <h2 class="form-section-title" id="sec-veiculo"><span class="form-section-num">02</span> Informações do veículo</h2>
            <div class="form-grid">
              <fieldset class="form-row form-row--full">
                <legend>O veículo já está disponível para a instalação? <span class="req-mark" aria-hidden="true">*</span></legend>
                <div class="radio-options">
                  <label class="radio-option"><input type="radio" name="veiculo_disponivel" value="sim" required data-msg-required="Informe se o veículo já está disponível."> Sim</label>
                  <label class="radio-option"><input type="radio" name="veiculo_disponivel" value="nao" required> Não</label>
                </div>
              </fieldset>
            <div class="form-row">
              <label for="marca">Marca do veículo <span class="req-mark" aria-hidden="true" hidden>*</span></label>
              <input type="text" id="marca" name="marca" placeholder="" data-required-if="veiculo_disponivel=sim" data-msg-required="Informe a marca do veículo.">
            </div>
            <div class="form-row">
              <label for="modelo">Modelo do veículo <span class="req-mark" aria-hidden="true" hidden>*</span></label>
              <input type="text" id="modelo" name="modelo" placeholder="" data-required-if="veiculo_disponivel=sim" data-msg-required="Informe o modelo do veículo.">
            </div>
            <div class="form-row">
              <label for="ano">Ano do veículo <span class="req-mark" aria-hidden="true" hidden>*</span></label>
              <input type="text" id="ano" name="ano" placeholder="" inputmode="numeric" maxlength="4" data-required-if="veiculo_disponivel=sim" data-validate="year" data-msg-required="Informe o ano do veículo.">
            </div>
            <div class="form-row">
              <label for="cidade_estado">Cidade e estado onde o veículo está localizado</label>
              <input type="text" id="cidade_estado" name="cidade_estado" placeholder="Ex.: cidade / UF">
            </div>
            </div>
            <p class="field-hint">Marca, modelo e ano são obrigatórios apenas se o veículo já estiver disponível.</p>
          </div>

          
          <div class="form-section" role="group" aria-labelledby="sec-detalhes">
            <h2 class="form-section-title" id="sec-detalhes"><span class="form-section-num">03</span> Detalhes do orçamento</h2>
            <div class="form-grid">
            <div class="form-row form-row--full">
              <label for="servico">Qual serviço ou solução você deseja solicitar? <span class="req-mark" aria-hidden="true">*</span></label>
              <input type="text" id="servico" name="servico" placeholder="Descreva brevemente" required data-msg-required="Informe o serviço ou solução desejada.">
            </div>
              <div class="form-row form-row--full">
                <label for="finalidade">Qual será a finalidade de utilização da cesta aérea? <span class="req-mark" aria-hidden="true">*</span></label>
                <select id="finalidade" name="finalidade" required data-msg-required="Selecione a finalidade de utilização.">
                  <option value="" selected disabled>Selecione uma opção</option>
                  <option value="manutencao-redes-eletricas">Manutenção de redes elétricas</option>
                  <option value="iluminacao-publica">Iluminação pública</option>
                  <option value="telecomunicacoes">Telecomunicações</option>
                  <option value="poda-manutencao-arvores">Poda e manutenção de árvores</option>
                  <option value="outra">Outra finalidade</option>
                </select>
              </div>
              <div class="form-row form-row--full">
                <label for="descricao">Informações adicionais ou descrição da necessidade</label>
                <textarea id="descricao" name="descricao" aria-describedby="dica-descricao" placeholder="Opcional"></textarea>
                <p class="field-hint" id="dica-descricao">Descreva sua necessidade com o máximo de detalhes que considerar relevantes. Se escolheu “Outra finalidade”, explique-a aqui.</p>
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enviar solicitação</button>
          </div>
          <p class="form-status" role="status"></p>
        </form>

      </div>
    </section>

  </main>

  
  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <div class="footer-brand-row">
          <img src="imagens/Logo.png" alt="" class="footer-logo">
          <span class="brand-name">Lion <span>Lift</span></span>
        </div>
        <p class="footer-tagline">Fabricação e instalação de cestas aéreas.</p>
      </div>
      <div class="footer-contact">
        <span>Telefone: <span class="placeholder-value">(19) 99943-4699</span></span>
        <span>E-mail: <span class="placeholder-value">juniorcesar.clemente@gmail.com</span></span>
        <span>Endereço: <a class="placeholder-value" href="https://www.google.com/maps/search/?api=1&amp;query=Rua+Virg%C3%ADnia+Pratta+Gregolin%2C+575%2C+Piracicaba%2C+SP%2C+Brasil" target="_blank" rel="noopener noreferrer">Rua Virgínia Pratta Gregolin, 575
Piracicaba, SP</a></span>
      </div>
    </div>
  </footer>

 
  <div class="modal" id="confirmacao-orcamento" hidden>
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="confirmacao-titulo" tabindex="-1">
      <img src="imagens/Logo.png" alt="Lion Lift" class="modal-logo">
      <div class="modal-icon" aria-hidden="true">
        <svg viewBox="0 0 20 20" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10.5l4 4 8-9"/></svg>
      </div>
      <h2 id="confirmacao-titulo">Solicitação recebida</h2>
      <p>Sua solicitação de orçamento foi recebida. As informações enviadas serão analisadas pela equipe da Lion Lift.</p>

      
      <p class="modal-protocol">Número da solicitação:
        <strong class="is-pending" data-protocolo data-texto-provisorio="aguardando integração com o sistema">aguardando integração com o sistema</strong>
      </p>

      <div class="modal-actions">
        <button type="button" class="btn btn-primary" data-fechar-modal>Fechar</button>
        <a href="index.php" class="btn btn-outline">Voltar ao início</a>
      </div>
    </div>
  </div>

</body>
</html>
