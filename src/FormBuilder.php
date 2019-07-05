<?php

namespace FormBuilder;

class FormBuilder
{
    private $formObj = [];

    public function __construct($url, $method = 'POST', array $attributes = [])
    {
        $attributes = array_merge($attributes, [
            'action' => $url,
            'method' => $method,
            'id' => $attributes['id'] ?? 'fb_' . time(),
        ]);
        $this->formObj = [
            'start' => "<form {$this->attributesPrepare($attributes)}>",
            'end' => '</form>',
            'attributes' => $attributes,
            'children' => [],
        ];
    }

    public function inputText($name, $value, $attributes = [])
    {
        return $this->setChildren($this->inputField('text', $name, $value, $attributes));
    }

    public function inputPassword($name, $value, $attributes = [])
    {
        return $this->setChildren($this->inputField('password', $name, $value, $attributes));
    }

    public function inputHidden($name, $value, $attributes = [])
    {
        return $this->setChildren($this->inputField('hidden', $name, $value, $attributes));
    }

    private function inputField($type, $name, $value, array $attributes = [])
    {
        $attributes = array_merge($attributes, ['name' => $name, 'type' => $type, 'value' => $value]);
        return "<input {$this->attributesPrepare($attributes)}>";
    }

    public function inputPasswordRecursive($name, $value)
    {
        $this->recursiveElementCreator($name, $value, 'inputPassword');
    }

    public function inputTextRecursive($name, $value)
    {
        $this->recursiveElementCreator($name, $value, 'inputText');
    }

    public function inputHiddenRecursive($name, $value)
    {
        $this->recursiveElementCreator($name, $value, 'inputHidden');
    }

    public function build()
    {
        $formObj = $this->getFormObj();
        $htmlOutput = $formObj['start'];
        foreach ($formObj['children'] as $child) {
            $htmlOutput .= " {$child} \n";
        }

        return $htmlOutput;
    }

    public function submit()
    {
        echo $this->build();
        echo "<script>document.getElementById(\"{$this->getFormObj()['attributes']['id']}\").submit();</script>";
        die();
    }

    public function getFormObj(): array
    {
        return $this->formObj;
    }

    private function setChildren($tag)
    {
        $this->formObj['children'][] = $tag;
        return $this;
    }

    private function attributesPrepare(array $attributes): string
    {
        $string_attributes = '';
        foreach ($attributes as $key => $value) {
            $string_attributes .= "{$key}=\"{$this->castValue($value)}\" ";
        }
        return $string_attributes;
    }

    private function castValue($value)
    {
        return $value;
    }

    private function recursiveElementCreator($name, $value, $method_name)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $name_attribute = $name . "[{$key}]";
                if (is_array($item)) {
                    $this->recursiveElementCreator($name_attribute, $item, $method_name);
                } else {
                    $this->{$method_name}($name_attribute, $item);
                }
            }
        } else {
            $this->{$method_name}($name, $value);
        }
    }
}
