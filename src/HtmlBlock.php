<?php
declare(strict_types=1);

namespace HtmlBlock {
    use Core\Util;
    use HtmlBlock\Interface\HtmlBlock as InterfaceHtmlBlock;
    use HtmlBlock\Exception\HtmlBlockException as ExceptionHtmlBlock;
    use \DOMDocument as DOMDocument;
    use \DOMElement as DOMElement;
    use \DOMNode as DOMNode;

    class HtmlBlock implements InterfaceHtmlBlock {
        const ENCODING = 'UTF-8';
        const DOCTYPE = '<!DOCTYPE html>';

        private $dom_document;
        private $node_document;
        private $node_head;
        private $node_head_title;
        private $node_body;
        private $node_body_div_container_row_top;
        private $node_body_div_container_row_main;
        private $node_body_div_container;

        public function __construct(array ...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $dom_document = new DOMDocument('1.0',self::ENCODING);

            $this->setDomDocument($dom_document);
            $this->loadSkeletonHtml();

            return $this;
        }

        public function getDomDocument(): DOMDocument {
            return $this->dom_document;
        }

        public function setDomDocument(DOMDocument $dom_document): self {
            $this->dom_document = $dom_document;

            return $this;
        }

        public function getNodeDocument(): DOMNode {
            return $this->node_document;
        }

        public function setNodeDocument(DOMNode $node_document): self {
            $this->node_document = $node_document;

            return $this;
        }

        public function getNodeHead(): DOMNode {
            return $this->node_head;
        }

        public function setNodeHead(DOMNode $node_head): self {
            $this->node_head = $node_head;

            return $this;
        }

        public function getNodeHeadTitle(): DOMNode {
            return $this->node_head_title;
        }

        public function setNodeHeadTitle(DOMNode $node_head_title): self {
            $this->node_head_title = $node_head_title;

            return $this;
        }

        public function setHeadTitle(string $head_title_content): self {
            $node_head_title = $this->getNodeHeadTitle();
            $node_head_title->textContent = $head_title_content;

            return $this;
        }

        public function getNodeBody(): DOMNode {
            return $this->node_body;
        }

        public function setNodeBody(DOMNode $node_body): self {
            $this->node_body = $node_body;

            return $this;
        }

        public function getNodeBodyDivContainer(): DOMNode {
            return $this->node_body_div_container;
        }

        public function setNodeBodyDivContainer(DOMNode $node_body_div_container): self {
            $this->node_body_div_container = $node_body_div_container;

            return $this;
        }

        public function getNodeBodyDivContainerRowTop(): DOMNode {
            return $this->node_body_div_container_row_top;
        }

        public function setNodeBodyDivContainerRowTop(DOMNode $node_body_div_container_row_top): self {
            $this->node_body_div_container_row_top = $node_body_div_container_row_top;

            return $this;
        }

        public function getNodeBodyDivContainerRowMain(): DOMNode {
            return $this->node_body_div_container_row_main;
        }

        public function setNodeBodyDivContainerRowMain(DOMNode $node_body_div_container_row_main): self {
            $this->node_body_div_container_row_main = $node_body_div_container_row_main;

            return $this;
        }

        public function loadSkeletonHtml(): self {
            $dom_document = $this->getDomDocument();

            $dom_head = $dom_document->createElement('head');
            $dom_head_title = $dom_document->createElement('head');

            $node_head_title = $dom_head->appendChild($dom_head_title);
            $this->setNodeHeadTitle($node_head_title);

            $dom_html = $dom_document->createElement('html');

            $node_head = $dom_html->appendChild($dom_head);
            $this->setNodeHead($node_head);

            $dom_body = $dom_document->createElement('body');

            $node_body = $dom_html->appendChild($dom_body);
            $this->setNodeBody($node_body);

            $node_document = $dom_document->appendChild($dom_html);
            $this->setNodeDocument($node_document);

            $dom_div_container_fluid = $dom_document->createElement('div');
            $dom_div_container_fluid->setAttribute('class','container-fluid');

            $node_body_div_container = $node_body->appendChild($dom_div_container_fluid);
            $this->setNodeBodyDivContainer($node_body_div_container);

            $dom_div_row = $dom_document->createElement('div');
            $dom_div_row->setAttribute('class','row');

            $node_body_div_container_row_top = $dom_div_container_fluid->appendChild($dom_div_row);
            $this->setNodeBodyDivContainerRowTop($node_body_div_container_row_top);

            $dom_div_row = $dom_document->createElement('div');
            $dom_div_row->setAttribute('class','row');

            $node_body_div_container_row_main = $dom_div_container_fluid->appendChild($dom_div_row);
            $this->setNodeBodyDivContainerRowMain($node_body_div_container_row_main);

            return $this;
        }

        public function addCss(string $url,string $media = 'all'): self {
            $dom_document = $this->getDomDocument();

            $dom_link = $dom_document->createElement('link');
            $dom_link->setAttribute('rel','stylesheet');
            $dom_link->setAttribute('href',$url);
            $dom_link->setAttribute('media',$media);

            $node_head = $this->getNodeHead();
            $node_head->appendChild($dom_link);

            return $this;
        }

        public function addJs(string $url): self {
            $dom_document = $this->getDomDocument();

            $dom_script = $dom_document->createElement('script');
            $dom_script->setAttribute('src',$url);

            $node_head = $this->getNodeHead();
            $node_head->appendChild($dom_script);

            return $this;
        }

        public function addMeta(array $attribute_list): self {
            $dom_document = $this->getDomDocument();

            $dom_meta = $dom_document->createElement('meta');

            foreach ($attribute_list as $key => $value) {
                $dom_meta->setAttribute($key,$value);
            }

            $node_head = $this->getNodeHead();
            $node_head->appendChild($dom_meta);

            return $this;
        }

        public function appendBodyTop(DOMNode $dom_node): self {
            $dom_document = $this->getDomDocument();

            $node_import = $dom_document->importNode($dom_node->getDomElement(),true);

            $node_body_div_container_row_top = $this->getNodeBodyDivContainerRowTop();
            $node_body_div_container_row_top->appendChild($node_import);

            return $this;
        }

        public function appendBodyMain(DOMNode $dom_node): self {
            $dom_document = $this->getDomDocument();

            $node = $dom_document->importNode($dom_node->getDomElement(),true);

            $node_body_div_container_row_main = $this->getNodeBodyDivContainerRowMain();
            $node_body_div_container_row_main->appendChild($node);

            return $this;
        }

        public function renderHtml(): string {
            $dom_document = $this->getDomDocument();
            $doc_type = self::DOCTYPE;

            return $doc_type.$dom_document->saveHTML();
        }
    }
}
