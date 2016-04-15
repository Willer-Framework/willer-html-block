<?php
 
namespace Core\Component\HtmlBlock {
    use Core\Exception\WException;
    use Core\Util;
    use Core\DAO\Transaction;
 
    class Form {
        private $html_block;
        private $dom_element;
        private $model;
        private $label;
        private $id;
        private $title;
        private $text;
        private $footer;
        private $container_class;
        private $container_style;
 
        public function __construct($html_block,...$kwargs) {
            $this->setHtmlBlock($html_block);
 
            if (!empty($kwargs)) {
                $kwargs = $kwargs[0];
            }
 
            $model = Util::get($kwargs,'model',null);
            $this->setModel($model);
 
            $label = Util::get($kwargs,'label',null);
            $this->setLabel($label);

            $title = Util::get($kwargs,'title',null);
            $this->setTitle($title);

            $text = Util::get($kwargs,'text',null);
            $this->setText($text);

            $footer = Util::get($kwargs,'footer',null);
            $this->setFooter($footer);
 
            $container_class = Util::get($kwargs,'container_class',null);
            $this->setContainerClass($container_class);
 
            $container_style = Util::get($kwargs,'container_style',null);
            $this->setContainerStyle($container_style);
 
            $dom_element = $html_block->createElement('form');
 
            if (isset($kwargs['id']) && !empty($kwargs['id'])) {
                $dom_element->setAttribute('id',$kwargs['id']);
                $this->setId($kwargs['id']);
            }
 
            if (isset($kwargs['class']) && !empty($kwargs['class'])) {
                $dom_element->setAttribute('class',$kwargs['class']);
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
 
        private function getLabel() {
            return $this->label;
        }
 
        private function setLabel($label) {
            $this->label = $label;
        }

        private function getId() {
            return $this->id;
        }

        private function setId($id) {
            $this->id = $id;
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
 
        private function addFieldForeignKey($model,$schema,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
 
            $div = $html_block->createElement('div');
            $div->setAttribute('class','form-group');
 
            $label = $html_block->createElement('label',$field);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $select = $html_block->createElement('select');
            $select->setAttribute('name',$field);
            $select->setAttribute('class','form-control');
            $select->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $db_transaction = new Transaction();
 
            $class = get_class($schema->rule['table']);
            $class = new $class($db_transaction);
 
            $class_schema = $class->schema();
 
            $class_field_primarykey = null;
            $class_field_reference = null;
 
            foreach ($class_schema as $field_ => $object_schema) {
                if ($object_schema->method == 'primaryKey') {
                    $class_field_primarykey = $field_;
                }
 
                if (empty($class_field_reference) && $object_schema->method == 'char') {
                    $class_field_reference = $field_;
                }
 
                if (array_key_exists('reference',$object_schema->rule)) {
                    $class_field_reference = $field_;
 
                    if (!empty($class_field_primarykey)) {
                        break;
                    }
                }
            }
 
            $db_transaction->connect();
 
            $data_list = $class
                ->execute([
                    'join' => 'left']);
 
            $data_list = $data_list['data'];
 
            if (!empty($data_list)) {
                foreach ($data_list as $data) {
                    $option = $html_block->createElement('option',$data->$class_field_reference);
                    $option->setAttribute('value',$data->$class_field_primarykey);
 
                    if (!empty($model->$field) && $model->$field->$class_field_primarykey == $data->$class_field_primarykey) {
                        $option->setAttribute('selected','selected');
                    }
 
                    $select->appendChild($option);
                }
            }
 
            $div->appendChild($label);
            $div->appendChild($select);
 
            return $div;
        }
 
        private function addFieldChar($model,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
 
            $div = $html_block->createElement('div');
            $div->setAttribute('class','form-group');
 
            $label = $html_block->createElement('label',$field);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $input = $html_block->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','text');
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $div->appendChild($label);
            $div->appendChild($input);
 
            return $div;
        }
 
        private function addFieldBoolean($model,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
 
            $div = $html_block->createElement('div');
            $div->setAttribute('class','checkbox');
 
            $label = $html_block->createElement('label');
 
            $paragraph = $html_block->createElement('p',$field);
 
            $input = $html_block->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value','1');
            $input->setAttribute('type','checkbox');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));
 
            if (!empty($model->$field)) {
                $input->setAttribute('checked','checked');
            }
 
            $label->appendChild($input);
            $label->appendChild($paragraph);
            $div->appendChild($label);
 
            return $div;
        }

        private function addFieldText($model,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
 
            $div = $html_block->createElement('div');
            $div->setAttribute('class','form-group');
 
            $label = $html_block->createElement('label',$field);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $input = $html_block->createElement('textarea',$model->$field);
            $input->setAttribute('rows','3');
            $input->setAttribute('name',$field);
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $div->appendChild($label);
            $div->appendChild($input);
 
            return $div;
        }

        private function addFieldFloat($model,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
 
            $div = $html_block->createElement('div');
            $div->setAttribute('class','form-group');
 
            $label = $html_block->createElement('label',$field);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $input = $html_block->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','text');
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $div->appendChild($label);
            $div->appendChild($input);
 
            return $div;
        }

        private function addFieldInteger($model,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();
 
            $div = $html_block->createElement('div');
            $div->setAttribute('class','form-group');
 
            $label = $html_block->createElement('label',$field);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $input = $html_block->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','text');
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));
 
