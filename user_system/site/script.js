

(function () {
  'use strict';

  
  var WHATSAPP_NUMERO = '5519999434699';
  var WHATSAPP_MENSAGEM = 'Olá! Gostaria de saber mais sobre as cestas aéreas.';

  var MSG_ERRO_ENVIO =
    'Não foi possível enviar agora. Tente novamente em alguns instantes ou fale conosco pelo WhatsApp.';


  
  function iniciarMenu() {
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.querySelector('.nav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', function () {
      var aberto = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', aberto ? 'true' : 'false');
      toggle.setAttribute('aria-label', aberto ? 'Fechar menu' : 'Abrir menu');
    });
  }


  
  function iniciarMenuConta() {
    var menus = document.querySelectorAll('.nav-account-menu');
    if (!menus.length) return;

    document.addEventListener('click', function (evento) {
      menus.forEach(function (menu) {
        if (menu.open && !menu.contains(evento.target)) menu.open = false;
      });
    });

    document.addEventListener('keydown', function (evento) {
      if (evento.key !== 'Escape') return;
      menus.forEach(function (menu) {
        if (!menu.open) return;
        menu.open = false;
        var resumo = menu.querySelector('summary');
        if (resumo) resumo.focus();
      });
    });
  }


  function criarBotaoWhatsapp() {
    if (document.querySelector('.whatsapp-float')) return; 

    var link = document.createElement('a');
    link.className = 'whatsapp-float';
    link.href = 'https://wa.me/' + WHATSAPP_NUMERO + '?text=' + encodeURIComponent(WHATSAPP_MENSAGEM);
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    link.setAttribute('aria-label', 'Fale conosco pelo WhatsApp');
    link.title = 'Fale conosco pelo WhatsApp';
    link.innerHTML =
      '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' +
      '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>' +
      '</svg>';
    document.body.appendChild(link);
  }


  
  var REGRAS = {
    email: function (v) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)
        ? '' : 'Informe um e-mail válido, como nome@exemplo.com.';
    },

    phone: function (v) {
      var d = v.replace(/\D/g, '');
      if (d.length > 11 && d.indexOf('55') === 0) d = d.slice(2); 
      if (d.length < 10 || d.length > 11) {
        return 'Informe o telefone com DDD (10 ou 11 dígitos).';
      }
      if (d.charAt(0) === '0') return 'Verifique o DDD informado.';
      if (d.length === 11 && d.charAt(2) !== '9') {
        return 'Celulares com 11 dígitos começam com 9 depois do DDD.';
      }
      return '';
    },

    name: function (v) {
      return v.length >= 2 ? '' : 'Informe o seu nome.';
    },

    fullname: function (v) {
      return v.split(/\s+/).length >= 2 ? '' : 'Informe nome e sobrenome.';
    },

    
    password: function (v) {
      return v.length >= 8 ? '' : 'A senha deve ter pelo menos 8 caracteres.';
    },

    year: function (v) {
      var maximo = new Date().getFullYear() + 1;
      var ano = parseInt(v, 10);
      return (/^\d{4}$/.test(v) && ano >= 1900 && ano <= maximo)
        ? '' : 'Informe um ano válido com 4 dígitos.';
    }
  };

  function mascaraTelefone(valor) {
    var d = valor.replace(/\D/g, '');
    if (d.length > 11 && d.indexOf('55') === 0) d = d.slice(2);
    d = d.slice(0, 11);
    if (d.length === 0) return '';
    if (d.length <= 2) return '(' + d;
    if (d.length <= 6) return '(' + d.slice(0, 2) + ') ' + d.slice(2);
    if (d.length <= 10) return '(' + d.slice(0, 2) + ') ' + d.slice(2, 6) + '-' + d.slice(6);
    return '(' + d.slice(0, 2) + ') ' + d.slice(2, 7) + '-' + d.slice(7);
  }

  

  function controlesDaLinha(linha) {
    return Array.prototype.slice.call(linha.querySelectorAll('input, select, textarea'));
  }

  function valorDaLinha(linha) {
    var controles = controlesDaLinha(linha);
    var primeiro = controles[0];
    if (primeiro.type === 'radio' || primeiro.type === 'checkbox') {
      var marcado = controles.filter(function (c) { return c.checked; })[0];
      return marcado ? marcado.value : '';
    }
    if (primeiro.type === 'password') return primeiro.value; 
    return primeiro.value.trim();
  }

  
  function condicaoAtendida(form, condicao) {
    var partes = condicao.split('=');
    var marcado = form.querySelector('[name="' + partes[0] + '"]:checked');
    return !!marcado && marcado.value === partes[1];
  }

  function linhaObrigatoria(linha, form) {
    var primeiro = controlesDaLinha(linha)[0];
    if (primeiro.hasAttribute('required')) return true;
    var condicao = primeiro.getAttribute('data-required-if');
    return condicao ? condicaoAtendida(form, condicao) : false;
  }

  
  function erroDaLinha(linha, form) {
    var primeiro = controlesDaLinha(linha)[0];
    var valor = valorDaLinha(linha);

    if (valor === '') {
      if (!linhaObrigatoria(linha, form)) return '';
      var padrao = (primeiro.type === 'radio' || primeiro.tagName === 'SELECT')
        ? 'Selecione uma opção.' : 'Preencha este campo.';
      return primeiro.getAttribute('data-msg-required') || padrao;
    }

    var regra = primeiro.getAttribute('data-validate');
    var erroDeFormato = (regra && REGRAS[regra]) ? REGRAS[regra](valor) : '';
    if (erroDeFormato) return erroDeFormato;

    
    var outro = primeiro.getAttribute('data-match');
    if (outro) {
      var campoAlvo = form.querySelector('[name="' + outro + '"]');
      if (campoAlvo && campoAlvo.value !== primeiro.value) {
        return primeiro.getAttribute('data-msg-match') || 'Os valores não coincidem.';
      }
    }
    return '';
  }

  
  function elementoDeErro(linha) {
    var existente = linha.querySelector('.field-error');
    if (existente) return existente;

    var controles = controlesDaLinha(linha);
    var primeiro = controles[0];
    var erro = document.createElement('p');
    erro.className = 'field-error';
    erro.id = 'erro-' + (primeiro.name || primeiro.id);
    linha.appendChild(erro);

    var alvos = (primeiro.type === 'radio') ? [linha] : [primeiro];
    alvos.forEach(function (alvo) {
      var atual = alvo.getAttribute('aria-describedby');
      alvo.setAttribute('aria-describedby', atual ? atual + ' ' + erro.id : erro.id);
    });
    return erro;
  }

  function marcarInvalido(linha, mensagem) {
    elementoDeErro(linha).textContent = mensagem;
    linha.classList.add('is-invalid');
    linha.classList.remove('is-valid');
    controlesDaLinha(linha).forEach(function (c) { c.setAttribute('aria-invalid', 'true'); });
  }

  function marcarValido(linha, temValor) {
    elementoDeErro(linha).textContent = '';
    linha.classList.remove('is-invalid');
    linha.classList.toggle('is-valid', temValor); 
    controlesDaLinha(linha).forEach(function (c) { c.removeAttribute('aria-invalid'); });
  }

  function limparLinha(linha) {
    var erro = linha.querySelector('.field-error');
    if (erro) erro.textContent = '';
    linha.classList.remove('is-invalid', 'is-valid');
    controlesDaLinha(linha).forEach(function (c) { c.removeAttribute('aria-invalid'); });
  }

  
  function validarLinha(linha, form) {
    var mensagem = erroDaLinha(linha, form);
    if (mensagem) {
      marcarInvalido(linha, mensagem);
      return false;
    }
    marcarValido(linha, valorDaLinha(linha) !== '');
    return true;
  }

  function linhasDoFormulario(form) {
    return Array.prototype.slice.call(form.querySelectorAll('.form-row'))
      .filter(function (linha) { return controlesDaLinha(linha).length > 0; });
  }

  
  function atualizarCamposCondicionais(form) {
    linhasDoFormulario(form).forEach(function (linha) {
      var primeiro = controlesDaLinha(linha)[0];
      if (!primeiro.hasAttribute('data-required-if')) return;

      var obrigatorio = linhaObrigatoria(linha, form);
      var marca = linha.querySelector('.req-mark');
      if (marca) marca.hidden = !obrigatorio;
      if (obrigatorio) primeiro.setAttribute('aria-required', 'true');
      else primeiro.removeAttribute('aria-required');

      if (!obrigatorio && valorDaLinha(linha) === '') limparLinha(linha);
    });
  }

  function validarFormulario(form) {
    var primeiroInvalido = null;
    linhasDoFormulario(form).forEach(function (linha) {
      if (!validarLinha(linha, form) && !primeiroInvalido) primeiroInvalido = linha;
    });
    if (primeiroInvalido) controlesDaLinha(primeiroInvalido)[0].focus();
    return !primeiroInvalido;
  }

  
  function mostrarStatus(form, texto, tipo) {
    var status = form.querySelector('.form-status');
    if (!status) return;
    status.textContent = texto;
    status.className = 'form-status is-visible is-' + tipo;
    status.setAttribute('role', tipo === 'error' ? 'alert' : 'status');
  }

  function limparStatus(form) {
    var status = form.querySelector('.form-status');
    if (!status) return;
    status.textContent = '';
    status.className = 'form-status';
  }

  function ativarValidacao(form) {
    
    form.querySelectorAll('input[type="tel"]').forEach(function (campo) {
      campo.addEventListener('input', function () {
        var formatado = mascaraTelefone(campo.value);
        if (formatado !== campo.value) campo.value = formatado;
      });
    });

    
    form.addEventListener('focusout', function (evento) {
      var linha = evento.target.closest && evento.target.closest('.form-row');
      if (linha && controlesDaLinha(linha).length) validarLinha(linha, form);
    });

    
    function aoAlterar(evento) {
      var linha = evento.target.closest && evento.target.closest('.form-row');
      if (!linha) return;
      var jaAvaliada = linha.classList.contains('is-invalid') || linha.classList.contains('is-valid');
      if (jaAvaliada || evento.target.type === 'radio' || evento.target.tagName === 'SELECT') {
        validarLinha(linha, form);
      }
      if (evento.target.type === 'radio') atualizarCamposCondicionais(form);

      
      var nome = evento.target.name;
      if (nome) {
        form.querySelectorAll('[data-match="' + nome + '"]').forEach(function (dependente) {
          var linhaDep = dependente.closest('.form-row');
          if (linhaDep && (linhaDep.classList.contains('is-invalid') || linhaDep.classList.contains('is-valid'))) {
            validarLinha(linhaDep, form);
          }
        });
      }
    }
    form.addEventListener('input', aoAlterar);
    form.addEventListener('change', aoAlterar);

    atualizarCamposCondicionais(form);
  }


  
  function enviarFormulario(form) {
    var endpoint = form.getAttribute('data-endpoint');

    if (!endpoint) {
      mostrarStatus(
        form,
        'Os dados estão corretos, mas o envio ainda não está conectado ao sistema. Nenhuma informação foi enviada.',
        'info'
      );
      return;
    }

    var botao = form.querySelector('button[type="submit"]');
    var textoOriginal = botao ? botao.textContent : '';
    if (botao) { botao.disabled = true; botao.textContent = 'Enviando...'; }
    limparStatus(form);

    fetch(endpoint, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'Accept': 'application/json' }
    })
      .then(function (resposta) {
        return resposta.json().then(function (dados) {
          return { ok: resposta.ok, dados: dados };
        });
      })
      .then(function (resultado) {
        if (resultado.ok && resultado.dados && resultado.dados.sucesso === true) {
          aoReceberSucesso(form, resultado.dados);
          return;
        }
        var erro = new Error((resultado.dados && resultado.dados.mensagem) || MSG_ERRO_ENVIO);
        throw erro;
      })
      .catch(function (erro) {
        
        var mensagem = (erro && erro.message && erro.name === 'Error') ? erro.message : MSG_ERRO_ENVIO;
        mostrarStatus(form, mensagem, 'error');
      })
      .then(function () {
        if (botao) { botao.disabled = false; botao.textContent = textoOriginal; }
      });
  }

 
  function aoReceberSucesso(form, dados) {
    var idDoModal = form.getAttribute('data-confirmacao');

    form.reset();
    linhasDoFormulario(form).forEach(limparLinha);
    atualizarCamposCondicionais(form);
    limparStatus(form);

    if (idDoModal) {
      abrirModal(idDoModal, dados.id); 
    } else {
      mostrarStatus(form, dados.mensagem || 'Mensagem enviada com sucesso.', 'success');
    }
  }

  function aoEnviar(evento) {
    var form = evento.currentTarget;

    if (!validarFormulario(form)) {
      evento.preventDefault();
      mostrarStatus(form, 'Revise os campos destacados antes de enviar.', 'error');
      return;
    }

    
    if (form.hasAttribute('data-envio-nativo')) return;

    evento.preventDefault();
    limparStatus(form);
    enviarFormulario(form);
  }


  
  var focoAnterior = null;

  var SELETOR_FOCAVEIS =
    'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

  function abrirModal(idDoModal, idDaSolicitacao) {
    var modal = document.getElementById(idDoModal);
    if (!modal) return;

    var campo = modal.querySelector('[data-protocolo]');
    if (campo) {
      var temId = idDaSolicitacao !== undefined && idDaSolicitacao !== null &&
                  String(idDaSolicitacao).trim() !== '';
      if (temId) {
        campo.textContent = String(idDaSolicitacao); 
        campo.classList.remove('is-pending');
      } else {
        campo.textContent = campo.getAttribute('data-texto-provisorio');
        campo.classList.add('is-pending');
      }
    }

    focoAnterior = document.activeElement;
    modal.hidden = false;
    document.body.classList.add('modal-open');
    var dialogo = modal.querySelector('.modal-dialog');
    if (dialogo) dialogo.focus();
  }

  function fecharModal(modal) {
    modal.hidden = true;
    document.body.classList.remove('modal-open');
    if (focoAnterior && focoAnterior.focus) focoAnterior.focus();
  }

  function ativarModais() {
    document.querySelectorAll('.modal').forEach(function (modal) {
      // Botões com data-fechar-modal e clique no fundo escuro
      modal.addEventListener('click', function (evento) {
        if (evento.target === modal || evento.target.closest('[data-fechar-modal]')) {
          fecharModal(modal);
        }
      });

      modal.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') { fecharModal(modal); return; }

        
        if (evento.key === 'Tab') {
          var focaveis = modal.querySelectorAll(SELETOR_FOCAVEIS);
          if (!focaveis.length) return;
          var primeiro = focaveis[0];
          var ultimo = focaveis[focaveis.length - 1];
          if (evento.shiftKey && (document.activeElement === primeiro ||
              document.activeElement === modal.querySelector('.modal-dialog'))) {
            evento.preventDefault(); ultimo.focus();
          } else if (!evento.shiftKey && document.activeElement === ultimo) {
            evento.preventDefault(); primeiro.focus();
          }
        }
      });
    });
  }


  
  function mostrarAvisoDeAcesso() {
    var textos = {
      login: 'Login realizado com sucesso.',
      cadastro: 'Conta criada com sucesso! Você já está conectado.'
    };
    var aviso = new URLSearchParams(window.location.search).get('aviso');
    if (!aviso || !textos.hasOwnProperty(aviso)) return;

    var caixa = document.createElement('div');
    caixa.className = 'toast';
    caixa.setAttribute('role', 'status');
    caixa.innerHTML = '<span></span><button type="button" aria-label="Fechar aviso">&times;</button>';
    caixa.firstChild.textContent = textos[aviso];
    caixa.querySelector('button').addEventListener('click', function () { caixa.remove(); });
    document.body.appendChild(caixa);
    window.setTimeout(function () { caixa.remove(); }, 6000);

    
    if (window.history && window.history.replaceState) {
      window.history.replaceState(null, '', window.location.pathname + window.location.hash);
    }
  }


 
  document.addEventListener('DOMContentLoaded', function () {
    iniciarMenu();
    iniciarMenuConta();
    criarBotaoWhatsapp();
    ativarModais();
    mostrarAvisoDeAcesso();

    document.querySelectorAll('form[data-validar]').forEach(function (form) {
      ativarValidacao(form);
      form.addEventListener('submit', aoEnviar);
    });
  });
})();
