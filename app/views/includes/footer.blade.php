<!-- ANALYTICS -->
<!-- <script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-20571872-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script> -->
  <script src="{{ URL::to("/") }}/js/script.js"></script>

<!-- Crazy Egg -->
<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName("script")[0];
a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0035/4321.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>

  <!--   RODAPE DO SITE   -->
  <div id="footer" class="container">

		<div class="twelve columns">

      <!--   CRÉDITOS - LOGOS   -->

      <!--   CRÉDITOS - LOGOS   -->
      <div id="credits" class="clearfix">
        <ul class="footer-links">
          @if( Auth::guest() )
          <li><a href="{{ URL::to("/users/login") }}">Login</a></li>
          <li><a href="{{ URL::to("/users/account") }}">Cadastrar-se</a></li>
          @endif
          <li><a href="{{ URL::to("/") }}/project">O projeto</a></li>
          <li><a href="{{ URL::to("/") }}/faq">FAQ</a></li>
          <li><a href="mailto: arquigrafiabrasil@gmail.com">Contato</a></li>
        </ul>
        <ul class="footer-logos">
          <li><a href="http://www.usp.br/" title="USP" id="usp" target="_blank"></a></li>
          <li><a href="http://www.fapesp.br/" title="FAPESP" id="fapesp" target="_blank"></a></li>
          <li><a href="http://www.rnp.br/" title="RNP" id="rnp" target="_blank"></a></li>
        </ul>
        <ul class="footer-logos">
          <li><a href="http://www.cnpq.br/" title="CNPQ" id="cnpq" target="_blank"></a></li>
          <li><a href="http://ccsl.ime.usp.br/" title="CCSL" id="ccsl" target="_blank"></a></li>
         <li><a href="{{ URL::to("/") }}/chancela" title="Chancela do Ministério da Cultura" id="chancela"></a></li>
        </ul>
        <ul class="footer-logos">
          <li><a href="http://www.usp.br/fau/" title="FAU" id="fau" target="_blank"></a></li>
          <li><a href="http://www.ime.usp.br/" title="IME" id="ime" target="_blank"></a></li>
          <li><a href="http://www.eca.usp.br/" title="ECA" id="eca" target="_blank"></a></li>
        </ul>
        <ul class="footer-logos">
          <li><a href="http://www.quapa.fau.usp.br/quapa_desenv/default.htm" title="QUAPÁ" id="quapa" target="_blank"></a></li>
          <li><a href="http://www.vitruvius.com.br/" title="Vitruvius" id="vitruvius" target="_blank"></a></li>
          <li><a href="http://www.scansystem.com.br/" title="Scan system" id="scansystem"
            target="_blank"><img src="{{ asset('img/scan-system.png')}}" height="37" ></a></li>
        </ul>
        <ul class="footer-logos last">
          <li><a href="http://www.bench.com.br/" title="Benchmark" id="benchmark" target="_blank"></a></li>
          <li><a href="http://doctela.com.br/" title="Doctela" id="doctela" target="_blank"></a></li>
        </ul>
      </div>
      <!--   FIM - CRÉDITOS - LOGOS   -->

      <div class="twelve columns alpha omega">
        <p><small>O Arquigrafia tem envidado todos os esforços para que nenhum direito autoral seja violado. Todas as imagens passíveis de download no Arquigrafia possuem uma licença <a href="http://creativecommons.org/licenses/?lang=pt" target="_blank">Creative Commons</a> específica. Caso seja encontrado algum arquivo/imagem que, por qualquer motivo, o autor entenda que afete seus direitos autorais, <a href="mailto: arquigrafiabrasil@gmail.com">clique aqui</a> e informe à equipe do portal Arquigrafia para que a situação seja imediatamente regularizada.</small></p>
      </div>

      <div class="footer-last">
        <div class="footer-msg left">
          <div class="footer-logo"></div>
          <p>O Arquigrafia conta com um total de {{ $count }} fotos.<br />
          <?php if (!Auth::check()) { ?>
            <a href="{{ URL::to("/users/login") }}">Faça o login</a> e compartilhe também suas imagens.
          <?php } else { ?>
            Compartilhe também suas imagens.
          <?php } ?>
          </p>
        </div>

        <p id="copyright">Arquigrafia - {{ date("Y") }} - Arquigrafia é uma marca registrada (INPI). Este site possui uma licença <a href="http://creativecommons.org/licenses/by/3.0/deed.pt_BR" target="_blank">Creative Commons Attribution 3.0</a></p>

      </div>

    </div>

	</div>
  <!--   FIM - FUNDO DO SITE   -->
