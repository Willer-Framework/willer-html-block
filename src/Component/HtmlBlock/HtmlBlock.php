<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use Core\Exception\WException;
    use \DOMDocument as DOMDocument;
    use \DOMElement as DOMElement;

    class HtmlBlock {
        private $dom_document;
        private $node_document;
        private $node_head;
        private $node_head_title;
        private $node_body;
        private $node_body_div_container_row;
        private $node_body_div_container;
        private $encoding;
        private $doc_type;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $encoding = $util->contains($kwargs,'encoding')->getString('UTF-8');
            $this->setEncoding($encoding);

            $doc_type = $util->contains($kwargs,'doc_type')->getString('<!DOCTYPE html>');
            $this->setDocType($doc_type);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $this->createHtmlElement();

            $node_body = $this->getNodeBody();

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $node_body->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $node_body->setAttribute('class',$kwargs['class']);
            }

            if (isset($kwargs['style']) && !empty($kwargs['style'])) {
                $node_body->setAttribute('style',$kwargs['style']);
            }

            return $this;
        }

        public function getDomDocument() {
            return $this->dom_document;
        }

        public function setDomDocument($dom_document) {
            $this->dom_document = $dom_document;

            return $this;
        }

        public function getNodeDocument() {
            return $this->node_document;
        }

        public function setNodeDocument($node_document) {
            $this->node_document = $node_document;

            return $this;
        }

        public function getEncoding() {
            return $this->encoding;
        }

        public function setEncoding($encoding) {
            $this->encoding = $encoding;

            return $this;
        }

        public function getDocType() {
            return $this->doc_type;
        }

        public function setDocType($doc_type) {
            $this->doc_type = $doc_type;

            return $this;
        }

        public function getNodeHead() {
            return $this->node_head;
        }

        public function setNodeHead($node_head) {
            $this->node_head = $node_head;

            return $this;
        }

        public function getNodeHeadTitle() {
            return $this->node_head_title;
        }

        public function setNodeHeadTitle($node_head_title) {
            $this->node_head_title = $node_head_title;

            return $this;
        }

        public function setHeadTitle($head_title_content) {
            $node_head_title = $this->getNodeHeadTitle();
            $node_head_title->textContent = $head_title_content;

            return $this;
        }

        public function getNodeBody() {
            return $this->node_body;
        }

        public function setNodeBody($node_body) {
            $this->node_body = $node_body;

            return $this;
        }

        public function getNodeBodyDivContainer() {
            return $this->node_body_div_container;
        }

        public function setNodeBodyDivContainer($node_body_div_container) {
            $this->node_body_div_container = $node_body_div_container;
        }

        public function getNodeBodyDivContainerRow() {
            return $this->node_body_div_container_row;
        }

        public function setNodeBodyDivContainerRow($node_body_div_container_row) {
            $this->node_body_div_container_row = $node_body_div_container_row;
        }

        public function createHtmlElement() {
            $dom_document = $this->getDomDocument();

            $dom_head = $this->createDom('head');
            $dom_head_title = $this->createDom('title');

            $node_head_title = $dom_head->appendChild($dom_head_title);
            $this->setNodeHeadTitle($node_head_title);

            $dom_html = $this->createDom('html');

            $node_head = $dom_html->appendChild($dom_head);
            $this->setNodeHead($node_head);

            $dom_body = $this->createDom('body');

            $node_body = $dom_html->appendChild($dom_body);
            $this->setNodeBody($node_body);

            $node_document = $dom_document->appendChild($dom_html);
            $this->setNodeDocument($node_document);

            $dom_div_row = $this->createDom('div');
            $dom_div_row->setAttribute('class','row');

            $dom_div_container_fluid = $this->createDom('div');
            $dom_div_container_fluid->setAttribute('class','container-fluid');

            $node_body_div_container_row = $dom_div_container_fluid->appendChild($dom_div_row);
            $this->setNodeBodyDivContainerRow($node_body_div_container_row);

            $node_body_div_container = $node_body->appendChild($dom_div_container_fluid);
            $this->setNodeBodyDivContainer($node_body_div_container);

            return $this;
        }

        public function addCss($url,$media = 'all') {
            $dom_link = $this->createDom('link');
            $dom_link->setAttribute('rel','stylesheet');
            $dom_link->setAttribute('href',$url);
            $dom_link->setAttribute('media',$media);

            $node_head = $this->getNodeHead();
            $node_head->appendChild($dom_link);

            return $this;
        }

        public function addJs($url) {
            $dom_script = $this->createDom('script');
            $dom_script->setAttribute('src',$url);

            $node_head = $this->getNodeHead();
            $node_head->appendChild($dom_script);

            return $this;
        }

        public function addMeta($attribute_list) {
            $dom_meta = $this->createDom('meta');

            foreach ($attribute_list as $key => $value) {
                $dom_meta->setAttribute($key,$value);
            }

            $node_head = $this->getNodeHead();
            $node_head->appendChild($dom_meta);

            return $this;
        }

        public function createDom($name,$content = null) {
            $dom_document = $this->getDomDocument();
            $dom = $dom_document->createElement($name,$content);

            return $dom;
        }

        public function appendBody($dom_node) {
            $dom_document = $this->getDomDocument();

            $node_import = $dom_document->importNode($dom_node->getDomElement(),true);

            $node_body_div_container = $this->getNodeBodyDivContainer();
            $node_body_div_container->appendChild($node_import);

            return $this;
        }

        public function appendBodyRow($dom_node_list) {
            $dom_document = $this->getDomDocument();

            if (!is_array($dom_node_list)) {
                throw new WException(vsprintf('Expected array, given %s',[gettype($dom_node_list)]));
            }

            $node_body_div_container_row = $this->getNodeBodyDivContainerRow();

            foreach ($dom_node_list as $dom_node) {
                if (!empty($dom_node) && !empty($dom_node->getDomElement())) {
                    $node = $dom_document->importNode($dom_node->getDomElement(),true);

                    $node_body_div_container_row->appendChild($node);
                }
            }

            return $this;
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();
            $doc_type = $this->getDocType();

            return $doc_type.$dom_document->saveHTML();
        }
    }
}
