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
     * @var string $file
     */
    private $file;

    /**
     * @var bool $allParameters
     */
    private $allParameters;

    /**
     * @var string $keyword
     */
    private $keyword;

    /**
     * Editor constructor.
     * @param $rootDir
     * @param $allParameters
     * @param $keyword
     */
    public function __construct($rootDir, $allParameters, $keyword)
    {
        $this->file          = $rootDir. "/config/parameters.yml";
        $this->allParameters = $allParameters;
        $this->keyword       = $keyword;
    }

    /**
     * @return array
     */
    public function readConfiguration()
    {

        $value = Yaml::parse(file_get_contents($this->file));

        if (! $this->allParameters) {
            $return = [];
            foreach ($value['parameters'] as $key => $parameter) {
                if (explode(".", $key)[0] == $this->keyword) {
                    $return[explode(".", $key)[1]] = $parameter;
                }
            }

            if (empty($return)) {
                throw new ParameterNotFoundException("There is no editable section in your parameters.yml file");
            }

            return $return;
        }

        return $value['parameters'];
    }

    /**
     * @param FormBuilder $form
     * @param array $editables
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function buildForm(FormBuilder $form, $editables = [])
    {
        foreach ($editables as $key => $editable) {
            $form->add(str_replace(".","__",$key), TextType::class, [
                'label'    => $key,
                'data'     => $editable,
                'required' => ! empty($editable)
            ]);
        }

        $form->add('save', SubmitType::class, array('label' => 'Edit configuration'));
        return $form->getForm();
    }

    /**
     * @param array $editable
     */
    public function writeConfiguration(array $editables)
    {
        $value = Yaml::parse(file_get_contents($this->file));

        foreach($editables as $key => $editable) {
            $keyname = ((! $this->allParameters) ? $this->keyword . "." : "")  . str_replace("__",".", $key);
            $value['parameters'][$keyname] = $editable;
        }

        $yaml = Yaml::dump($value);
        file_put_contents($this->file, $yaml);
    }
}