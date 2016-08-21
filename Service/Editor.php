<?php

namespace Renus\ParametersEditorBundle\Service;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Yaml\Yaml;

class Editor
{
    /**
     * Yaml constructor.
     */
    public function __construct($rootDir)
    {
        $this->file = $rootDir. "/config/parameters.yml";
    }

    /**
     * @return array
     */
    public function readConfiguration()
    {

        $value = Yaml::parse(file_get_contents($this->file));

        if (! isset($value['parameters']['editable'])) {
            throw new ParameterNotFoundException("There is no editable section in your parameters.yml file");
        }

        return $value['parameters']['editable'];
    }

    /**
     * @param FormBuilder $form
     * @param array $editables
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function buildForm(FormBuilder $form, $editables = [])
    {
        foreach ($editables as $key => $editable) {
            $form->add($key, TextType::class, [
                'label'    => $key,
                'data'     => $editable,
                'required' => true
            ]);
        }

        $form->add('save', SubmitType::class, array('label' => 'Edit configuration'));
        return $form->getForm();
    }

    /**
     * @param array $editable
     */
    public function writeConfiguration(array $editable)
    {
        $value = Yaml::parse(file_get_contents($this->file));
        $value['parameters']['editable'] = $editable;
        $yaml = Yaml::dump($value);
        file_put_contents($this->file, $yaml);
    }
}