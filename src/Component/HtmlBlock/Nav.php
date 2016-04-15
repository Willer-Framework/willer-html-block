<?php

namespace Core\Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;

    class Nav {
        private $html_block;
        private $dom_element;
        private $model;
        private $title;
        private $node_div_nav_header;
        private $node_div_nav_body;
        private $node_ul_nav_body;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

            $dom_element = $html_block->createElement('nav');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','navbar navbar-inverse navbar-fixed-top');
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

        private function getModel() {
            return $this->model;
        }

        private function setModel($model) {
            $this->model = $model;
        }

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function getTitle() {
            return $this->title;
        }

        private function setTitle($title) {
            $this->title = $title;
        }

        private function getNodeDivContainer() {
            return $this->node_div_container;
        }

        private function setNodeDivContainer($node_div_container) {
            $this->node_div_container = $node_div_container;
        }

        public function getNodeDivNavHeader() {
            return $this->node_div_nav_header;
        }

        private function setNodeDivNavHeader($node_div_nav_header) {
            $this->node_div_nav_header = $node_div_nav_header;
        }

        public function getNodeDivNavBody() {
            return $this->node_div_nav_body;
        }

        private function setNodeDivNavBody($node_div_nav_body) {
            $this->node_div_nav_body = $node_div_nav_body;
        }

        public function getNodeUlNavBody() {
            return $this->node_ul_nav_body;
        }

        private function setNodeUlNavBody($node_ul_nav_body) {
            $this->node_ul_nav_body = $node_ul_nav_body;
        }

        private function setNavHeadTitle() {
            $html_block = $this->getHtmlBlock();
            $node_div_nav_header = $this->getNodeDivNavHeader();
            $title = $this->getTitle();

            if (!empty($title)) {
                $a_title = $html_block->createElement('a',$title);
                $a_title->setAttribute('class','navbar-brand');
                $node_div_nav_header->appendChild($a_title);
            }
        }

        private function setNavBodyMenu() {
            $html_block = $this->getHtmlBlock();
            $node_ul_nav_body = $this->getNodeUlNavBody();
            $model = $this->getModel();

            if (empty($model) || !is_array($model)) {
                return false;
            }

            foreach ($model as $name => $route) {
                $li_menu = $html_block->createElement('li');

                $li_a_menu = $html_block->createElement('a',$name);
                $li_a_menu->setAttribute('href',$route);

                $li_menu->appendChild($li_a_menu);

                $node_ul_nav_body->appendChild($li_menu);
            }
        }

        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();

            $div_class_container_fluid_element = $html_block->createElement('div');
            $div_class_container_fluid_element->setAttribute('class','container-fluid');
            $this->setNodeDivContainer($div_class_container_fluid_element);

            $div_class_navbar_header_element = $html_block->createElement('div');
            $div_class_navbar_header_element->setAttribute('class','navbar-header');
            $this->setNodeDivNavHeader($div_class_navbar_header_element);

            $this->setNavHeadTitle();

            $div_class_navbar_collapse_element = $html_block->createElement('div');
            $div_class_navbar_collapse_element->setAttribute('class','navbar-collapse collapse');
            $this->setNodeDivNavBody($div_class_navbar_collapse_element);

            $ul_class_navbar_element = $html_block->createElement('ul');
            $ul_class_navbar_element->setAttribute('class','nav navbar-nav navbar-right');

            $node_ul_nav_body = $div_class_navbar_collapse_element->appendChild($ul_class_navbar_element);
            $this->setNodeUlNavBody($node_ul_nav_body);

            $this->setNavBodyMenu();

            $div_class_container_fluid_element->appendChild($div_class_navbar_header_element);
            $div_class_container_fluid_element->appendChild($div_class_navbar_collapse_element);

            $dom_element->appendChild($div_class_container_fluid_element);
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();

            $html_block->appendBody($this);

            return $html_block->renderHtml();
        }
    }
}
