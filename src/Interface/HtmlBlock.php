<?php
declare(strict_types=1);

namespace HtmlBlock\Interface {
	interface HtmlBlock {
	    public function getDomDocument();
	    public function setDomDocument(\DOMDocument $dom_document);
	    public function getDomElement();
	    public function setDomElement(\DOMElement $dom_element);
	    public function getModel();
	    public function setModel(array $model);
	    public function getContainerClass();
		public function setContainerClass(string $container_class);
	    public function ready();
	    public function addContainer();
	    public function renderHtml();
	}
}