<?php

namespace Component\HtmlBlock {
    use Core\Util;
    use Core\Exception\WException;
    use \DOMDocument as DOMDocument;

    class Alert {
        private $dom_document;
        private $dom_element;
        private $model;
        private $container_class;
        private $container_style;

        public function __construct(...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $encoding = $util->contains($kwargs,'encoding')->getString('UTF-8');
            $this->setEncoding($encoding);

            $model = $util->contains($kwargs,'model')->getArray();
            $this->setModel($model);

            $container_class = $util->contains($kwargs,'container_class')->getString();
            $this->setContainerClass($container_class);
 
            $container_style = $util->contains($kwargs,'container_style')->getString();
            $this->setContainerStyle($container_style);

            $dom_document = new DOMDocument(null,$encoding);

            $this->setDomDocument($dom_document);

            $dom_element = $dom_document->createElement('div');
            $dom_element->setAttribute('role','alert');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);

            } else {
                $dom_element->setAttribute('class','alert alert-info alert-dismissible');
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

        public function getDomElement() {
            return $this->dom_element;
        }

        private function setDomElement($dom_element) {
            $this->dom_element = $dom_element;
        }

        private function getModel() {
            return $this->model;
        }

        private function setModel($model) {
            $this->model = $model;
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
 
            $this->setDomElement($div_class_col);
        }

        private function ready() {
            $util = new Util;

            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();

            if (empty($model) || !is_array($model)) {
                $this->setDomElement(null);

                return false;
            }

            foreach ($model as $model_item) {
                $message = $util->contains($model_item,'message')->getString();
                $type = $util->contains($model_item,'type')->getString();

                $button = $dom_document->createElement('button');
                $button->setAttribute('type','button');
                $button->setAttribute('class','close');
                $button->setAttribute('data-dismiss','alert');
                $button->setAttribute('aria-label','Close');

                // $span = $dom_document->createElement('span','&times;');
                // $span->setAttribute('aria-hidden','true');

                // $button->appendChild($span);

                $p = $dom_document->createElement('p',$message);

                if (!empty($type)) {
                    $dom_element->setAttribute('class',vsprintf('alert alert-%s alert-dismissible',[$type]));
                }

                $dom_element->appendChild($button);
                $dom_element->appendChild($p);
            }

            $this->addContainer();
        }

        public function renderHtml() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();

            if (!empty($dom_element)) {
                $node = $dom_document->importNode($dom_element,true);
            }

            return $node->saveHTML();
        }
    }
}
