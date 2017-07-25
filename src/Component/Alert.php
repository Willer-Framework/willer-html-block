<?php
declare(strict_types=1);

namespace HtmlBlock\Component {
    use Core\Util;
    use HtmlBlock\HtmlBlock;
    use HtmlBlock\Interface\HtmlBlock as InterfaceHtmlBlock;
    use HtmlBlock\Exception\HtmlBlockException as ExceptionHtmlBlock;
    use \DOMDocument as DOMDocument;
    use \DOMElement as DOMElement;

    class Alert implements InterfaceHtmlBlock {
        private $dom_document;
        private $dom_element;
        private $model;
        private $container_class;
        private $id;
        private $class;
        private $style;

        public function __construct(array ...$kwargs) {
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }

            $util = new Util;

            $model = $util->contains($kwargs,'model')->getArray();
            $this->setModel($model);

            $container_class = $util->contains($kwargs,'container_class')->getString('col-md-12');
            $this->setContainerClass($container_class);

            $id = $util->contains($kwargs,'id')->getString();
            $this->setId($id);

            $class = $util->contains($kwargs,'class')->getString('alert alert-info alert-dismissible');
            $this->setClass($class);

            $style = $util->contains($kwargs,'style')->getString();
            $this->setStyle($style);

            $dom_document = new DOMDocument('1.0',HtmlBlock::ENCODING);

            $this->setDomDocument($dom_document);
            $this->ready();

            return $this;
        }

        private function getDomDocument(): DOMDocument {
            return $this->dom_document;
        }

        private function setDomDocument(DOMDocument $dom_document): self {
            $this->dom_document = $dom_document;

            return $this;
        }

        public function getDomElement(): DOMElement {
            return $this->dom_element;
        }

        private function setDomElement(DOMElement $dom_element): self {
            $this->dom_element = $dom_element;

            return $this;
        }

        private function getModel(): ?array {
            return $this->model;
        }

        private function setModel(?array $model): self {
            $this->model = $model;

            return $this;
        }

        private function getContainerClass(): ?string {
            return $this->container_class;
        }
 
        private function setContainerClass(?string $container_class): self {
            $this->container_class = $container_class;

            return $this;
        }

        private function getId(): ?string {
            return $this->id;
        }

        private function setId(?string $id): self {
            $this->id = $id;

            return $this;
        }

        private function getClass(): ?string {
            return $this->class;
        }

        private function setClass(?string $class): self {
            $this->class = $class;

            return $this;
        }

        private function getStyle(): ?string {
            return $this->style;
        }

        private function setStyle(?string $style): self {
            $this->style = $style;

            return $this;
        }

        private function ready(): self {
            $util = new Util;

            $dom_document = $this->getDomDocument();

            $dom_element = $dom_document->createElement('div');
            $dom_element->setAttribute('role','alert');
            $dom_element->setAttribute('id',$this->getId());
            $dom_element->setAttribute('class',$this->getClass());
            $dom_element->setAttribute('style',$this->getStyle());

            $model = $this->getModel();

            if (empty($model) || !is_array($model)) {
                $this->setDomElement($dom_element);
                $this->addContainer();

                return $this;
            }

            foreach ($model as $item) {
                $message = $util->contains($item,'message')->getString();
                $type = $util->contains($item,'type')->getString();

                $button = $dom_document->createElement('button');
                $button->setAttribute('type','button');
                $button->setAttribute('class','close');
                $button->setAttribute('data-dismiss','alert');
                $button->setAttribute('aria-label','Close');

                $p = $dom_document->createElement('p',$message);

                if (!empty($type)) {
                    $dom_element->setAttribute('class',vsprintf('alert alert-%s alert-dismissible',[$type]));
                }

                $dom_element->appendChild($button);
                $dom_element->appendChild($p);
            }

            $this->setDomElement($dom_element);
            $this->addContainer();

            return $this;
        }

        private function addContainer(): self {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $container_class = $this->getContainerClass();
 
            $div_container = $dom_document->createElement('div');
            $div_container->setAttribute('class',$container_class);
            $div_container->appendChild($dom_element);
 
            $this->setDomElement($div_container);

            return $this;
        }

        public function renderHtml(): string {
            $dom_document = $this->getDomDocument();

            return $dom_document->saveHTML();
        }
    }
}
