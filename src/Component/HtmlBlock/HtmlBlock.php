<?php

namespace Component\HtmlBlock {
    use \DOMDocument as DOMDocument;
    use Core\Exception\WException;
    use Core\Util;

    class HtmlBlock {
        private $dom_document;
        private $html_node_document;
        private $html_node_head;
        private $html_node_head_title;
        private $html_node_body;
        private $html_node_body_div_container;
        private $encoding;
        private $doc_type;
        private $col_md_default = 'col-md-12';

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $encoding = Util::get($kwargs,'encoding','UTF-8');
            $this->setEncoding($encoding);

            $doc_type = Util::get($kwargs,'doc_type','<!DOCTYPE html>');
            $this->setDocType($doc_type);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $this->createHtmlElement();
            $html_node_body = $this->getHtmlNodeBody();

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $html_node_body->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $html_node_body->setAttribute('class',$kwargs['class']);
            }

            if (isset($kwargs['style']) && !empty($kwargs['style'])) {
                $html_node_body->setAttribute('style',$kwargs['style']);
            }

            return $this;
        }

        public function getColMdDefault() {
            return $this->col_md_default;
        }

        public function setColMdDefault($col_md_default) {
            $this->col_md_default = $col_md_default;
        }

        public function getDomDocument() {
            return $this->dom_document;
        }

        public function setDomDocument($dom_document) {
            $this->dom_document = $dom_document;

            return $this;
        }

        public function getHtmlNodeDocument() {
            return $this->html_node_document;
        }

        public function setHtmlNodeDocument($html_node_document) {
            $this->html_node_document = $html_node_document;

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

        public function getHtmlNodeHead() {
            return $this->html_node_head;
        }

        public function setHtmlNodeHead($html_node_head) {
            $this->html_node_head = $html_node_head;

            return $this;
        }

        public function getHtmlNodeHeadTitle() {
            return $this->html_node_head_title;
        }

        public function setHtmlNodeHeadTitle($html_node_head_title) {
            $this->html_node_head_title = $html_node_head_title;

            return $this;
        }

        public function setHeadTitle($head_title_content) {
            $html_node_head_title = $this->getHtmlNodeHeadTitle();
            $html_node_head_title->textContent = $head_title_content;

            return $this;
        }

        public function getHtmlNodeBody() {
            return $this->html_node_body;
        }

        public function setHtmlNodeBody($html_node_body) {
            $this->html_node_body = $html_node_body;

            return $this;
        }

        public function getHtmlNodeBodyDivContainer() {
            return $this->html_node_body_div_container;
        }

        public function setHtmlNodeBodyDivContainer($html_node_body_div_container) {
            $this->html_node_body_div_container = $html_node_body_div_container;
        }

        public function getHtmlNodeBodyDivContainerRow() {
            return $this->html_node_body_div_container_row;
        }

        public function setHtmlNodeBodyDivContainerRow($html_node_body_div_container_row) {
            $this->html_node_body_div_container_row = $html_node_body_div_container_row;
        }

        public function addCss($url,$media = 'all') {
            $link_element = $this->createElement('link');
            $link_element->setAttribute('rel','stylesheet');
            $link_element->setAttribute('href',$url);
            $link_element->setAttribute('media',$media);

            $html_node_head = $this->getHtmlNodeHead();
            $html_node_head->appendChild($link_element);

            return $this;
        }

        public function addJs($url) {
            $script_element = $this->createElement('script');
            $script_element->setAttribute('src',$url);

            $html_node_head = $this->getHtmlNodeHead();
            $html_node_head->appendChild($script_element);

            return $this;
        }

        // public function addMetaTag($name,$content) {
        //     $element = $this->document->createElement( 'meta' );
        //     $element->setAttribute( 'name', $name );
        //     $element->setAttribute( 'content', $content );
        //     $this->metas[] = $element;
        // }

        public function createHtmlElement() {
            $dom_document = $this->getDomDocument();

            $head_element = $dom_document->createElement('head');
            $title_element = $dom_document->createElement('title');

            $html_node_head_title = $head_element->appendChild($title_element);
            $this->setHtmlNodeHeadTitle($html_node_head_title);

            $html_element = $dom_document->createElement('html');

            $html_node_head = $html_element->appendChild($head_element);
            $this->setHtmlNodeHead($html_node_head);

            $body_element = $dom_document->createElement('body');

            $html_node_body = $html_element->appendChild($body_element);
            $this->setHtmlNodeBody($html_node_body);

            $html_node_document = $dom_document->appendChild($html_element);
            $this->setHtmlNodeDocument($html_node_document);

            $div_class_row_element = $dom_document->createElement('div');
            $div_class_row_element->setAttribute('class','row');

            $div_class_container_fluid_element = $dom_document->createElement('div');
            $div_class_container_fluid_element->setAttribute('class','container-fluid');

            $html_node_container_row = $div_class_container_fluid_element->appendChild($div_class_row_element);
            $this->setHtmlNodeBodyDivContainerRow($html_node_container_row);

            $html_node_body_div_container = $html_node_body->appendChild($div_class_container_fluid_element);
            $this->setHtmlNodeBodyDivContainer($html_node_body_div_container);

            return $this;
        }

        public function createElement($name,$content = null) {
            $dom_document = $this->getDomDocument();
            $element = $dom_document->createElement($name,$content);

            return $element;
        }

        public function appendBody($element) {
            $html_node_body_div_container = $this->getHtmlNodeBodyDivContainer();
            $html_node_body_div_container->appendChild($element->getDomElement());

            return $this;
        }

        public function appendBodyRow($class = null,$component_list) {
            if (empty($class)) {
                $class = $this->getColMdDefault();
            }
 
            $div_element = $this->createElement('div');
            $div_element->setAttribute('class',$class);

            if (!is_array($component_list)) {
                throw new WException(vsprintf('Expected array, given %s',[gettype($component_list)]));
            }

            $html_node_body_div_container_row = $this->getHtmlNodeBodyDivContainerRow();

            foreach ($component_list as $component) {
                if (!empty($component->getDomElement())) {
                    $div_element->appendChild($component->getDomElement());
                }
            }

            $html_node_body_div_container_row->appendChild($div_element);

            return $this;
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();
            $doc_type = $this->getDocType();

            return $doc_type.$dom_document->saveHTML();
        }
    }
}
