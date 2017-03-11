<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use Core\Exception\WException;
    use \DOMDocument as DOMDocument;

    class Breadcrumbs {
        private $dom_document;
        private $dom_element;
        private $model;
        private $container_class;
        private $container_style;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $encoding = Util::get($kwargs,'encoding','UTF-8');
            $this->setEncoding($encoding);

            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);

            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);

            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('ol');

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
            $model = $this->getModel();

            if (empty($model)) {
                return false;
            }

            foreach ($model as $model_data) {
                $href = $model_data['href'] ?? null;
                $title = $model_data['title'] ?? null;
                $active = $model_data['active'] ?? null;

                if (!empty($active)) {
                    $li_breadcrumbs = $dom_document->createElement('li',$title);
                    $li_breadcrumbs->setAttribute('class','active');                    
                } else {
                    $li_a_heading = $dom_document->createElement('a',$title);
                    $li_a_heading->setAttribute('href',$href);

                    $li_breadcrumbs = $dom_document->createElement('li');
                    $li_breadcrumbs->appendChild($li_a_heading);

                }

                $dom_element->appendChild($li_breadcrumbs);
            }

            $add_container = $this->addContainer();
            $this->setDomElement($add_container);
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
