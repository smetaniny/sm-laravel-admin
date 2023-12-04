<?php

namespace App\Http\Controllers\Helper;

use DOMDocument;
use DOMText;
use Exception;
use Masterminds\HTML5;
use StdClass;

class EJSParser
{
    /**
     * @var StdClass
     */
    private mixed $data;

    /**
     * @var DOMDocument
     */
    private DOMDocument $dom;

    /**
     * @var HTML5
     */
    private HTML5 $html5;

    /**
     * @var string
     */
    private string $prefix = "prs";

    public function __construct($data)
    {
        $data = json_decode(json_encode($data));
        $this->data = $data;
        $this->dom = new DOMDocument(1.0, 'UTF-8');
        $this->html5 = new HTML5([
            'target_document' => $this->dom,
            'disable_html_ns' => true
        ]);
    }

    static function parse($data)
    {
        return new self($data);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getTime()
    {
        return $this->data->time ?? null;
    }

    public function getVersion()
    {
        return $this->data->version ?? null;
    }

    public function getBlocks()
    {
        return $this->data->blocks ?? null;
    }

    /**
     * @throws Exception
     */
    public function toHtml()
    {
        $this->init();

        return $this->dom->saveHTML();
    }

    /**
     * @throws Exception
     */
    protected function init()
    {
        if (!$this->hasBlocks()) throw new Exception('No Blocks to parse !');
        foreach ($this->data->blocks as $block) {
            switch ($block->type) {
                case 'header':
                    $this->parseHeader($block);
                    break;
                case 'delimiter':
                    $this->parseDelimiter();
                    break;
                case 'code':
                    $this->parseCode($block);
                    break;
                case 'paragraph':
                    $this->parseParagraph($block);
                    break;
                case 'image':
                    $this->Image($block);
                    break;
                case 'link':
                    $this->parseLink($block);
                    break;
                case 'embed':
                    $this->parseEmbed($block);
                    break;
                case 'raw':
                    $this->parseRaw($block);
                    break;
                case 'list':
                    $this->parseList($block);
                    break;
                case 'warning':
                    $this->parseWarning($block);
                    break;
                case 'simpleImage':
                    $this->parseImage($block);
                    break;
                case 'table':
                    $this->parseTable($block);
                    break;
                case 'quote':
                    $this->parseQuote($block);
                    break;
                default:
                    break;
            }
        }
    }

    private function hasBlocks()
    {
        return count($this->data->blocks) !== 0;
    }

    /**
     * @throws \DOMException
     */
    private function parseHeader($block)
    {
        $text = new DOMText($block->data->text);

        $header = $this->dom->createElement('h' . $block->data->level);

        $header->setAttribute('class', "{$this->prefix}-h{$block->data->level}");

        $header->appendChild($text);

        $this->dom->appendChild($header);
    }

    /**
     * @throws \DOMException
     */
    private function parseDelimiter()
    {
        $node = $this->dom->createElement('hr');

        $node->setAttribute('class', "{$this->prefix}-delimiter");

        $this->dom->appendChild($node);
    }

    /**
     * @throws \DOMException
     */
    private function parseCode($block)
    {
        $wrapper = $this->dom->createElement('div');

        $wrapper->setAttribute('class', "{$this->prefix}-code");

        $pre = $this->dom->createElement('pre');

        $code = $this->dom->createElement('code');

        $content = new DOMText($block->data->code);

        $code->appendChild($content);

        $pre->appendChild($code);

        $wrapper->appendChild($pre);

        $this->dom->appendChild($wrapper);
    }

    /**
     * @throws \DOMException
     */
    private function parseParagraph($block)
    {
        $node = $this->dom->createElement('p');

        $node->setAttribute('class', "{$this->prefix}-paragraph");

        $node->appendChild($this->html5->loadHTMLFragment($block->data->text));

        $this->dom->appendChild($node);
    }

    /**
     * @throws \DOMException
     */
    private function parseTable($block)
    {
        $table = $this->dom->createElement('table');
        $table->setAttribute('class', "{$this->prefix}-table");

        $tbody = $this->dom->createElement('tbody');
        $tbody->setAttribute('class', "{$this->prefix}-tbody");
        $table->appendChild($tbody);

        foreach ($block->data->content as $trElement) {
            $tr = $this->dom->createElement('tr');
            foreach ($trElement as $tdElement) {
                $td = $this->dom->createElement('td');
                $td->appendChild($this->html5->loadHTMLFragment($tdElement));
                $tr->appendChild($td);
            }
            $tbody->appendChild($tr);
        }

        $this->dom->appendChild($table);
    }

    /**
     * @throws \DOMException
     */
    private function parseQuote($block)
    {
        // Создаем элемент цитаты (например, <blockquote>)
        $quoteElement = $this->dom->createElement('blockquote');

        // Получаем текст цитаты из данных блока
        $quoteText = $block->data->text;

        // Создаем текстовый узел с цитатой и добавляем его в элемент цитаты
        $quoteTextNode = $this->dom->createTextNode($quoteText);
        $quoteElement->appendChild($quoteTextNode);

        // Добавляем элемент цитаты в вашу структуру DOM, например, в основной DOM-элемент

        // Например:
        $this->dom->appendChild($quoteElement);
    }


    /**
     * @throws \DOMException
     */
    private function Image($block)
    {
        $picture = $this->dom->createElement('picture');

        $url = str_replace(['jpg', 'jpeg', 'png'], "webp", $block->data->file->url);

        $img = $this->dom->createElement('img');
        $img->setAttribute('src', $block->data->file->url);
        $img->setAttribute('class', "{$this->prefix}-image");
        $img->setAttribute('alt', $block->data->caption);
        $img->setAttribute('title', $block->data->caption);

        $source = $this->dom->createElement('source');
        $source->setAttribute('type', 'image/webp');
        $source->setAttribute('srcset', $url);
        $source->setAttribute('class', "{$this->prefix}-image");

        $picture->appendChild($source);
        $picture->appendChild($img);

        $this->dom->appendChild($picture);
    }

    /**
     * @throws \DOMException
     */
    private function parseLink($block)
    {
        $link = $this->dom->createElement('a');

        $link->setAttribute('href', $block->data->link);
        $link->setAttribute('target', '_blank');
        $link->setAttribute('class', "{$this->prefix}-link");

        $innerContainer = $this->dom->createElement('div');
        $innerContainer->setAttribute('class', "{$this->prefix}-link-container");

        $hasTitle = isset($block->data->meta->title);
        $hasDescription = isset($block->data->meta->description);
        $hasImage = isset($block->data->meta->image);

        if ($hasTitle) {
            $titleNode = $this->dom->createElement('div');
            $titleNode->setAttribute('class', "{$this->prefix}-link-title");
            $titleText = new DOMText($block->data->meta->title);
            $titleNode->appendChild($titleText);
            $innerContainer->appendChild($titleNode);
        }

        if ($hasDescription) {
            $descriptionNode = $this->dom->createElement('div');
            $descriptionNode->setAttribute('class', "{$this->prefix}-link-description");
            $descriptionText = new DOMText($block->data->meta->description);
            $descriptionNode->appendChild($descriptionText);
            $innerContainer->appendChild($descriptionNode);
        }

        $linkContainer = $this->dom->createElement('div');
        $linkContainer->setAttribute('class', "{$this->prefix}-link-url");
        $linkText = new DOMText($block->data->link);
        $linkContainer->appendChild($linkText);
        $innerContainer->appendChild($linkContainer);

        $link->appendChild($innerContainer);

        if ($hasImage) {
            $imageContainer = $this->dom->createElement('div');
            $imageContainer->setAttribute('class', "{$this->prefix}-link-img-container");
            $image = $this->dom->createElement('img');
            $image->setAttribute('src', $block->data->meta->image->url);
            $imageContainer->appendChild($image);
            $link->appendChild($imageContainer);
            $innerContainer->setAttribute('class', "{$this->prefix}-link-container-with-img");
        }

        $this->dom->appendChild($link);
    }

    /**
     * @throws \DOMException
     */
    private function parseEmbed($block)
    {
        $wrapper = $this->dom->createElement('div');

        $wrapper->setAttribute('class', "{$this->prefix}-embed");

        switch ($block->data->service) {
            case 'youtube':

                $attrs = [
                    'height' => $block->data->height,
                    'src' => $block->data->embed,
                    'allow' => 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
                    'allowfullscreen' => true
                ];

                $wrapper->appendChild($this->createIframe($attrs));

                break;
            case 'codepen' || 'gfycat':

                $attrs = [
                    'height' => $block->data->height,
                    'src' => $block->data->embed,
                ];

                $wrapper->appendChild($this->createIframe($attrs));

                break;
        }

        $this->dom->appendChild($wrapper);
    }

    /**
     * @throws \DOMException
     */
    private function createIframe(array $attrs)
    {
        $iframe = $this->dom->createElement('iframe');
        foreach ($attrs as $key => $attr) $iframe->setAttribute($key, $attr);
        return $iframe;
    }

    /**
     * @throws \DOMException
     */
    private function parseRaw($block)
    {
        $wrapper = $this->dom->createElement('div');
        $wrapper->setAttribute('class', "{$this->prefix}-raw");
        $wrapper->appendChild($this->html5->loadHTMLFragment($block->data->html));
        $this->dom->appendChild($wrapper);
    }

    /**
     * @throws \DOMException
     */
    private function parseList($block)
    {
        $wrapper = $this->dom->createElement('div');
        $wrapper->setAttribute('class', "{$this->prefix}-list");
        $list = match ($block->data->style) {
            'ordered' => $this->dom->createElement('ol'),
            default => $this->dom->createElement('ul'),
        };

        foreach ($block->data->items as $item) {
            $li = $this->dom->createElement('li');
            $li->appendChild($this->html5->loadHTMLFragment($item));
            $list->appendChild($li);
        }

        $wrapper->appendChild($list);
        $this->dom->appendChild($wrapper);
    }

    /**
     * @throws \DOMException
     */
    private function parseWarning($block)
    {
        $title = new DOMText($block->data->title);
        $message = new DOMText($block->data->message);

        $wrapper = $this->dom->createElement('div');
        $wrapper->setAttribute('class', "{$this->prefix}-warning");

        $textWrapper = $this->dom->createElement('div');
        $titleWrapper = $this->dom->createElement('p');

        $titleWrapper->appendChild($title);
        $messageWrapper = $this->dom->createElement('p');

        $messageWrapper->appendChild($message);

        $textWrapper->appendChild($titleWrapper);
        $textWrapper->appendChild($messageWrapper);

        $icon = $this->dom->createElement('ion-icon');
        $icon->setAttribute('name', 'information-outline');
        $icon->setAttribute('size', 'large');

        $wrapper->appendChild($icon);
        $wrapper->appendChild($textWrapper);

        $this->dom->appendChild($wrapper);
    }

    /**
     * @throws \DOMException
     */
    private function parseImage($block)
    {
        $figure = $this->dom->createElement('figure');
        $figure->setAttribute('class', "{$this->prefix}-image");
        $img = $this->dom->createElement('img');

        $imgAttrs = [];

        if ($block->data->withBorder) $imgAttrs[] = "{$this->prefix}-image-border";
        if ($block->data->withBackground) $imgAttrs[] = "{$this->prefix}-image-background";
        if ($block->data->stretched) $imgAttrs[] = "{$this->prefix}-image-stretched";

        $img->setAttribute('src', $block->data->url);
        $img->setAttribute('class', implode(' ', $imgAttrs));

        $figCaption = $this->dom->createElement('figcaption');

        $figCaption->appendChild($this->html5->loadHTMLFragment($block->data->caption));

        $figure->appendChild($img);

        $figure->appendChild($figCaption);

        $this->dom->appendChild($figure);
    }
}
