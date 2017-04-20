<?php

namespace Component\HtmlBlock {
    use Core\{Util,Request};
    use Core\DAO\Transaction;
    use Core\Exception\WException;
    use \DOMDocument as DOMDocument;

    class Form {
        private const QUERY_LIMIT_DEFAULT = 9999;

        private $dom_document;
        private $dom_element;
        private $model;
        private $id;
        private $type;
        private $button;
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

            $encoding = $util->contains($kwargs,'encoding')->getString('UTF-8');
            $this->setEncoding($encoding);

            $model = $util->contains($kwargs,'model')->getArray();
            $model = $model[0];
            $this->setModel($model);

            $type = $util->contains($kwargs,'type')->getString();
            $this->setType($type);

            $button = $util->contains($kwargs,'button')->getArray();
            $this->setButton($button);

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

            $dom_element = $dom_document->createElement('form');

            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
                $this->setId($kwargs['id']);
            }

            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);
            }

            if (!empty($this->getType()) && $this->getType() == 'horizontal') {
                $dom_element->setAttribute('class','form-horizontal');
            }

            if (isset($kwargs['style']) && !empty($kwargs['style'])) {
                $dom_element->setAttribute('style',$kwargs['style']);
            }

            if (isset($kwargs['name']) && !empty($kwargs['name'])) {
                $dom_element->setAttribute('name',$kwargs['name']);
            }

            if (isset($kwargs['method']) && !empty($kwargs['method'])) {
                $dom_element->setAttribute('method',$kwargs['method']);
            }

            if (isset($kwargs['action']) && !empty($kwargs['action'])) {
                $dom_element->setAttribute('action',$kwargs['action']);
            }

            if (isset($kwargs['enctype']) && !empty($kwargs['enctype'])) {
                $dom_element->setAttribute('enctype',$kwargs['enctype']);
            }

            if (isset($kwargs['novalidate']) && !empty($kwargs['novalidate'])) {
                $dom_element->setAttribute('novalidate',$kwargs['novalidate']);
            }

            if (isset($kwargs['target']) && !empty($kwargs['target'])) {
                $dom_element->setAttribute('target',$kwargs['target']);
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

        private function getId() {
            return $this->id;
        }

        private function setId($id) {
            $this->id = $id;
        }

        private function getType() {
            return $this->type;
        }

        private function setType($type) {
            $this->type = $type;
        }

        private function getButton() {
            return $this->button;
        }

        private function setButton($button) {
            $this->button = $button;
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

        private function addFieldPrimaryKey($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();

            $input = $dom_document->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','hidden');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            return $input;
        }

        private function addFieldForeignKey($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','form-group');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label',$field_label);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!empty($type) && $type == 'horizontal') {
                $label->setAttribute('class','col-sm-2 control-label');
            }

            $select = $dom_document->createElement('select');

            if (array_key_exists('multiple',$schema->rule) && !empty($schema->rule['multiple'])) {
                $select->setAttribute('name',vsprintf('%s[]',[$field,]));
                $select->setAttribute('multiple','multiple');

            } else {
                $select->setAttribute('name',$field);
            }

            $select->setAttribute('class','form-control');
            $select->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!array_key_exists('multiple',$schema->rule)) {
                $option = $dom_document->createElement('option','---');
                $option->setAttribute('value','');

                $select->appendChild($option);
            }

            if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                $select->setAttribute('disabled','disabled');
            }

            $db_transaction = new Transaction();

            $class = get_class($schema->rule['table']);
            $class = new $class($db_transaction);

            $class_schema = $class->schema();

            $class_field_primarykey = null;
            $class_field_reference = null;

            foreach ($class_schema as $field_ => $object_schema) {
                if ($object_schema->method == 'primaryKey') {
                    $class_field_primarykey = $field_;

                    break;
                }
            }

            foreach ($class_schema as $field_ => $object_schema) {
                if (empty($class_field_reference) && $object_schema->method == 'char') {
                    $class_field_reference = $field_;
                }

                if (array_key_exists('reference',$object_schema->rule) && $object_schema->rule['reference'] === true) {
                    $class_field_reference = $field_;

                    break;
                }
            }

            $db_transaction->connect();

            if (array_key_exists('filter',$schema->rule)) {
                $class->where($schema->rule['filter']);
            }

            $data_list = $class
                ->limit(1,self::QUERY_LIMIT_DEFAULT)
                ->execute();

            $data_list = $data_list->data;

            if (!empty($data_list)) {
                foreach ($data_list as $data) {
                    $option = $dom_document->createElement('option',$data->$class_field_reference);
                    $option->setAttribute('value',$data->$class_field_primarykey);

                    if (!empty($model->$field)) {
                        if (is_array($model->$field)) {
                            foreach ($model->$field as $model_field) {
                                if ($model_field->$class_field_primarykey == $data->$class_field_primarykey) {
                                    $option->setAttribute('selected','selected');

                                    break;
                                }
                            }
                        } else {
                            if (is_object($model->$field) && $model->$field->$class_field_primarykey == $data->$class_field_primarykey) {
                                $option->setAttribute('selected','selected');
                            }
                        }
                    }

                    $select->appendChild($option);
                }
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($select);

                $div->appendChild($label);
                $div->appendChild($div_type_horizontal);

            } else {
                $div->appendChild($label);
                $div->appendChild($select);
            }

            return $div;
        }

        private function addFieldChar($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','form-group');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label',$field_label);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!empty($type) && $type == 'horizontal') {
                $label->setAttribute('class','col-sm-2 control-label');
            }

            if (array_key_exists('option',$schema->rule) && !empty($schema->rule['option'])) {
                $select_or_input = $dom_document->createElement('select');
                $select_or_input->setAttribute('name',$field);
                $select_or_input->setAttribute('class','form-control');
                $select_or_input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

                if (array_key_exists('multiple',$schema->rule) && !empty($schema->rule['multiple'])) {
                    $select_or_input->setAttribute('multiple','multiple');
                    $select_or_input->removeAttribute('name');
                    $select_or_input->setAttribute('name',vsprintf('%s[]',[$field,]));

                } else {
                    $option = $dom_document->createElement('option','---');
                    $option->setAttribute('value','');

                    $select_or_input->appendChild($option);
                }

                if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                    $select_or_input->setAttribute('disabled','disabled');
                }

                foreach ($schema->rule['option'] as $key => $value) {
                    $option = $dom_document->createElement('option',$value);
                    $option->setAttribute('value',$key);

                    if (array_key_exists('multiple',$schema->rule) && !empty($schema->rule['multiple'])) {
                        if (!is_null($model->$field) && in_array($key,$model->$field)) {
                            $option->setAttribute('selected','selected');
                        }

                    } else {
                        if (!is_null($model->$field) && $model->$field == $key) {
                            $option->setAttribute('selected','selected');
                        }
                    }

                    $select_or_input->appendChild($option);
                }

            } else {
                $input_type = 'text';

                if (array_key_exists('password',$schema->rule) && !empty($schema->rule['password'])) {
                    // $model->$field = null;
                    $input_type = 'password';
                }

                $select_or_input = $dom_document->createElement('input');
                $select_or_input->setAttribute('name',$field);
                $select_or_input->setAttribute('value',$model->$field);
                $select_or_input->setAttribute('type',$input_type);
                $select_or_input->setAttribute('class','form-control');
                $select_or_input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

                if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                    $select_or_input->setAttribute('disabled','disabled');
                }
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($select_or_input);

                $div->appendChild($label);
                $div->appendChild($div_type_horizontal);

            } else {
                $div->appendChild($label);
                $div->appendChild($select_or_input);
            }

            return $div;
        }

        private function addFieldBoolean($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','checkbox');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label');

            $paragraph = $dom_document->createElement('p',$field_label);

            $input = $dom_document->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value','1');
            $input->setAttribute('type','checkbox');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                $input->setAttribute('disabled','disabled');
            }

            if (!empty($model->$field)) {
                $input->setAttribute('checked','checked');
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_space_type_horizontal = $dom_document->createElement('div');
                $div_space_type_horizontal->setAttribute('class','col-sm-2');

                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($input);

                $label->appendChild($input);
                $label->appendChild($paragraph);

                $div_type_horizontal->appendChild($label);

                $div->appendChild($div_space_type_horizontal);
                $div->appendChild($div_type_horizontal);

            } else {
                $label->appendChild($input);
                $label->appendChild($paragraph);
                $div->appendChild($label);

            }

            return $div;
        }

        private function addFieldText($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','form-group');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label',$field_label);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!empty($type) && $type == 'horizontal') {
                $label->setAttribute('class','col-sm-2 control-label');
            }

            $input = $dom_document->createElement('textarea',$model->$field);
            $input->setAttribute('rows','3');
            $input->setAttribute('name',$field);
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                $input->setAttribute('disabled','disabled');
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($input);

                $div->appendChild($label);
                $div->appendChild($div_type_horizontal);

            } else {
                $div->appendChild($label);
                $div->appendChild($input);
            }

            return $div;
        }

        private function addFieldFloat($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','form-group');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label',$field_label);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!empty($type) && $type == 'horizontal') {
                $label->setAttribute('class','col-sm-2 control-label');
            }

            $input = $dom_document->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','text');
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                $input->setAttribute('disabled','disabled');
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($input);

                $div->appendChild($label);
                $div->appendChild($div_type_horizontal);

            } else {
                $div->appendChild($label);
                $div->appendChild($input);
            }

            return $div;
        }

        private function addFieldInteger($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','form-group');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label',$field_label);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!empty($type) && $type == 'horizontal') {
                $label->setAttribute('class','col-sm-2 control-label');
            }

            if (array_key_exists('option',$schema->rule) && !empty($schema->rule['option'])) {
                $select_or_input = $dom_document->createElement('select');
                $select_or_input->setAttribute('name',$field);
                $select_or_input->setAttribute('class','form-control');
                $select_or_input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

                if (array_key_exists('multiple',$schema->rule) && !empty($schema->rule['multiple'])) {
                    $select_or_input->setAttribute('multiple','multiple');
                    $select_or_input->removeAttribute('name');
                    $select_or_input->setAttribute('name',vsprintf('%s[]',[$field,]));
                }

                if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                    $select_or_input->setAttribute('disabled','disabled');
                }

                foreach ($schema->rule['option'] as $key => $value) {
                    $option = $dom_document->createElement('option',$value);
                    $option->setAttribute('value',$key);

                    if (array_key_exists('multiple',$schema->rule) && !empty($schema->rule['multiple'])) {
                        if (!is_null($model->$field) && in_array($key,$model->$field)) {
                            $option->setAttribute('selected','selected');
                        }

                    } else {
                        if (!is_null($model->$field) && $model->$field == $key) {
                            $option->setAttribute('selected','selected');
                        }
                    }

                    $select_or_input->appendChild($option);
                }

            } else {
                $input_type = 'text';

                $select_or_input = $dom_document->createElement('input');
                $select_or_input->setAttribute('name',$field);
                $select_or_input->setAttribute('value',$model->$field);
                $select_or_input->setAttribute('type',$input_type);
                $select_or_input->setAttribute('class','form-control');
                $select_or_input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

                if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                    $select_or_input->setAttribute('disabled','disabled');
                }
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($select_or_input);

                $div->appendChild($label);
                $div->appendChild($div_type_horizontal);

            } else {
                $div->appendChild($label);
                $div->appendChild($select_or_input);
            }

            return $div;
        }

        private function addFieldDatetime($model,$schema,$field) {
            $dom_document = $this->getDomDocument();
            $element_id = $this->getId();
            $type = $this->getType();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','form-group');

            $field_label = $field;

            if (array_key_exists('label',$schema->rule) && !empty($schema->rule['label'])) {
                $field_label = $schema->rule['label'];
            }

            if (!array_key_exists('null',$schema->rule) || (array_key_exists('null',$schema->rule) && empty($schema->rule['null']))) {
                $field_label = vsprintf('%s*',[$field_label,]);
            }

            $label = $dom_document->createElement('label',$field_label);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            if (!empty($type) && $type == 'horizontal') {
                $label->setAttribute('class','col-sm-2 control-label');
            }

            $div_group = $dom_document->createElement('div');
            $div_group->setAttribute('class','input-group');

            $span_icon = $dom_document->createElement('span');
            $span_icon->setAttribute('class','glyphicon glyphicon-calendar');
            $span_icon->setAttribute('aria-hidden','true');

            $span = $dom_document->createElement('span');
            $span->setAttribute('class','input-group-addon');
            $span->setAttribute('id',vsprintf('%s-icon-field-%s',[$element_id,$field]));

            $input = $dom_document->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','text');
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            if (array_key_exists('disabled',$schema->rule) && !empty($schema->rule['disabled'])) {
                $input->setAttribute('disabled','disabled');
            }

            $span->appendChild($span_icon);
            $div_group->appendChild($span);
            $div_group->appendChild($input);

            $div->appendChild($label);
            $div->appendChild($div_group);

            if (!empty($type) && $type == 'horizontal') {
                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');
                $div_type_horizontal->appendChild($div_group);

                $div->appendChild($label);
                $div->appendChild($div_type_horizontal);

            } else {
                $div->appendChild($label);
                $div->appendChild($div_group);
            }

            return $div;
        }

        private function addButton() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();
            $element_id = $this->getId();
            $button = $this->getButton();

            $div = $dom_document->createElement('div');
            $div->setAttribute('class','btn-group');
            $div->setAttribute('role','group');
            $div->setAttribute('aria-label','...');

            if (empty($button)) {
                return $div;
            }

            foreach ($button as $button_attribute) {
                if (empty($button_attribute)) {
                    continue;
                }

                $title = $button_attribute['title'] ?? null;
                $icon = $button_attribute['icon'] ?? null;
                $class = $button_attribute['class'] ?? null;
                $id = $button_attribute['id'] ?? null;
                $style = $button_attribute['style'] ?? null;
                $type = $button_attribute['type'] ?? 'submit';
                $element = $button_attribute['element'] ?? 'button';
                $href = $button_attribute['href'] ?? null;

                $button = $dom_document->createElement($element);
                $button->setAttribute('type',$type);
                $button->setAttribute('id',$id);

                if (!empty($href)) {
                    $button->setAttribute('href',$href);
                }

                if (!empty($class)) {
                    $button->setAttribute('class',$class);

                } else {
                    $button->setAttribute('class','btn btn-default');
                }

                if (!empty($style)) {
                    $button->setAttribute('style',$style);

                }

                if (!empty($icon)) {
                    $span = $dom_document->createElement('span');
                    $span->setAttribute('class',$icon);

                    $button->appendChild($span);
                }

                $button->appendChild(new \DOMText($title));

                $div->appendChild($button);
            }

            return $div;
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

        private function modelPersist() {
            $model = $this->getModel();

            $request = new Request();
            $util = new util();

            $dom_document_form = $request->getHttpSession();

            $html_block_form = $util->contains($request->getHttpSession(),'html_block_form')->getArray();

            if (!empty($html_block_form)) {
                foreach ($html_block_form as $field_key => $field_value) {
                    if (property_exists($model,$field_key)) {
                        $model->$field_key = $field_value;
                    }
                }

                $this->setModel($model);

                $request->cleanHttpSession('html_block_form');
            }

            return $this;
        }

        private function ready() {
            $dom_document = $this->getDomDocument();
            $dom_element = $this->getDomElement();

            $this->modelPersist();

            $model = $this->getModel();

            $element_id = $this->getId();
            $type = $this->getType();

            foreach ($model->schema() as $field => $schema) {
                if (array_key_exists('hidden',$schema->rule) && !empty($schema->rule)) {
                    continue;
                }

                if ($schema->method == 'primaryKey') {
                    $add_field_primaryKey = $this->addFieldPrimaryKey($model,$schema,$field);

                    $dom_element->appendChild($add_field_primaryKey);

                } else if ($schema->method == 'foreignKey') {
                    $add_field_foreignkey = $this->addFieldForeignKey($model,$schema,$field);

                    $dom_element->appendChild($add_field_foreignkey);

                } else if ($schema->method == 'char') {
                    $add_field_char = $this->addFieldChar($model,$schema,$field);

                    $dom_element->appendChild($add_field_char);

                } else if ($schema->method == 'boolean') {
                    $add_field_boolean = $this->addFieldBoolean($model,$schema,$field);

                    $dom_element->appendChild($add_field_boolean);

                } else if ($schema->method == 'text') {
                    $add_field_text = $this->addFieldText($model,$schema,$field);

                    $dom_element->appendChild($add_field_text);

                } else if ($schema->method == 'float') {
                    $add_field_float = $this->addFieldFloat($model,$schema,$field);

                    $dom_element->appendChild($add_field_float);

                } else if ($schema->method == 'integer') {
                    $add_field_integer = $this->addFieldInteger($model,$schema,$field);

                    $dom_element->appendChild($add_field_integer);

                } else if ($schema->method == 'datetime') {
                    $add_field_datetime = $this->addFieldDatetime($model,$schema,$field);

                    $dom_element->appendChild($add_field_datetime);
                }
            }

            if (!empty($type) && $type == 'horizontal') {
                $div_row_type_horizontal = $dom_document->createElement('div');
                $div_row_type_horizontal->setAttribute('class','row');

                $div_space_type_horizontal = $dom_document->createElement('div');
                $div_space_type_horizontal->setAttribute('class','col-sm-2');

                $div_type_horizontal = $dom_document->createElement('div');
                $div_type_horizontal->setAttribute('class','col-sm-10');

                $div_button = $this->addButton();
                $div_type_horizontal->appendChild($div_button);

                $div_row_type_horizontal->appendChild($div_space_type_horizontal);
                $div_row_type_horizontal->appendChild($div_type_horizontal);

                $dom_element->appendChild($div_row_type_horizontal);

            } else {
                $div_button = $this->addButton();
                $dom_element->appendChild($div_button);
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
