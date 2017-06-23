<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use \DOMDocument as DOMDocument;

    class PageHeader {
        private $dom_document;
        private $dom_element;
        private $title;
        private $small_title;
        private $container_class;
        private $container_style;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $encoding = $util->contains($kwargs,'encoding')->getString('UTF-8');
            $this->setEncoding($encoding);

            $container_class = $util->contains($kwargs,'container_class')->getString();
            $this->setContainerClass($container_class);

            $title = $util->contains($kwargs,'title')->getString();
            $this->setTitle($title);

            $small_title = $util->contains($kwargs,'small_title')->getString();
            $this->setSmallTitle($small_title);

            $container_style = $util->contains($kwargs,'container_style')->getString();
            $this->setContainerStyle($container_style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('div');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','page-header');
            }

            if (isset($kwargs['style']) && !empty($kwargs['style'])) {
                $dom_element->setAttribute('style',$kwargs['style']);
            }

            $this->setDomElement($dom_element);
            $this->ready();

            return $this;
        }

        private function getDomDocument() {
            return $this->dom_document;
        }

        private function setDomDocument($dom_document) {
            $this->dom_document = $dom_document;
        }

        public function getEncoding() {
            return $this->encoding;
        }

        public function setEncoding($encoding) {
            $this->encoding = $encoding;

            return $this;
        }

        private function getContainerClass() {
            return $this->container_class;
        }

        private function setContainerClass($container_class) {
            $this->container_class = $container_class;
        }

        private function getContainerStyle() {
            return $this->container_style;
        }

        private function setContainerStyle($container_style) {
            $this->container_style = $container_style;
        }

        private function getTitle() {
            return $this->title;
        }

        private function setTitle($title) {
            $this->title = $title;
        }

        private function getSmallTitle() {
            return $this->small_title;
        }

        private function setSmallTitle($small_title) {
            $this->small_title = $small_title;
        }

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function addContainer() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
            $container_style = $this->getContainerStyle();

            $div_class_col = $dom_document->createElement('div');
            $div_class_col->setAttribute('class',$container_class);
            $div_class_col->setAttribute('style',$container_style);

            $div_class_col->appendChild($dom_element);

            return $div_class_col;
        }

        private function ready() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $small_title = $this->getSmallTitle();

            $h1 = $dom_document->createElement('h1',$title);
            $small = $dom_document->createElement('small',$small_title);

            $h1->appendChild($small);
            $dom_element->appendChild($h1);

            $add_container = $this->addContainer();
            $this->setDomElement($add_container);
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
