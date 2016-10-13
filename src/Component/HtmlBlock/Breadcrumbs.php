<?php

namespace Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;

    class Breadcrumbs {
        private $html_block;
        private $dom_element;
        private $model;
        private $container_class;
        private $container_style;

        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);

            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);

            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);

            $dom_element = $html_block->createElement('ol');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','breadcrumb');
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
            $model = $this->getModel();

            if (empty($model)) {
                return false;
            }

            foreach ($model as $model_data) {
                $href = $model_data['href'] ?? null;
                $title = $model_data['title'] ?? null;
                $active = $model_data['active'] ?? null;

                if (!empty($active)) {
                    $li_breadcrumbs = $html_block->createElement('li',$title);
                    $li_breadcrumbs->setAttribute('class','active');                    
                } else {
                    $li_a_heading = $html_block->createElement('a',$title);
                    $li_a_heading->setAttribute('href',$href);

                    $li_breadcrumbs = $html_block->createElement('li');
                    $li_breadcrumbs->appendChild($li_a_heading);

                }

                $dom_element->appendChild($li_breadcrumbs);
            }

            $add_container = $this->addContainer();
            $this->setDomElement($add_container);
        }

        public function renderHtml() {
            $html_block = $this->getHtmlBlock();

            $html_block->appendBody($this);

            return $html_block->renderHtml();
        }
    }
}