            $div->appendChild($label);
            $div->appendChild($input);
 
            return $div;
        }

        private function addFieldDatetime($model,$field) {
            $html_block = $this->getHtmlBlock();
            $element_id = $this->getId();

            $div = $html_block->createElement('div');
            $div->setAttribute('class','form-group');
 
            $label = $html_block->createElement('label',$field);
            $label->setAttribute('for',vsprintf('%s-field-%s',[$element_id,$field]));

            $div_group = $html_block->createElement('div');
            $div_group->setAttribute('class','input-group');

            $span_icon = $html_block->createElement('span');
            $span_icon->setAttribute('class','glyphicon glyphicon-calendar');
            $span_icon->setAttribute('aria-hidden','true');

            $span = $html_block->createElement('span');
            $span->setAttribute('class','input-group-addon');
            $span->setAttribute('id',vsprintf('%s-icon-field-%s',[$element_id,$field]));
 
            $input = $html_block->createElement('input');
            $input->setAttribute('name',$field);
            $input->setAttribute('value',$model->$field);
            $input->setAttribute('type','text');
            $input->setAttribute('class','form-control');
            $input->setAttribute('id',vsprintf('%s-field-%s',[$element_id,$field]));

            $span->appendChild($span_icon);
            $div_group->appendChild($span);
            $div_group->appendChild($input);
 
            $div->appendChild($label);
            $div->appendChild($div_group);
 
            return $div;
        }
 
        private function addPanel() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $title = $this->getTitle();
            $text = $this->getText();
            $footer = $this->getFooter();
 
            if (empty($title) && empty($text) && empty($footer)) {
                return false;
            }
 
            $div_class_panel = $html_block->createElement('div');
            $div_class_panel->setAttribute('class','panel panel-default');
 
            if (!empty($title)) {
                $div_class_panel_head = $html_block->createElement('div',$title);
                $div_class_panel_head->setAttribute('class','panel-heading');
                $node_div_panel_head = $div_class_panel->appendChild($div_class_panel_head);
            }
 
            $div_class_panel_body = $html_block->createElement('div');

            if (!empty($text)) {
                $p_text = $html_block->createElement('p',$text);
                $div_class_panel_body->appendChild($p_text);
            }

            $div_class_panel_body->setAttribute('class','panel-body');
            $node_div_panel_body = $div_class_panel->appendChild($div_class_panel_body);
            $node_div_panel_body->appendChild($dom_element);
 
            if (!empty($footer)) {
                $div_class_panel_footer = $html_block->createElement('div',$footer);
                $div_class_panel_footer->setAttribute('class','panel-footer');
                $node_div_panel_footer = $div_class_panel->appendChild($div_class_panel_footer);
            }
 
            $this->setDomElement($div_class_panel);
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
 
            $this->setDomElement($div_class_col);
        }
 
        private function ready() {
            $html_block = $this->getHtmlBlock();
            $dom_element = $this->getDomElement();
            $model = $this->getModel();
            $element_id = $this->getId();
 
            foreach ($model->schema() as $field => $schema) {
                if ($schema->method == 'foreignKey') {
                    $add_field_foreignkey = $this->addFieldForeignKey($model,$schema,$field);
 
                    $dom_element->appendChild($add_field_foreignkey);
 
                } else if ($schema->method == 'char') {
                    $add_field_char = $this->addFieldChar($model,$field);
 
                    $dom_element->appendChild($add_field_char);
 
                } else if ($schema->method == 'boolean') {
                    $add_field_boolean = $this->addFieldBoolean($model,$field);
 
                    $dom_element->appendChild($add_field_boolean);

                } else if ($schema->method == 'text') {
                    $add_field_text = $this->addFieldText($model,$field);
 
                    $dom_element->appendChild($add_field_text);

                } else if ($schema->method == 'float') {
                    $add_field_float = $this->addFieldFloat($model,$field);
 
                    $dom_element->appendChild($add_field_float);

                } else if ($schema->method == 'integer') {
                    $add_field_integer = $this->addFieldInteger($model,$field);
 
                    $dom_element->appendChild($add_field_integer);

                } else if ($schema->method == 'datetime') {
                    $add_field_datetime = $this->addFieldDatetime($model,$field);
 
                    $dom_element->appendChild($add_field_datetime);
                }
            }
 
            $button = $html_block->createElement('button','Salvar');
            $button->setAttribute('type','submit');
            $button->setAttribute('id',vsprintf('%s-field-button-save',[$element_id,]));
            $button->setAttribute('class','btn btn-default');
 
            $dom_element->appendChild($button);
            $this->addPanel();
            $this->addContainer();
        }
 
        public function renderHtml() {
            $html_block = $this->getHtmlBlock();
 
            $html_block->appendBodyContainerRow($this);
 
            return $html_block->renderHtml();
        }
    }
}
