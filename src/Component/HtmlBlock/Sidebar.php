<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use \DOMDocument as DOMDocument;

    class Sidebar {
        private $dom_document;
        private $dom_element;
        private $model;
        private $title;
        private $text;
        private $footer;
        private $container_class;
        private $container_style;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $model = $util->contains($kwargs,'model')->getArray();
            $this->setModel($model);

            $title = $util->contains($kwargs,'title')->getString();
            $this->setTitle($title);

            $text = $util->contains($kwargs,'text')->getString();
            $this->setText($text);

            $footer = $util->contains($kwargs,'footer')->getString();
            $this->setFooter($footer);

            $container_class = $util->contains($kwargs,'container_class')->getString();
            $this->setContainerClass($container_class);
 
            $container_style = $util->contains($kwargs,'container_style')->getString();
            $this->setContainerStyle($container_style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('ul');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','nav nav-pills nav-stacked');
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

        private function getModel() {
            return $this->model;
        }

        private function setModel($model) {
            $this->model = $model;
        }

        private function getTitle() {
            return $this->title;
        }

        private function setTitle($title) {
            $this->title = $title;
        }

        private function getText() {
            return $this->text;
        }

        private function setText($text) {
            $this->text = $text;
        }

        private function getFooter() {
            return $this->footer;
        }

        private function setFooter($footer) {
            $this->footer = $footer;
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

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function addPanel() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $text = $this->getText();
            $footer = $this->getFooter();
 
            if (empty($title) && empty($text) && empty($footer)) {
                return false;
            }
 
            $div_class_panel = $dom_document->createElement('div');
            $div_class_panel->setAttribute('class','panel panel-default');
 
            if (!empty($title)) {
                $div_class_panel_head = $dom_document->createElement('div',$title);
                $div_class_panel_head->setAttribute('class','panel-heading');
                $node_div_panel_head = $div_class_panel->appendChild($div_class_panel_head);
            }
 
            $div_class_panel_body = $dom_document->createElement('div');

            if (!empty($text)) {
                $p_text = $dom_document->createElement('p',$text);
                $div_class_panel_body->appendChild($p_text);
            }

            $div_class_panel_body->setAttribute('class','panel-body');
            $node_div_panel_body = $div_class_panel->appendChild($div_class_panel_body);
            $node_div_panel_body->appendChild($dom_element);
 
            if (!empty($footer)) {
                $div_class_panel_footer = $dom_document->createElement('div',$footer);
                $div_class_panel_footer->setAttribute('class','panel-footer');
                $node_div_panel_footer = $div_class_panel->appendChild($div_class_panel_footer);
            }
 
            $this->setDomElement($div_class_panel);
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
 
            $this->setDomElement($div_class_col);
        }

        private function ready() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            foreach ($model as $name => $route) {
                $li_menu = $dom_document->createElement('li');

                $li_a_menu = $dom_document->createElement('a',$name);
                $li_a_menu->setAttribute('href',$route);

                $li_menu->appendChild($li_a_menu);

                $dom_element->appendChild($li_menu);
            }

            $this->addPanel();
            $this->addContainer();
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
