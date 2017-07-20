<?php

namespace Interface {
	interface HtmlBlock {
	    public function getDomDocument();
	    public function setDomDocument($dom_document);
	    public function getEncoding();
	    public function setEncoding($encoding);
	    public function getDomElement();
	    public function setDomElement($dom_element);
	    public function getModel();
	    public function setModel($model);
	}
}