<?php

require __DIR__ . '/../vendor/autoload.php';

use ElementaryFramework\WaterPipe\WaterPipe;
use ElementaryFramework\WaterPipe\HTTP\Request\Request;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;
use ElementaryFramework\WaterPipe\HTTP\Response\ResponseStatus;
use ElementaryFramework\WaterPipe\HTTP\Response\ResponseHeader;
use Pixelbrackets\Html5MiniTemplate\Html5MiniTemplate;

$root = new WaterPipe;

// homepage
// http GET example.com/
$root->get('/', function (Request $req, Response $res) {
    $template = new Html5MiniTemplate();
    $template->setStylesheet('skeleton');
    $template->setStylesheetMode(Html5MiniTemplate::STYLE_INLINE);
    $content = '<h1>\(^_^)/ Mattermost Kaomoji Integration</h1>
      <p>
          1. Create a »Slash Command« in your Mattermost instance<br>
          2. Set »###BASEURL###hook/«
             as »Request URL«<br>
          3. Select a command trigger word, for example »kaomoji«<br>
          4. Type <code>/kaomoji</code> to trigger the command<br>
      </p>';
    $content = str_replace(
        '###BASEURL###',
        empty(getenv('BASEURL'))? 'https://example.com/' : getenv('BASEURL'),
        $content
    );
    $template->setContent($content);
    $res->sendHtml($template->getMarkup());
});

// hook endpoint
// http POST example.com/hook command="/kaomoji" text=""
$root->post('/hook', function (Request $req, Response $res) {
    $data = $req->getBody(); // Mattermost Request is x-www-form-urlencoded

    $kaomoji = '    \(^_^)/';

    $res->sendJson([
        'response_type' => 'ephemeral',
        'text' => $kaomoji
    ]);
});

$root->run();
