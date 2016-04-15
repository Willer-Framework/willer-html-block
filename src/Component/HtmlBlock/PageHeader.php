<?php

namespace Core\Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;

    class PageHeader {
        private $html_block;
        private $dom_element;
        private $title;
        private $small_title;
        private $container_class;
        private $container_style;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

            $small_title = Util::get($kwargs,'small_title',null);
            $this->setSmallTitle($small_title);

            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);

            $dom_element = $html_block->createElement('div');

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

        private function getHtmlBlock() {
            return $this->html_block;
        }

        private function setHtmlBlock($html_block) {
            $this->html_block = $html_block;
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
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
            $container_style = $this->getContainerStyle();

            $div_class_col = $html_block->createElement('div');
            $div_class_col->setAttribute('class',$container_class);
            $div_class_col->setAttribute('style',$container_style);

            $div_class_col->appendChild($dom_element);

            return $div_class_col;
        }

        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $small_title = $this->getSmallTitle();

            $h1 = $html_block->createElement('h1',$title);
            $small = $html_block->createElement('small',$small_title);

            $h1->appendChild($small);
            $dom_element->appendChild($h1);

            $add_container = $this->addContainer();
            $this->setDomElement($add_container);
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();

            $html_block->appendBodyContainerRow($this);

            return $html_block->renderHtml();
        }
    }
}
