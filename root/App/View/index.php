<script>hljs.initHighlightingOnLoad();</script>

<header>
  <div class="container">
    <div class="left">
      <div class="logo">
        Clover
      </div>
      <nav>
        <ul>
          <li>문서</li>
        </ul>
      </nav>
    </div>
  </div>
</header>

<section class="base-information">
  <div class="container">
    <div class="left">
      <h1 class="title">
          MVC 풀스택 PHP 프레임워크
      </h1>
      <div class="description">
        <div class="first">
          Clover Framework는 PHP의 데이터 타입을 객체 형태로 수정하여 PHP의 함수를 사용하지 않고도 기능을 구현할 수 있습니다.
          <br/>
          <br/>
          또한 Router, Dependency Injection(DI)과 같은 클래스를 기본적으로 제공하여 빠르게 사이트를 구현할 수 있습니다.
        </div>

        <hr class="divider">

        <div class="second">
          Clover Framework의 CoreJavascript를 통하여 위치정보 확인, 오디오 플레이어, 오디오 녹음, 브라우저 알림, 웹DB, TTS, 팝업 등의 기능을 간략한 코드를 통하여 구현할 수 있습니다.
        </div>
    </div>
  </div>
</section>
<div class="features">
  <nav>
      <div class="container container--short">
          <ul class="list">
              <li class="active">
                  <span>
                      백엔드
                  </span>
                  <hr>
              </li>
              <li>
                  <span>
                      뷰 &amp; 프론트엔드
                  </span>
                  <hr>
              </li>
          </ul>
      </div>
  </nav>

  <div class="container container--short">
    <div class="content">
      <div class="feature active">
        <div class="title">
          의존성 주입
        </div>

        <pre class="php hljs "><code>
&lt;?xml version=&quot;1.0&quot; ?&gt;
&lt;container&gt;
    &lt;services&gt;
        &lt;service key=&quot;Renderer&quot; class=&quot;Clover\Framework\Component\Renderer&quot;/&gt;
        &lt;service key=&quot;Resource&quot; class=&quot;Clover\Framework\Component\Resource&quot;/&gt;
        &lt;service key=&quot;Logger&quot; class=&quot;Clover\Classes\Logging\Logger&quot;/&gt;
    &lt;/services&gt;
&lt;/container&gt;</code></pre>

        <div class="description">
          Clover Framework는 의존성 주입을 통하여 기존의 PHP 파일 단위의 기능 개발 형식을 벗어난 효율적인 패턴을 도입하였습니다.
        </div>
        
        <div class="title">
          MVC
        </div>

        <div class="description">
          익숙한 MVC 패턴과 Annotation을 통한 라우트 경로 설정, Middleware 같은 기능을 통하여 편리하게 어플리케이션을 제작하세요.
        </div>
        
        <pre class="php hljs "><code>
class IndexController extends BaseController
{
	#[Annotation\Route(method: &#39;GET&#39;, pattern: &#39;/&#39;)]
	public function index()
	{
		return $this-&gt;render(&#39;/App/View/index.php&#39;);
	}
}</code></pre>

        <div class="title">
          CoreJavascript
        </div>

        <div class="description">
          CoreJavascript에는 EventDispatcher를 통한 컴포넌트가 기본적으로 제공됩니다.
          <br/>
          <br/>
          AudioRecorder, AudioPlayer, Clipboard, FileService, GamePad, MediaPlayer, Pagination, ScreenService, NotificationService, EventService, WebsocketService, RequestService, MediaSessionService, OpenGL 등등 다양한 기능을 제공합니다.
        </div>

        <pre class="php hljs "><code>
const mediaPlayer = new MediaPlayer();
mediaPlayer.setContext(document.getElementById(&quot;player&quot;));
mediaPlayer.setEvents();
mediaPlayer.setVisualizerStyle(&quot;circular&quot;);
mediaPlayer.setSpectrum(&quot;#spectrum&quot;);

const onClickEvent = function () {
  mediaPlayer.connectPanEffector();
  mediaPlayer.setParseFrequencyTimeout(1000 / 35);
  mediaPlayer.play();
};
document.getElementById(&quot;play&quot;).addEventListener(&quot;click&quot;, onClickEvent);</code></pre>
      </div>
    </div>
  </div>
</div>

<footer>
    <div class="container">
        <ul class="footer-nav">
            <li><a></a></li>
        </ul>

        <div class="footer__license">
            
        </div>
    </div>
</footer>